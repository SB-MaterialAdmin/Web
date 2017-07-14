#include <textparse>

#define GROUP_STATE_NONE		0
#define GROUP_STATE_GROUPS		1
#define GROUP_STATE_INGROUP		2
#define GROUP_STATE_OVERRIDES	3
#define GROUP_PASS_FIRST		1
#define GROUP_PASS_SECOND		2

static Handle:g_hGroupParser;
static GroupId:g_CurGrp = INVALID_GROUP_ID;
static g_GroupState = GROUP_STATE_NONE;
static g_GroupPass = 0;
static bool:g_NeedReparse = false;

public SMCResult:ReadGroups_NewSection(Handle:smc, const String:name[], bool:opt_quotes)
{
	if (g_IgnoreLevel)
	{
		g_IgnoreLevel++;
		return SMCParse_Continue;
	}
	
	if (g_GroupState == GROUP_STATE_NONE)
	{
		if (StrEqual(name, "Groups", false))
		{
			g_GroupState = GROUP_STATE_GROUPS;
		} else {
			g_IgnoreLevel++;
		}
	} else if (g_GroupState == GROUP_STATE_GROUPS) {
		if ((g_CurGrp = CreateAdmGroup(name)) == INVALID_GROUP_ID)
		{
			g_CurGrp = FindAdmGroup(name);
		}
		g_GroupState = GROUP_STATE_INGROUP;
	} else if (g_GroupState == GROUP_STATE_INGROUP) {
		if (StrEqual(name, "Overrides", false))
		{
			g_GroupState = GROUP_STATE_OVERRIDES;
		} else {
			g_IgnoreLevel++;
		}
	} else {
		g_IgnoreLevel++;
	}
	
	return SMCParse_Continue;
}

public SMCResult:ReadGroups_KeyValue(Handle:smc, const String:key[], const String:value[], bool:key_quotes, bool:value_quotes)
{
	if (g_CurGrp == INVALID_GROUP_ID || g_IgnoreLevel)
	{
		return SMCParse_Continue;
	}
	
	new AdminFlag:flag;
	
	if (g_GroupPass == GROUP_PASS_FIRST)
	{
		if (g_GroupState == GROUP_STATE_INGROUP)
		{
			if (StrEqual(key, "flags", false))
			{
				new len = strlen(value);
				for (new i = 0; i < len; i++)
				{
					if (!FindFlagByChar(value[i], flag))
					{
						continue;
					}
					SetAdmGroupAddFlag(g_CurGrp, flag, true);
				}
			} else if (StrEqual(key, "immunity", false)) {
				g_NeedReparse = true;
			}
		} else if (g_GroupState == GROUP_STATE_OVERRIDES) {
			new OverrideRule:rule = Command_Deny;
			
			if (StrEqual(value, "allow", false))
			{
				rule = Command_Allow;
			}
			
			if (key[0] == '@')
			{
				AddAdmGroupCmdOverride(g_CurGrp, key[1], Override_CommandGroup, rule);
			} else {
				AddAdmGroupCmdOverride(g_CurGrp, key, Override_Command, rule);
			}
		}
	} else if (g_GroupPass == GROUP_PASS_SECOND
		 && g_GroupState == GROUP_STATE_INGROUP) {
		/* Check for immunity again, core should handle double inserts */
		if (StrEqual(key, "immunity", false))
		{
			/* If it's a value we know about, use it */
			if (StrEqual(value, "*"))
			{
				SetAdmGroupImmunityLevel(g_CurGrp, 2);
			} else if (StrEqual(value, "$")) {
				SetAdmGroupImmunityLevel(g_CurGrp, 1);
			} else {
				new level;
				if (StringToIntEx(value, level))
				{
					SetAdmGroupImmunityLevel(g_CurGrp, level);
				} else {
					new GroupId:id;
					if (value[0] == '@')
					{
						id = FindAdmGroup(value[1]);
					} else {
						id = FindAdmGroup(value);
					}
					if (id != INVALID_GROUP_ID)
					{
						SetAdmGroupImmuneFrom(g_CurGrp, id);
					} else {
						ParseError("Unable to find group: \"%s\"", value);
					}
				}
			}
		}
	}
	
	return SMCParse_Continue;
}

public SMCResult:ReadGroups_EndSection(Handle:smc)
{
	/* If we're ignoring, skip out */
	if (g_IgnoreLevel)
	{
		g_IgnoreLevel--;
		return SMCParse_Continue;
	}
	
	if (g_GroupState == GROUP_STATE_OVERRIDES)
	{
		g_GroupState = GROUP_STATE_INGROUP;
	} else if (g_GroupState == GROUP_STATE_INGROUP) {
		g_GroupState = GROUP_STATE_GROUPS;
		g_CurGrp = INVALID_GROUP_ID;
	} else if (g_GroupState == GROUP_STATE_GROUPS) {
		g_GroupState = GROUP_STATE_NONE;
	}
	
	return SMCParse_Continue;
}

public SMCResult:ReadGroups_CurrentLine(Handle:smc, const String:line[], lineno)
{
	g_CurrentLine = lineno;
	
	return SMCParse_Continue;
}

static InitializeGroupParser()
{
	if (!g_hGroupParser)
	{
		g_hGroupParser = SMC_CreateParser();
		SMC_SetReaders(g_hGroupParser, ReadGroups_NewSection, ReadGroups_KeyValue, ReadGroups_EndSection);
		SMC_SetRawLine(g_hGroupParser, ReadGroups_CurrentLine);
	}
}

static InternalReadGroups(const String:path[], pass)
{
	/* Set states */
	InitGlobalStates();
	g_GroupState = GROUP_STATE_NONE;
	g_CurGrp = INVALID_GROUP_ID;
	g_GroupPass = pass;
	g_NeedReparse = false;
	
	new SMCError:err = SMC_ParseFile(g_hGroupParser, path);
	if (err != SMCError_Okay)
	{
		new String:buffer[64];
		if (SMC_GetErrorString(err, buffer, sizeof(buffer)))
		{
			ParseError("%s", buffer);
		} else {
			ParseError("Fatal parse error");
		}
	}
}

ReadGroups()
{
	InitializeGroupParser();
	
	BuildPath(Path_SM, g_Filename, sizeof(g_Filename), "configs/sourcebans/sb_admin_groups.cfg");
	
	InternalReadGroups(g_Filename, GROUP_PASS_FIRST);
	if (g_NeedReparse)
	{
		InternalReadGroups(g_Filename, GROUP_PASS_SECOND);
	}
}
