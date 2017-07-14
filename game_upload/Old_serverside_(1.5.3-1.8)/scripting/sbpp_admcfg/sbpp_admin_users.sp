#include <textparse>

#define USER_STATE_NONE			0
#define USER_STATE_ADMINS		1
#define USER_STATE_INADMIN		2

static Handle:g_hUserParser;
static g_UserState = USER_STATE_NONE;
static String:g_CurAuth[64];
static String:g_CurIdent[64];
static String:g_CurName[64];
static String:g_CurPass[64];
static Handle:g_GroupArray;
static g_CurFlags;
static g_CurImmunity;

public SMCResult:ReadUsers_NewSection(Handle:smc, const String:name[], bool:opt_quotes)
{
	if (g_IgnoreLevel)
	{
		g_IgnoreLevel++;
		return SMCParse_Continue;
	}
	
	if (g_UserState == USER_STATE_NONE)
	{
		if (StrEqual(name, "Admins", false))
		{
			g_UserState = USER_STATE_ADMINS;
		}
		else
		{
			g_IgnoreLevel++;
		}
	}
	else if (g_UserState == USER_STATE_ADMINS)
	{
		g_UserState = USER_STATE_INADMIN;
		strcopy(g_CurName, sizeof(g_CurName), name);
		g_CurAuth[0] = '\0';
		g_CurIdent[0] = '\0';
		g_CurPass[0] = '\0';
		ClearArray(g_GroupArray);
		g_CurFlags = 0;
		g_CurImmunity = 0;
	}
	else
	{
		g_IgnoreLevel++;
	}
	
	return SMCParse_Continue;
}

public SMCResult:ReadUsers_KeyValue(Handle:smc, const String:key[], const String:value[], bool:key_quotes, bool:value_quotes)
{
	if (g_UserState != USER_STATE_INADMIN || g_IgnoreLevel)
	{
		return SMCParse_Continue;
	}
	
	if (StrEqual(key, "auth", false))
	{
		strcopy(g_CurAuth, sizeof(g_CurAuth), value);
	}
	else if (StrEqual(key, "identity", false))
	{
		strcopy(g_CurIdent, sizeof(g_CurIdent), value);
	}
	else if (StrEqual(key, "password", false)) 
	{
		strcopy(g_CurPass, sizeof(g_CurPass), value);
	}
	else if (StrEqual(key, "group", false)) 
	{
		new GroupId:id = FindAdmGroup(value);
		if (id == INVALID_GROUP_ID)
		{
			ParseError("Unknown group \"%s\"", value);
		}
		
		PushArrayCell(g_GroupArray, id);
	}
	else if (StrEqual(key, "flags", false))
	{
		new len = strlen(value);
		new AdminFlag:flag;
		
		for (new i = 0; i < len; i++)
		{
			if (!FindFlagByChar(value[i], flag))
			{
				ParseError("Invalid flag detected: %c", value[i]);
			}
			else
			{
				g_CurFlags |= FlagToBit(flag);
			}
		}
	}
	else if (StrEqual(key, "immunity", false))
	{
		g_CurImmunity = StringToInt(value);
	}
	
	return SMCParse_Continue;
}

public SMCResult:ReadUsers_EndSection(Handle:smc)
{
	if (g_IgnoreLevel)
	{
		g_IgnoreLevel--;
		return SMCParse_Continue;
	}
	
	if (g_UserState == USER_STATE_INADMIN)
	{
		/* Dump this user to memory */
		if (g_CurIdent[0] != '\0' && g_CurAuth[0] != '\0')
		{
			decl AdminFlag:flags[26];
			new AdminId:id, i, num_groups, num_flags;
			
			if ((id = FindAdminByIdentity(g_CurAuth, g_CurIdent)) == INVALID_ADMIN_ID)
			{
				id = CreateAdmin(g_CurName);
				if (!BindAdminIdentity(id, g_CurAuth, g_CurIdent))
				{
					RemoveAdmin(id);
					ParseError("Failed to bind auth \"%s\" to identity \"%s\"", g_CurAuth, g_CurIdent);
					return SMCParse_Continue;
				}
			}
			
			num_groups = GetArraySize(g_GroupArray);
			for (i = 0; i < num_groups; i++)
			{
				AdminInheritGroup(id, GetArrayCell(g_GroupArray, i));
			}
			
			SetAdminPassword(id, g_CurPass);
			if (GetAdminImmunityLevel(id) < g_CurImmunity)
			{
				SetAdminImmunityLevel(id, g_CurImmunity);
			}
			
			num_flags = FlagBitsToArray(g_CurFlags, flags, sizeof(flags));
			for (i = 0; i < num_flags; i++)
			{
				SetAdminFlag(id, flags[i], true);
			}
		}
		else
		{
			ParseError("Failed to create admin: did you forget either the auth or identity properties?");
		}
		
		g_UserState = USER_STATE_ADMINS;
	}
	else if (g_UserState == USER_STATE_ADMINS)
	{
		g_UserState = USER_STATE_NONE;
	}
	
	return SMCParse_Continue;
}

public SMCResult:ReadUsers_CurrentLine(Handle:smc, const String:line[], lineno)
{
	g_CurrentLine = lineno;
	
	return SMCParse_Continue;
}

static InitializeUserParser()
{
	if (!g_hUserParser)
	{
		g_hUserParser = SMC_CreateParser();
		SMC_SetReaders(g_hUserParser, ReadUsers_NewSection, ReadUsers_KeyValue, ReadUsers_EndSection);
		SMC_SetRawLine(g_hUserParser, ReadUsers_CurrentLine);

		g_GroupArray = CreateArray();
	}
}

ReadUsers()
{
	InitializeUserParser();
	
	BuildPath(Path_SM, g_Filename, sizeof(g_Filename), "configs/sourcebans/sb_admins.cfg");
	
	/* Set states */
	InitGlobalStates();
	g_UserState = USER_STATE_NONE;
	
	new SMCError:err = SMC_ParseFile(g_hUserParser, g_Filename);
	if (err != SMCError_Okay)
	{
		new String:buffer[64];
		if (SMC_GetErrorString(err, buffer, sizeof(buffer)))
		{
			ParseError("%s", buffer);
		}
		else
		{
			ParseError("Fatal parse error");
		}
	}
}