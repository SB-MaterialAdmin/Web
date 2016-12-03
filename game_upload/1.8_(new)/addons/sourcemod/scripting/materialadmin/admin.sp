int g_iIgnoreLevel = 0;               /* Nested ignored section count, so users can screw up files safely */

enum GroupState
{
	GroupState_None,
	GroupState_Groups,
	GroupState_InGroup,
	GroupState_Overrides,
}

enum GroupPass
{
	GroupPass_Invalid,
	GroupPass_First,
	GroupPass_Second,
}

static SMCParser g_smcGroupParser;
static GroupId g_idGroup = INVALID_GROUP_ID;
static GroupState g_iGroupState = GroupState_None;
static GroupPass g_iGroupPass = GroupPass_Invalid;
static bool g_bNeedReparse = false;

enum UserState
{
	UserState_None,
	UserState_Admins,
	UserState_InAdmin,
}

static SMCParser g_smcUserParser;
static UserState g_iUserState = UserState_None;
static char g_sCurAuth[64],
	g_sCurIdent[64],
	g_sCurName[64],
	g_sCurPass[64];
static ArrayList g_aGroupArray;
static int g_iCurFlags,
	g_iCurImmunity,
	g_iCurExpire;
	
enum OverrideState
{
	OverrideState_None,
	OverrideState_Levels,
	OverrideState_Overrides,
}

static SMCParser g_smcOverrideParser;
static OverrideState g_iOverrideState = OverrideState_None;
//-----------------------------------------------------------------------------------------------------
public SMCResult ReadGroups_NewSection(SMCParser smc, const char[] sName, bool opt_quotes)
{
	if (g_iIgnoreLevel)
	{
		g_iIgnoreLevel++;
		return SMCParse_Continue;
	}
	
	if (g_iGroupState == GroupState_None)
	{
		if (StrEqual(sName, "groups", false))
			g_iGroupState = GroupState_Groups;
		else
			g_iIgnoreLevel++;
	} 
	else if (g_iGroupState == GroupState_Groups)
	{
		if ((g_idGroup = CreateAdmGroup(sName)) == INVALID_GROUP_ID)
			g_idGroup = FindAdmGroup(sName);
		
	#if DEBUG
		LogToFile(g_sLogFile, "Laod group (grup %d, %s)", g_idGroup, sName);
	#endif
		g_iGroupState = GroupState_InGroup;
	} 
	else if (g_iGroupState == GroupState_InGroup)
	{
		if (StrEqual(sName, "Overrides", false))
			g_iGroupState = GroupState_Overrides;
		else
			g_iIgnoreLevel++;
	} 
	else
		g_iIgnoreLevel++;
	
	return SMCParse_Continue;
}

public SMCResult ReadGroups_KeyValue(SMCParser smc, const char[] sKey, const char[] sValue, bool key_quotes, bool value_quotes)
{
	if (g_idGroup == INVALID_GROUP_ID || g_iIgnoreLevel)
		return SMCParse_Continue;

	AdminFlag admFlag;
	
	if (g_iGroupPass == GroupPass_First)
	{
		if (g_iGroupState == GroupState_InGroup)
		{
			if (StrEqual(sKey, "flags"))
			{
				int iLen = strlen(sValue);
				for (int i = 0; i < iLen; i++)
				{
					if (!FindFlagByChar(sValue[i], admFlag))
						continue;

					g_idGroup.SetFlag(admFlag, true);
				}
			} 
			else if (StrEqual(sKey, "immunity"))
				g_bNeedReparse = true;
			
		#if DEBUG
			LogToFile(g_sLogFile, "Laod group flag override (grup %d, %s %s)", g_idGroup, sKey, sValue);
		#endif
		} 
		else if (g_iGroupState == GroupState_Overrides)
		{
			OverrideRule overRule = Command_Deny;
			
			if (StrEqual(sValue, "allow"))
				overRule = Command_Allow;
			
			if (sKey[0] == '@')
				g_idGroup.AddCommandOverride(sKey[1], Override_CommandGroup, overRule);
			else
				g_idGroup.AddCommandOverride(sKey, Override_Command, overRule);
			
		#if DEBUG
			LogToFile(g_sLogFile, "Laod group command override (group %d, %s, %s)", g_idGroup, sKey, sValue);
		#endif
		}
	}
	else if (g_iGroupPass == GroupPass_Second && g_iGroupState == GroupState_InGroup)
	{
		/* Check for immunity again, core should handle double inserts */
		if (StrEqual(sKey, "immunity"))
		{
			/* If it's a sValue we know about, use it */
			if (StrEqual(sValue, "*"))
				g_idGroup.ImmunityLevel = 2;
			else if (StrEqual(sValue, "$"))
				g_idGroup.ImmunityLevel = 1;
			else
			{
				int iLevel;
				if (StringToIntEx(sValue, iLevel))
					g_idGroup.ImmunityLevel = iLevel;
				else
				{
					GroupId idGroup;
					if (sValue[0] == '@')
						idGroup = FindAdmGroup(sValue[1]);
					else
						idGroup = FindAdmGroup(sValue);
					
					if (idGroup != INVALID_GROUP_ID)
						g_idGroup.AddGroupImmunity(idGroup);
					else
						LogToFile(g_sLogFile, "Unable to find group: \"%s\"", sValue);
				}
			}
		#if DEBUG
			LogToFile(g_sLogFile, "Laod group add immunity (%d, %s, %s)", g_idGroup, sKey, sValue);
		#endif
		}
	}
	
	return SMCParse_Continue;
}

public SMCResult ReadGroups_EndSection(SMCParser smc)
{
	/* If we're ignoring, skip out */
	if (g_iIgnoreLevel)
	{
		g_iIgnoreLevel--;
		return SMCParse_Continue;
	}
	
	if (g_iGroupState == GroupState_Overrides)
		g_iGroupState = GroupState_InGroup;
	else if (g_iGroupState == GroupState_InGroup)
	{
		g_iGroupState = GroupState_Groups;
		g_idGroup = INVALID_GROUP_ID;
	} 
	else if (g_iGroupState == GroupState_Groups)
		g_iGroupState = GroupState_None;
	
	return SMCParse_Continue;
}

static void InternalReadGroups(const char[] sPath, GroupPass grPass)
{
	/* Set states */
	g_iIgnoreLevel = 0;
	g_iGroupState = GroupState_None;
	g_idGroup = INVALID_GROUP_ID;
	g_iGroupPass = grPass;
	g_bNeedReparse = false;

	if(FileExists(sPath))
	{
		int iLine;
		SMCError err = g_smcGroupParser.ParseFile(sPath, iLine);
		if (err != SMCError_Okay)
		{
			char sError[256];
			g_smcGroupParser.GetErrorString(err, sError, sizeof(sError));
			LogToFile(g_sLogFile, "Could not parse file (line %d, file \"%s\"):", iLine, sPath);
			LogToFile(g_sLogFile, "Parser encountered error: %s", sError);
		}
	}
}

void ReadGroups()
{
	if (!g_smcGroupParser)
	{
		g_smcGroupParser = new SMCParser();
		g_smcGroupParser.OnEnterSection = ReadGroups_NewSection;
		g_smcGroupParser.OnKeyValue = ReadGroups_KeyValue;
		g_smcGroupParser.OnLeaveSection = ReadGroups_EndSection;
	}
	
	InternalReadGroups(g_sGroupsLoc, GroupPass_First);
	if (g_bNeedReparse)
		InternalReadGroups(g_sGroupsLoc, GroupPass_Second);
}
//----------------------------------------------------------------------------------------------------
public SMCResult ReadUsers_NewSection(SMCParser smc, const char[] sName, bool opt_quotes)
{
	if (g_iIgnoreLevel)
	{
		g_iIgnoreLevel++;
		return SMCParse_Continue;
	}
	
	if (g_iUserState == UserState_None)
	{
		if (StrEqual(sName, "admins", false))
			g_iUserState = UserState_Admins;
		else
			g_iIgnoreLevel++;
	}
	else if (g_iUserState == UserState_Admins)
	{
		g_iUserState = UserState_InAdmin;
		strcopy(g_sCurName, sizeof(g_sCurName), sName);
		g_sCurAuth[0] = '\0';
		g_sCurIdent[0] = '\0';
		g_sCurPass[0] = '\0';
		g_aGroupArray.Clear();
		g_iCurFlags = 0;
		g_iCurImmunity = 0;
		g_iCurExpire = 0;
	}
	else
		g_iIgnoreLevel++;
	
	return SMCParse_Continue;
}

public SMCResult ReadUsers_KeyValue(SMCParser smc, const char[] sKey, const char[] sValue, bool key_quotes, bool value_quotes)
{
	if (g_iUserState != UserState_InAdmin || g_iIgnoreLevel)
		return SMCParse_Continue;
	
	if (StrEqual(sKey, "auth"))
		strcopy(g_sCurAuth, sizeof(g_sCurAuth), sValue);
	else if (StrEqual(sKey, "identity"))
		strcopy(g_sCurIdent, sizeof(g_sCurIdent), sValue);
	else if (StrEqual(sKey, "password")) 
		strcopy(g_sCurPass, sizeof(g_sCurPass), sValue);
	else if (StrEqual(sKey, "group")) 
	{
		GroupId idGroup = FindAdmGroup(sValue);
		if (idGroup == INVALID_GROUP_ID)
			LogToFile(g_sLogFile, "Unknown group \"%s\"", sValue);

		g_aGroupArray.Push(idGroup);
	} 
	else if (StrEqual(sKey, "flags")) 
	{
		int iLen = strlen(sValue);
		AdminFlag admFlag;
		
		for (int i = 0; i < iLen; i++)
		{
			if (!FindFlagByChar(sValue[i], admFlag))
				LogToFile(g_sLogFile, "Invalid admFlag detected: %c", sValue[i]);
			else
				g_iCurFlags |= FlagToBit(admFlag);
		}
	} 
	else if (StrEqual(sKey, "immunity"))
	{
		if(sValue[0])
			g_iCurImmunity = StringToInt(sValue);
		else
			g_iCurImmunity = 0;
	}
	else if (StrEqual(sKey, "expire"))
	{
		if(sValue[0])
			g_iCurExpire = StringToInt(sValue);
		else
			g_iCurExpire = 0;
	}
	
	return SMCParse_Continue;
}

public SMCResult ReadUsers_EndSection(SMCParser smc)
{
	if (g_iIgnoreLevel)
	{
		g_iIgnoreLevel--;
		return SMCParse_Continue;
	}
	
	if (g_iUserState == UserState_InAdmin)
	{
		/* Dump this user to memory */
		if (g_sCurIdent[0] && g_sCurAuth[0])
		{
			AdminFlag admFlags[26];
			AdminId idAdmin;
			int i, iGroups, iFlags;
			
			if ((idAdmin = FindAdminByIdentity(g_sCurAuth, g_sCurIdent)) != INVALID_ADMIN_ID)
			{
				if (g_iCurExpire == 0 || g_iCurExpire > GetTime())
				{
				#if DEBUG
					LogToFile(g_sLogFile, "Add admin %s expire %d (auth %s, %s)", g_sCurName, g_iCurExpire, g_sCurAuth, g_sCurIdent);
				#endif
					AddAdminExpire(idAdmin, g_iCurExpire);
				}
				else
				{
				#if DEBUG
					LogToFile(g_sLogFile, "Admin %s expire end %d (auth %s, %s)", g_sCurName, g_iCurExpire, g_sCurAuth, g_sCurIdent);
				#endif
					RemoveAdmin(idAdmin);
					return SMCParse_Continue;
				}
			#if DEBUG
				LogToFile(g_sLogFile, "Find admin %s yes (%d, auth %s, %s)", g_sCurName, idAdmin, g_sCurAuth, g_sCurIdent);
			#endif
			}
			else
			{
			#if DEBUG
				LogToFile(g_sLogFile, "Find admin %s no (auth %s, %s)", g_sCurName, g_sCurAuth, g_sCurIdent);
			#endif
				idAdmin = CreateAdmin(g_sCurName);
			#if DEBUG
				LogToFile(g_sLogFile, "Create new admin %s (%d, auth %s, %s)", g_sCurName, idAdmin, g_sCurAuth, g_sCurIdent);
			#endif
				if (!idAdmin.BindIdentity(g_sCurAuth, g_sCurIdent))
				{
					RemoveAdmin(idAdmin);

					LogToFile(g_sLogFile, "Failed to bind auth \"%s\" to identity \"%s\"", g_sCurAuth, g_sCurIdent);
					return SMCParse_Continue;
				}
				
				if (g_iCurExpire == 0 || g_iCurExpire > GetTime())
				{
				#if DEBUG
					LogToFile(g_sLogFile, "Add admin %s expire %d (auth %s, %s)", g_sCurName, g_iCurExpire, g_sCurAuth, g_sCurIdent);
				#endif
					AddAdminExpire(idAdmin, g_iCurExpire);
				}
				else
				{
				#if DEBUG
					LogToFile(g_sLogFile, "Admin %s expire end %d (auth %s, %s)", g_sCurName, g_iCurExpire, g_sCurAuth, g_sCurIdent);
				#endif
					RemoveAdmin(idAdmin);
					return SMCParse_Continue;
				}
			}
			
			iGroups = g_aGroupArray.Length;
			for (i = 0; i < iGroups; i++)
				idAdmin.InheritGroup(g_aGroupArray.Get(i));

			if(g_sCurPass[0])
				idAdmin.SetPassword(g_sCurPass);

			if (idAdmin.ImmunityLevel < g_iCurImmunity)
				idAdmin.ImmunityLevel = g_iCurImmunity;
			
			iFlags = FlagBitsToArray(g_iCurFlags, admFlags, sizeof(admFlags));
			for (i = 0; i < iFlags; i++)
				idAdmin.SetFlag(admFlags[i], true);
			
		#if DEBUG
			LogToFile(g_sLogFile, "Laod yes admin (name %s, auth %s, ident %s, flag %d, pass %s, imuni %d, expire %d)", g_sCurName, g_sCurAuth, g_sCurIdent, g_iCurFlags, g_sCurPass, g_iCurImmunity, g_iCurExpire);
		#endif
		}
		else
		{
		#if DEBUG
			LogToFile(g_sLogFile, "Laod no admin (name %s, auth %s, ident %s, flag %d, pass %s, imuni %d, expire %d)", g_sCurName, g_sCurAuth, g_sCurIdent, g_iCurFlags, g_sCurPass, g_iCurImmunity, g_iCurExpire);
		#endif
			LogToFile(g_sLogFile, "Failed to create admin: did you forget either the auth or identity properties?");
		}
		
		g_iUserState = UserState_Admins;
	}
	else if (g_iUserState == UserState_Admins)
		g_iUserState = UserState_None;
	
	return SMCParse_Continue;
}

void ReadUsers()
{
	if (!g_smcUserParser)
	{
		g_smcUserParser = new SMCParser();
		g_smcUserParser.OnEnterSection = ReadUsers_NewSection;
		g_smcUserParser.OnKeyValue = ReadUsers_KeyValue;
		g_smcUserParser.OnLeaveSection = ReadUsers_EndSection;
		
		g_aGroupArray = new ArrayList();
	}

	g_iIgnoreLevel = 0;
	g_iUserState = UserState_None;
		
	if(FileExists(g_sAdminsLoc))
	{
		int iLine;
		SMCError err = g_smcUserParser.ParseFile(g_sAdminsLoc, iLine);
		if (err != SMCError_Okay)
		{
			char sError[256];
			g_smcUserParser.GetErrorString(err, sError, sizeof(sError));
			LogToFile(g_sLogFile, "Could not parse file (line %d, file \"%s\"):", iLine, g_sAdminsLoc);
			LogToFile(g_sLogFile, "Parser encountered error: %s", sError);
		}
	}
}
//-------------------------------------------------------------------------------------------

public SMCResult ReadOverrides_NewSection(SMCParser smc, const char[] sName, bool opt_quotes)
{
	if (g_iIgnoreLevel)
	{
		g_iIgnoreLevel++;
		return SMCParse_Continue;
	}
	
	if (g_iOverrideState == OverrideState_None)
	{
		if (StrEqual(sName, "override_commands", false))
			g_iOverrideState = OverrideState_Levels;
		else
			g_iIgnoreLevel++;
	}
	else if (g_iOverrideState == OverrideState_Levels)
	{
		if (StrEqual(sName, "override_groups", false))
			g_iOverrideState = OverrideState_Overrides;
		else
			g_iIgnoreLevel++;
	} 
	else
		g_iIgnoreLevel++;
	
	return SMCParse_Continue;
}

public SMCResult ReadOverrides_KeyValue(SMCParser smc, const char[] sKey, const char[] sValue, bool key_quotes, bool value_quotes)
{
	if (g_iIgnoreLevel)
		return SMCParse_Continue;
	
	int iFlags = ReadFlagString(sValue);
	
	if (g_iOverrideState == OverrideState_Levels)
		AddCommandOverride(sKey, Override_Command, iFlags);
	else if (g_iOverrideState == OverrideState_Overrides)
		AddCommandOverride(sKey, Override_CommandGroup, iFlags);
	
#if DEBUG
	LogToFile(g_sLogFile, "Laod overrid (%s, %s)", sKey, sValue);
#endif
	
	return SMCParse_Continue;
}

public SMCResult ReadOverrides_EndSection(SMCParser smc)
{
	/* If we're ignoring, skip out */
	if (g_iIgnoreLevel)
	{
		g_iIgnoreLevel--;
		return SMCParse_Continue;
	}
	
	if (g_iOverrideState == OverrideState_Levels)
		g_iOverrideState = OverrideState_None;
	else if (g_iOverrideState == OverrideState_Overrides)
	{
		/* We're totally done parsing */
		g_iOverrideState = OverrideState_Levels;
		return SMCParse_Halt;
	}
	
	return SMCParse_Continue;
}

void ReadOverrides()
{
	if (!g_smcOverrideParser)
	{
		g_smcOverrideParser = new SMCParser();
		g_smcOverrideParser.OnEnterSection = ReadOverrides_NewSection;
		g_smcOverrideParser.OnKeyValue = ReadOverrides_KeyValue;
		g_smcOverrideParser.OnLeaveSection = ReadOverrides_EndSection;
	}
	
	g_iIgnoreLevel = 0;
	g_iOverrideState = OverrideState_None;

	if(FileExists(g_sOverridesLoc))
	{
		int iLine;
		SMCError err = g_smcOverrideParser.ParseFile(g_sOverridesLoc, iLine);
		if (err != SMCError_Okay)
		{
			char sError[256];
			g_smcOverrideParser.GetErrorString(err, sError, sizeof(sError));
			LogToFile(g_sLogFile, "Could not parse file (line %d, file \"%s\"):", iLine, g_sOverridesLoc);
			LogToFile(g_sLogFile, "Parser encountered error: %s", sError);
		}
	}
}