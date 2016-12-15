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

static SMCParser g_smcOverrideParser;

#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
public int OnRebuildAdminCache(AdminCachePart acPart)
#else
public void OnRebuildAdminCache(AdminCachePart acPart)
#endif
{
	if (g_bLalodAdmin)
	{
		if (acPart == AdminCache_Overrides)
			ReadOverrides();
		else if (acPart == AdminCache_Groups)
			ReadGroups();
		else if (acPart == AdminCache_Admins)
			ReadUsers();
	}
}
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
		if (StrEqual(sName, "overrides", false))
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
				for (int i = 0; i < strlen(sValue); i++)
				{
					if (!FindFlagByChar(sValue[i], admFlag))
						continue;

				#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
					SetAdmGroupAddFlag(g_idGroup, admFlag, true);
				#else
 					g_idGroup.SetFlag(admFlag, true);
				#endif
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
			#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
				AddAdmGroupCmdOverride(g_idGroup, sKey[1], Override_CommandGroup, overRule);
			#else
 				g_idGroup.AddCommandOverride(sKey[1], Override_CommandGroup, overRule);
			#endif
 			else
			#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
				AddAdmGroupCmdOverride(g_idGroup, sKey, Override_Command, overRule);
			#else
 				g_idGroup.AddCommandOverride(sKey, Override_Command, overRule);
			#endif
			
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
			#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
				SetAdmGroupImmunityLevel(g_idGroup, 2);
			#else
 				g_idGroup.ImmunityLevel = 2;
			#endif
			else if (StrEqual(sValue, "$"))
			#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
				SetAdmGroupImmunityLevel(g_idGroup, 1);
			#else
 				g_idGroup.ImmunityLevel = 1;
			#endif
			else
			{
				int iLevel;
				if (StringToIntEx(sValue, iLevel))
				#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
					SetAdmGroupImmunityLevel(g_idGroup, iLevel);
				#else
 					g_idGroup.ImmunityLevel = iLevel;
				#endif
				else
				{
					GroupId idGroup;
					if (sValue[0] == '@')
						idGroup = FindAdmGroup(sValue[1]);
					else
						idGroup = FindAdmGroup(sValue);
					
					if (idGroup != INVALID_GROUP_ID)
					#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
						SetAdmGroupImmuneFrom(g_idGroup, idGroup);
					#else
 						g_idGroup.AddGroupImmunity(idGroup);
					#endif
					else
						LogToFile(g_sLogFile, "Unable to find group: \"%s\"", sValue);
				}
			}
		#if DEBUG
			LogToFile(g_sLogFile, "Laod group add immunity level (%d, %s, %s)", g_idGroup, sKey, sValue);
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
		else
		{
		#if DEBUG
			LogToFile(g_sLogFile, "Admin %s group %s %d", g_sCurName, sValue, idGroup);
		#endif
			g_aGroupArray.Push(idGroup);
		}
	} 
	else if (StrEqual(sKey, "flags")) 
	{
		AdminFlag admFlag;
		
		for (int i = 0; i < strlen(sValue); i++)
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
			
			if ((idAdmin = FindAdminByIdentity(g_sCurAuth, g_sCurIdent)) != INVALID_ADMIN_ID)
			{
				if (g_iCurExpire == 0 || g_iCurExpire > GetTime())
				{
				#if DEBUG
					LogToFile(g_sLogFile, "Add admin %s expire %d", g_sCurName, g_iCurExpire);
				#endif
					AddAdminExpire(idAdmin, g_iCurExpire);
				}
				else
				{
				#if DEBUG
					LogToFile(g_sLogFile, "Admin %s expire end %d", g_sCurName, g_iCurExpire);
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
				idAdmin = CreateAdmin(g_sCurName);
			#if DEBUG
				LogToFile(g_sLogFile, "Create new admin %s (%d, auth %s, %s)", g_sCurName, idAdmin, g_sCurAuth, g_sCurIdent);
			#endif
			#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
				if (!BindAdminIdentity(idAdmin, g_sCurAuth, g_sCurIdent))
			#else
 				if (!idAdmin.BindIdentity(g_sCurAuth, g_sCurIdent))
			#endif
				{
					RemoveAdmin(idAdmin);

					LogToFile(g_sLogFile, "Failed to bind auth \"%s\" to identity \"%s\"", g_sCurAuth, g_sCurIdent);
					return SMCParse_Continue;
				}
				
				if (g_iCurExpire == 0 || g_iCurExpire > GetTime())
				{
				#if DEBUG
					LogToFile(g_sLogFile, "Add admin %s expire %d", g_sCurName, g_iCurExpire);
				#endif
					AddAdminExpire(idAdmin, g_iCurExpire);
				}
				else
				{
				#if DEBUG
					LogToFile(g_sLogFile, "Admin %s expire end %d", g_sCurName, g_iCurExpire);
				#endif
					RemoveAdmin(idAdmin);
					return SMCParse_Continue;
				}
			}
			
			
			GroupId idGroup;
			for (int i = 0; i < g_aGroupArray.Length; i++)
			{
				idGroup = g_aGroupArray.Get(i);
			#if DEBUG
				#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
				if (AdminInheritGroup(idAdmin, idGroup))
				#else
				if (idAdmin.InheritGroup(idGroup))
				#endif
					LogToFile(g_sLogFile, "Admin %s add group %d", g_sCurName, idGroup);
				else
					LogToFile(g_sLogFile, "Admin %s no add group %d", g_sCurName, idGroup);
			#else
				#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
				AdminInheritGroup(idAdmin, idGroup);
				#else
				idAdmin.InheritGroup(idGroup);
				#endif
			#endif
			}

			if(g_sCurPass[0])
			#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
				SetAdminPassword(idAdmin, g_sCurPass);
			#else
 				idAdmin.SetPassword(g_sCurPass);
			#endif

		#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
			if (GetAdminImmunityLevel(idAdmin) < g_iCurImmunity)
				SetAdminImmunityLevel(idAdmin, g_iCurImmunity);
		#else
			if (idAdmin.ImmunityLevel < g_iCurImmunity)
				idAdmin.ImmunityLevel = g_iCurImmunity;
		#endif
			
			int iFlags = FlagBitsToArray(g_iCurFlags, admFlags, sizeof(admFlags));
			for (int i = 0; i < iFlags; i++)
			#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
				SetAdminFlag(idAdmin, admFlags[i], true);
			#else
 				idAdmin.SetFlag(admFlags[i], true);
			#endif
			
		#if DEBUG
			LogToFile(g_sLogFile, "Laod yes admin (name %s, auth %s, ident %s, flag %d, imuni %d, expire %d)", g_sCurName, g_sCurAuth, g_sCurIdent, g_iCurFlags, g_iCurImmunity, g_iCurExpire);
		#endif
		}
		else
		{
		#if DEBUG
			LogToFile(g_sLogFile, "Laod no admin (name %s, auth %s, ident %s, flag %d, imuni %d, expire %d)", g_sCurName, g_sCurAuth, g_sCurIdent, g_iCurFlags, g_iCurImmunity, g_iCurExpire);
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
		g_aAdminsExpired.Clear();
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
	return SMCParse_Continue;
}

public SMCResult ReadOverrides_KeyValue(SMCParser smc, const char[] sKey, const char[] sValue, bool key_quotes, bool value_quotes)
{
	if(!sKey[0])
		return SMCParse_Continue;
	
	int iFlags = ReadFlagString(sValue);
	
	if (sKey[0] == '@')
		AddCommandOverride(sKey[1], Override_CommandGroup, iFlags);
	else
		AddCommandOverride(sKey, Override_Command, iFlags);
	
#if DEBUG
	LogToFile(g_sLogFile, "Laod overrid (%s, %s)", sKey, sValue);
#endif
	
	return SMCParse_Continue;
}

public SMCResult ReadOverrides_EndSection(SMCParser smc)
{
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