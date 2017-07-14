//получение айпи и порта сервера
void InsertServerInfo()
{
	int iPieces[4], 
		iLongIP;
	ConVar cvarHostIp,
		cvarPort;	
	cvarHostIp = FindConVar("hostip");
	cvarPort = FindConVar("hostport");
	
	iLongIP = cvarHostIp.IntValue;
	iPieces[0] = (iLongIP >> 24) & 0x000000FF;
	iPieces[1] = (iLongIP >> 16) & 0x000000FF;
	iPieces[2] = (iLongIP >> 8) & 0x000000FF;
	iPieces[3] = iLongIP & 0x000000FF;
	FormatEx(g_sServerIP, sizeof(g_sServerIP), "%d.%d.%d.%d", iPieces[0], iPieces[1], iPieces[2], iPieces[3]);
	cvarPort.GetString(g_sServerPort, sizeof(g_sServerPort));
}

void PrintToChat2(int iClient, const char[] sMesag, any ...)
{
	static const char sColorT[][] = {"#1",   "#2",   "#3",   "#4",   "#5",   "#6",   "#7",   "#8",   "#9",   "#10", "#OB",   "#OC",  "#OE"},
					  sColorC[][] = {"\x01", "\x02", "\x03", "\x04", "\x05", "\x06", "\x07", "\x08", "\x09", "\x10", "\x0B", "\x0C", "\x0E"},
					  sNameD[][] = {"name1", "name2"};
	char sBufer[256];
	VFormat(sBufer, sizeof(sBufer), sMesag, 3);
	
	// del name ???
	if (g_sNameReples[0][0])
		ReplaceString(sBufer, sizeof(sBufer), g_sNameReples[0], sNameD[0]);
	if (g_sNameReples[1][0])
		ReplaceString(sBufer, sizeof(sBufer), g_sNameReples[1], sNameD[1]);
	
	Format(sBufer, sizeof(sBufer), "%T %s", "prifix", iClient, sBufer);
	for(int i = 0; i < 13; i++)
		ReplaceString(sBufer, sizeof(sBufer), sColorT[i], sColorC[i]);
	
	// add name ????
	ReplaceString(sBufer, sizeof(sBufer), sNameD[0], g_sNameReples[0]);
	ReplaceString(sBufer, sizeof(sBufer), sNameD[1], g_sNameReples[1]);

	if (GetUserMessageType() == UM_Protobuf)
		PrintToChat(iClient, " \x01%s.", sBufer);
	else
		PrintToChat(iClient, "\x01%s.", sBufer);
}

void ShowAdminAction(int iClient, const char[] sMesag, any ...)
{
	char sBufer[256],
		 sName[MAX_NAME_LENGTH];
		 
	if (!g_iShowAdminAction)
		return;
 
	for (int i = 1; i <= MaxClients; i++)
	{
		if (IsClientInGame(i))
		{
			switch(g_iShowAdminAction)
			{
				case 1: FormatEx(sName, sizeof(sName), "%T", "Admin", i);
				case 2: 
				{
					if (iClient)
						GetClientName(iClient, sName, sizeof(sName));
					else
						FormatEx(sName, sizeof(sName), "%T", "Server", i);
				}
			}
			strcopy(g_sNameReples[1], sizeof(g_sNameReples[]), sName);
			SetGlobalTransTarget(i);
			VFormat(sBufer, sizeof(sBufer), sMesag, 3);
			PrintToChat2(i, "%s %s", sName, sBufer);
		}
	}
}
//-----------------------------------------------------------------------------
bool CheckAdminFlags(int iClient, int iFlag)
{
	int iUserFlags = GetUserFlagBits(iClient);
	if (iUserFlags & ADMFLAG_ROOT || iUserFlags & iFlag)
		return true;
	else
		return false;
}

bool IsImune(int iAdminImun, int iTargetImun)
{
	switch(g_iCvar_ImmunityMode)
	{
		case 1:
		{
			if (iAdminImun < iTargetImun)
				return false;
		}
		case 2: 
		{
			if (iAdminImun <= iTargetImun)
				return false;
		}
		case 3: 
		{
			if (!iAdminImun && !iTargetImun)
				return true;
			else if (iAdminImun <= iTargetImun)
				return false;
		}
	}
	return true;
}

int GetImmuneAdmin(int iClient)
{
	if (!iClient || !IsClientInGame(iClient))
		return g_iServerImmune;

	AdminId idAdmin = GetUserAdmin(iClient);
	if (idAdmin == INVALID_ADMIN_ID)
		return 0;
	
#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
	int iAdminImun = GetAdminImmunityLevel(idAdmin);
	int iCount = GetAdminGroupCount(idAdmin);
#else
	int iAdminImun = idAdmin.ImmunityLevel;
	int iCount = idAdmin.GroupCount;
#endif
	int iGroupImun,
		iImune = 0;

	if (iCount)
	{
		for (int i = 0; i < iCount; i++)
		{
			char sNameGroup[64];
		#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
			GroupId idGroup = GetAdminGroup(idAdmin, i, sNameGroup, sizeof(sNameGroup));
			iGroupImun = GetAdmGroupImmunityLevel(idGroup);
		#else
			GroupId idGroup = idAdmin.GetGroup(i, sNameGroup, sizeof(sNameGroup));
			iGroupImun = idGroup.ImmunityLevel;
		#endif
			if (iImune < iAdminImun && iImune < iGroupImun)
			{
				if (iAdminImun >= iGroupImun)
					iImune = iAdminImun;
				else
					iImune = iGroupImun;
			}
			else if (iImune < iGroupImun)
				iImune = iGroupImun;
		}
	}
	else
		return iAdminImun;

	return iImune;
}

bool CheckAdminImune(int iAdminClient, int iAdminTarget)
{
	if (!iAdminClient && iAdminTarget)
		return true;
	
	if (iAdminClient == iAdminTarget)
	{
		if(g_bActionOnTheMy)
			return true;
		else
			return false;
	}

	AdminId idAdminTarget = GetUserAdmin(iAdminTarget);
	if (idAdminTarget != INVALID_ADMIN_ID && g_iCvar_ImmunityMode != 0)
	{
		int iTargetImun = GetImmuneAdmin(iAdminTarget);
		int iAdminImun = GetImmuneAdmin(iAdminClient);
	#if MADEBUG
		LogToFile(g_sLogAction, "CheckAdminImune: (admin %N - %d)  (target %N - %d)", iAdminClient, iAdminImun, iAdminTarget, iTargetImun);
	#endif
	
		if (!IsImune(iAdminImun, iTargetImun))
			return false;
	}
	return true;
}

bool CheckUnMuteImun(int iAdmin, int iTarget)
{
	char sAdmin_SteamID[MAX_STEAMID_LENGTH];
	if (iAdmin && IsClientInGame(iAdmin))
		GetClientAuthId(iAdmin, TYPE_STEAM, sAdmin_SteamID, sizeof(sAdmin_SteamID));
	else
		strcopy(sAdmin_SteamID, sizeof(sAdmin_SteamID), "STEAM_ID_SERVER");
	
	if (StrEqual(sAdmin_SteamID, g_sTargetMuteSteamAdmin[iTarget]))
		return true;

	int iImun = GetImmuneAdmin(iAdmin);
#if MADEBUG
	LogToFile(g_sLogAction, "CheckUnMuteImun: (admin %N - %d)  (target %N - %d)", iAdmin, iImun, iTarget, g_iTargenMuteImun[iTarget]);
#endif
	if (IsImune(iImun, g_iTargenMuteImun[iTarget]))
		return true;
	
	return false;
}

int GetAdminMaxTime(int iClient)
{
	char sAdminID[12];
	int iMaxTime;
	
	AdminId idAdmin = GetUserAdmin(iClient);
	FormatEx(sAdminID, sizeof(sAdminID), "%d", idAdmin);
	
	if (g_iTargetType[iClient] <= TYPE_ADDBAN)
	{
		if (g_tAdminBanTimeMax.GetValue(sAdminID, iMaxTime))
			return iMaxTime;
	}
	else
	{
		if (g_tAdminMuteTimeMax.GetValue(sAdminID, iMaxTime))
			return iMaxTime;
	}
	return -1;
}
//----------------------------------------------------------------------------------------------
// управлене админами
int GetAdminWebFlag(int iClient)
{
	if (!iClient)
		return 0;

	char sAdminID[12];
	int iFlag;
	AdminId idAdmin = GetUserAdmin(iClient);
	FormatEx(sAdminID, sizeof(sAdminID), "%d", idAdmin);
	
	if (g_tAdminWebFlag.GetValue(sAdminID, iFlag))
	{
		if (iFlag)
		{
			if (iFlag & (1<<24))
				return 1; // полные права
			else if (iFlag & (1<<1) && iFlag & (1<<3))
				return 2; // есть на добавление и на удаление
			else if (iFlag & (1<<1))
				return 3; // добавление
			else if (iFlag & (1<<3))
				return 4; // удаление
		}
		else
			return 0; // нет прав
	}
	return 0; // нет прав
}

void ResetFlagAddAdmin(int iClient)
{
	for (int i = 0; i < 21; i++)
	{
		if (i < 4)
			g_bAdminAdd[iClient][i] = false;
		g_bAddAdminFlag[iClient][i] = false;
	}
}
//-------------------------------------------------------------------------------
void SBGetCmdArg1(int iClient, char[] sBuffer, char[] sArg, int iMaxLin)
{
	int iLen;
	
	if ((iLen = BreakString(sBuffer, sArg, iMaxLin)) == -1)
	{
		iLen = 0;
		sBuffer[0] = '\0';
	}
	
	strcopy(g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]), sBuffer[iLen]);
}

bool SBGetCmdArg2(int iClient, char[] sBuffer, char[] sArg, int iMaxLin)
{
	char sTime[56];	
	int iLen,
		iTotelLen;
	
	if ((iLen = BreakString(sBuffer, sArg, iMaxLin)) == -1)
		return false;

	iTotelLen += iLen;
	if ((iLen = BreakString(sBuffer[iTotelLen], sTime, sizeof(sTime))) != -1)
		iTotelLen += iLen;
	else
	{
		iTotelLen = 0;
		sBuffer[0] = '\0';
	}

	g_iTarget[iClient][TTIME] = StringToInt(sTime);
	strcopy(g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]), sBuffer[iTotelLen]);
	return true;
}

int GetTypeClient(char[] sArg)
{
	if (StrEqual(sArg, "#all"))
		return -1;
	else if (StrEqual(sArg, "#ct") || StrEqual(sArg, "#blue"))
		return -2;
	else if (StrEqual(sArg, "#t") || StrEqual(sArg, "#red"))
		return -3;
	else if (sArg[0] == '#')
		return -4;
	else
		return -5;
}

bool ValidTime(int iClient)
{
	if (iClient)
	{
		int iMaxTime = GetAdminMaxTime(iClient);
	#if MADEBUG
		LogToFile(g_sLogAction,"valid time: time %d, max %d.", g_iTarget[iClient][TTIME], iMaxTime);
	#endif
		
		/*
			-1 - всё разрешено
			0  - можно всё но не на всегда
			1... - больше этого времени не разрешено, навсегда тоже не разрешено
		*/
		if (iMaxTime > -1)
		{
			if (!g_iTarget[iClient][TTIME])
			{
				ReplyToCommand(iClient, "%s%T", MAPREFIX, "No Access time 0", iClient);
				return false;
			}
			else if (g_iTarget[iClient][TTIME] > iMaxTime)
			{
				ReplyToCommand(iClient, "%s%T", MAPREFIX, "No Access max time", iClient, iMaxTime);
				return false;
			}
		}
	}

	if (g_iTargetType[iClient] <= TYPE_ADDBAN && g_iTarget[iClient][TTIME] < 0)
	{
		if (iClient)
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "Invalid time", iClient, 0);
		else
			ReplyToCommand(iClient, "%sUsage: [time] invalid", MAPREFIX);
		return false;
	}
	else if (g_iTarget[iClient][TTIME] < -1)
	{
		if (iClient)
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "Invalid time", iClient, -1);
		else
			ReplyToCommand(iClient, "%sUsage: [time] invalid", MAPREFIX);
		return false;
	}
	else if (!g_iTarget[iClient][TTIME] && iClient && !CheckAdminFlags(iClient, ADMFLAG_UNBAN))
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "No Access unban time 0", iClient);
		return false;
	}
	return true;
}

bool ValidSteam(char[] sSteamID)
{
	if (sSteamID[0] == '[')
	{
		if (strncmp(sSteamID, "[U:", 3) != 0)
			return false;
	}
	else
	{
		if (strlen(sSteamID) < 10)
			return false;

		if (strncmp(sSteamID, "STEAM_", 6) != 0)
			return false;
	}

	return true;
}

// взято с этого плагина https://forums.alliedmods.net/showpost.php?p=2353704&postcount=10
void ConvecterSteam3ToSteam2(char[] sSteamID, int iMaxLin)
{
	char sParts[3][10];
	
	ReplaceString(sSteamID, iMaxLin, "[", "");
	ReplaceString(sSteamID, iMaxLin, "]", "");
	ExplodeString(sSteamID, ":", sParts, sizeof(sParts), sizeof(sParts[]));

	int iUniverse = StringToInt(sParts[1]);
	int iSteamid32 = StringToInt(sParts[2]);

	if (iUniverse == 1)
		Format(sSteamID, iMaxLin, "STEAM_%d:%d:%d", 0, iSteamid32 & (1 << 0), iSteamid32 >>> 1);
	else
		Format(sSteamID, iMaxLin, "STEAM_%d:%d:%d", iUniverse, iSteamid32 & (1 << 0), iSteamid32 >>> 1);
}

void GetClientToBd(int iClient, int iTyp, const char[] sArg = "")
{
	switch(iTyp)
	{
		case 0:
		{
			int iTarget;
			for (int i = 0; i < g_aUserId[iClient].Length; i++)
			{
				iTarget = GetClientOfUserId(g_aUserId[iClient].Get(i));
				if(iTarget)
					DoCreateDB(iClient, iTarget);
				else
					PrintToChat2(iClient, "%T", "No Client Game", iClient);
			}
		}
		case -1:
		{
			if (CheckAdminFlags(iClient, ADMFLAG_ROOT))
			{
				for (int i = 1; i <= MaxClients; i++)
				{
					if(IsClientInGame(i) && !IsFakeClient(i) && CheckAdminImune(iClient, i))
						DoCreateDB(iClient, i);
				}
			}
			else
			{
				if(iClient)
					ReplyToCommand(iClient, "%s%T", MAPREFIX, "No Access all", iClient);
				else
					ReplyToCommand(iClient, "%sSelect all players allowed Admins with flag ROOT.", MAPREFIX);
			}
		}
		case -2:
		{
			for (int i = 1; i <= MaxClients; i++)
			{
				if (IsClientInGame(i) && GetClientTeam(i) == CS_TEAM_CT && !IsFakeClient(i) && CheckAdminImune(iClient, i))
					DoCreateDB(iClient, i);
			}
		}
		case -3:
		{
			for (int i = 1; i <= MaxClients; i++)
			{
				if (IsClientInGame(i) && GetClientTeam(i) == CS_TEAM_T && !IsFakeClient(i) && CheckAdminImune(iClient, i))
					DoCreateDB(iClient, i);
			}
		}
		case -4:
		{
			
			int iUserId = StringToInt(sArg[1]);
		#if MADEBUG
			LogToFile(g_sLogAction,"Command get target: UserId %d.", iUserId);
		#endif
			int iTarget = GetClientOfUserId(iUserId);
			if (iTarget)
			{
				if(CheckAdminImune(iClient, iTarget))
					DoCreateDB(iClient, iTarget);
				else
				{
					if (iClient)
						ReplyToCommand(iClient, "%s%T", MAPREFIX, "No admin", iClient);
					else
						ReplyToCommand(iClient, "%sThis Admin immunity.", MAPREFIX);
				}
			}
			else
			{
				if (iClient)
					ReplyToCommand(iClient, "%s%T", MAPREFIX, "No matching client", iClient);
				else
					ReplyToCommand(iClient, "%sNo matching client was found.", MAPREFIX);
			}
		}
		case -5:
		{
			char sTargetName[MAX_TARGET_LENGTH];
			int iTargetList[MAXPLAYERS], iTargetCount;
			bool bTnIsMl;
			if ((iTargetCount = ProcessTargetString(
						sArg, 
						iClient, 
						iTargetList, 
						MAXPLAYERS, 
						COMMAND_FILTER_NO_BOTS, 
						sTargetName, 
						MAX_TARGET_LENGTH, 
						bTnIsMl)) <= 0)
			{
				ReplyToTargetError(iClient, iTargetCount);
				return;
			}

			if (bTnIsMl)
			{
				for (int i = 0; i < iTargetCount; i++)
				{
					if (CheckAdminImune(iClient, iTargetList[i]))
						DoCreateDB(iClient, iTargetList[i]);
					else
					{
						if (iClient)
							ReplyToCommand(iClient, "%s%T", MAPREFIX, "No admin", iClient);
						else
							ReplyToCommand(iClient, "%sThis Admin immunity.", MAPREFIX);
					}
				}
			}
			else
			{
				if (CheckAdminImune(iClient, iTargetList[0]))
					DoCreateDB(iClient, iTargetList[0]);
				else
				{
					if (iClient)
						ReplyToCommand(iClient, "%s%T", MAPREFIX, "No admin", iClient);
					else
						ReplyToCommand(iClient, "%sThis Admin immunity.", MAPREFIX);
				}
			}
		}
	}
}

int FindTargetSteam(char[] sSteamID)
{
	char sSteamIDs[MAX_STEAMID_LENGTH];
	for (int i = 1; i <= MaxClients; i++)
	{
		if (IsClientInGame(i))
		{
			GetClientAuthId(i, TYPE_STEAM, sSteamIDs, sizeof(sSteamIDs));
			if(StrEqual(sSteamID[8], sSteamIDs[8]))
				return i;
		}
	}
	return 0;
}

int FindTargetIp(char[] sIp)
{
	char sIps[MAX_IP_LENGTH];
	for (int i = 1; i <= MaxClients; i++)
	{
		if (IsClientInGame(i))
		{
			GetClientIP(i, sIps, sizeof(sIps));
			if(StrEqual(sIp, sIps))
				return i;
		}
	}
	return 0;
}

/*int FindTargetName(char[] sName)
{
	char sNames[MAX_NAME_LENGTH];
	for (int i = 1; i <= MaxClients; i++)
	{
		if (IsClientInGame(i))
		{
			GetClientName(i, sNames, sizeof(sNames));
			if(StrEqual(sName, sNames))
				return i;
		}
	}
	return 0;
}*/
//---------------------------------------------------------------------------------------------
public void ConVarChange_Alltalk(ConVar convar, const char[] oldValue, const char[] newValue)
{
	g_bCvar_Alltalk = convar.BoolValue;

	for (int i = 1; i <= MaxClients; i++)
	{
		if (IsClientInGame(i))
		{
			if (g_iTargetMuteType[i] == TYPEMUTE || g_iTargetMuteType[i] == TYPESILENCE)
				SetClientListeningFlags(i, VOICE_MUTED);
			else if (g_bCvar_Alltalk)
				SetClientListeningFlags(i, VOICE_NORMAL);
			else if (g_iGameTyp != GAMETYP_CSGO && !IsPlayerAlive(i))
			{
				if (g_iCvar_Deadtalk == 0)
					SetClientListeningFlags(i, VOICE_NORMAL);
				else if (g_iCvar_Deadtalk == 1)
					SetClientListeningFlags(i, VOICE_LISTENALL);
				else if (g_iCvar_Deadtalk == 2)
					SetClientListeningFlags(i, VOICE_TEAM);
			}
		}
	}
}

public void ConVarChange(ConVar convar, const char[] oldValue, const char[] newValue)
{
	char sName[256];
	convar.GetName(sName, sizeof(sName));
	if (StrEqual(sName, "sm_immunity_mode"))
		g_iCvar_ImmunityMode = convar.IntValue;
	else
	{
		for (int i = 1; i <= MaxClients; i++)
		{
			if (IsClientInGame(i))
			{
				if (g_iTargetMuteType[i] == TYPEMUTE || g_iTargetMuteType[i] == TYPESILENCE)
					SetClientListeningFlags(i, VOICE_MUTED);
			}
		}
	}
}

public void ConVarChange_Deadtalk(ConVar convar, const char[] oldValue, const char[] newValue)
{
	if (g_iGameTyp == GAMETYP_CSGO)
	{
		for (int i = 1; i <= MaxClients; i++)
		{
			if (IsClientInGame(i))
			{
				if (g_iTargetMuteType[i] == TYPEMUTE || g_iTargetMuteType[i] == TYPESILENCE)
					SetClientListeningFlags(i, VOICE_MUTED);
			}
		}
	}
	else
	{
		g_iCvar_Deadtalk = convar.IntValue;
		if (g_iCvar_Deadtalk)
		{
			for (int i = 1; i <= MaxClients; i++)
			{
				if (IsClientInGame(i))
				{
					if (g_iTargetMuteType[i] == TYPEMUTE || g_iTargetMuteType[i] == TYPESILENCE)
						SetClientListeningFlags(i, VOICE_MUTED);
					else if (g_bCvar_Alltalk)
						SetClientListeningFlags(i, VOICE_NORMAL);
					else if (!IsPlayerAlive(i))
					{
						if (g_iCvar_Deadtalk == 1)
							SetClientListeningFlags(i, VOICE_LISTENALL);
						else if (g_iCvar_Deadtalk == 2)
							SetClientListeningFlags(i, VOICE_TEAM);
					}
				}
			}
			HookEvent("player_spawn", Event_PlayerSpawn, EventHookMode_Post);
			HookEvent("player_death", Event_PlayerDeath, EventHookMode_Post);
			g_bHooked = true;
		}
		else if (g_bHooked)
		{
			for (int i = 1; i <= MaxClients; i++)
			{
				if (IsClientInGame(i))
				{
					if (g_iTargetMuteType[i] == TYPEMUTE || g_iTargetMuteType[i] == TYPESILENCE)
						SetClientListeningFlags(i, VOICE_MUTED);
					else
						SetClientListeningFlags(i, VOICE_NORMAL);
				}
			}
			UnhookEvent("player_spawn", Event_PlayerSpawn);
			UnhookEvent("player_death", Event_PlayerDeath);		
			g_bHooked = false;
		}
	}
}

public void Event_PlayerSpawn(Event eEvent, const char[] sName, bool bDontBroadcast)
{
	int iClient = GetClientOfUserId(eEvent.GetInt("userid"));
	
	if (iClient)
	{
		if (g_iTargetMuteType[iClient] == TYPEMUTE || g_iTargetMuteType[iClient] == TYPESILENCE)
			SetClientListeningFlags(iClient, VOICE_MUTED);
		else
			SetClientListeningFlags(iClient, VOICE_NORMAL);
	}
}

public void Event_PlayerDeath(Event eEvent, const char[] sName, bool bDontBroadcast)
{
	int iClient = GetClientOfUserId(eEvent.GetInt("userid"));
	
	if (!iClient)
		return;	
	
	if (g_iTargetMuteType[iClient] == TYPEMUTE || g_iTargetMuteType[iClient] == TYPESILENCE)
	{
		SetClientListeningFlags(iClient, VOICE_MUTED);
		return;
	}
	
	if (g_bCvar_Alltalk)
	{
		SetClientListeningFlags(iClient, VOICE_NORMAL);
		return;
	}

	if (g_iCvar_Deadtalk == 1)
		SetClientListeningFlags(iClient, VOICE_LISTENALL);
	else if (g_iCvar_Deadtalk == 2)
		SetClientListeningFlags(iClient, VOICE_TEAM);
}
//--------------------------------------------------------------------------------------------------------
void CheckClientAdmin(int iClient, char[] sSteamID)
{
	g_bNewConnect[iClient] = true;

	AdminId idAdmin = GetUserAdmin(iClient);
	if(idAdmin != INVALID_ADMIN_ID)
	{
		int iExpire = GetAdminExpire(idAdmin);
		if (iExpire)
		{
			if(iExpire > GetTime())
			{
				DataPack dPack = new DataPack();
				dPack.WriteCell(GetClientUserId(iClient));
				dPack.WriteCell(iExpire);
				CreateTimer(15.0, TimerAdminExpire, dPack);
			}
			else
			{
			#if MADEBUG
				LogToFile(g_sLogAdmin, "RemoveAdmin expire: admin id %d, steam %s", idAdmin, sSteamID);
			#endif
				RemoveAdmin(idAdmin);
			}
		}
	}
	else
		DelOflineInfo(sSteamID);	
}
//---------------------------------------------------------------------------------------------------------
//временная админка
public Action TimerAdminExpire(Handle timer, any data)
{
	DataPack dPack = view_as<DataPack>(data);
	dPack.Reset();
	int iClient = GetClientOfUserId(dPack.ReadCell());
	int iExpire = dPack.ReadCell();
	delete dPack;
	
	if(!iClient)
		return Plugin_Stop;

	char sLength[128];
	int iLength = iExpire - GetTime();
	FormatVrema(iClient, iLength, sLength, sizeof(sLength));
	if(IsClientInGame(iClient))
		PrintToChat2(iClient, "%T", "Admin Expire", iClient, sLength);
	
	return Plugin_Stop;
}

void AddAdminExpire(AdminId idAdmin, int iExpire)
{
	char sAdminID[12];
	FormatEx(sAdminID, sizeof(sAdminID), "%d", idAdmin);
#if MADEBUG
	LogToFile(g_sLogAdmin, "Add Admin Expire: admin id %d, expire %d", idAdmin, iExpire);
#endif
	g_tAdminsExpired.SetValue(sAdminID, iExpire, false);
}

int GetAdminExpire(AdminId idAdmin)
{
	char sAdminID[12];
	int iExpire;
	FormatEx(sAdminID, sizeof(sAdminID), "%d", idAdmin);
	if (g_tAdminsExpired.GetValue(sAdminID, iExpire))
	{
	#if MADEBUG
		LogToFile(g_sLogAdmin, "Get Admin Expire: admin id %d, expire %d", idAdmin, iExpire);
	#endif
		return iExpire;
	}

#if MADEBUG
	LogToFile(g_sLogAdmin, "Get Admin Expire: admin id %d, expire 0", idAdmin);
#endif
	return 0;
}
//--------------------------------------------------------------------------------------------------
void FormatVrema(int iClient, int iLength, char[] sLength, int iLens)
{
	if (iLength == -1)
		FormatEx(sLength, iLens, "%T", "temporarily", iClient);
	else if (iLength == 0)
		FormatEx(sLength, iLens, "%T", "Permanent", iClient);
	else
	{
		int iDays = iLength / (60 * 60 * 24);
		int iHours = (iLength - (iDays * (60 * 60 * 24))) / (60 * 60);
		int iMinutes = (iLength - (iDays * (60 * 60 * 24)) - (iHours * (60 * 60))) / 60;
		int iSec = (iLength - (iDays * (60 * 60 * 24)) - (iHours * (60 * 60)) - (iMinutes * 60));
		int iLen = 0;
	#if MADEBUG
		LogToFile(g_sLogAction, "format vrema %d: days %d, hours %d, minutes %d, sec %d", iLength, iDays, iHours, iMinutes, iSec);
	#endif
		if(iDays) iLen += Format(sLength[iLen], iLens - iLen, "%d %T", iDays, "Days", iClient);
		if(iHours) iLen += Format(sLength[iLen], iLens - iLen, "%s%d %T", iDays ? " " : "", iHours, "Hours", iClient);
		if(iMinutes) iLen += Format(sLength[iLen], iLens - iLen, "%s%d %T", (iDays || iHours) ? " " : "", iMinutes, "Minutes", iClient);
		if(iSec) iLen += Format(sLength[iLen], iLens - iLen, "%s%d %T", (iDays || iHours || iMinutes) ? " " : "", iSec, "Sec", iClient);
	}
}
//------------------------------------------------------------------------------------------------------
// работа с мутами
void UnMute(int iClient)
{
	if (g_iTargetMuteType[iClient] == TYPESILENCE)
		g_iTargetMuteType[iClient] = TYPEGAG;
	else if (g_iTargetMuteType[iClient] == TYPEMUTE)
		g_iTargetMuteType[iClient] = 0;

	FunMute(iClient);
	KillTimerMute(iClient);

#if MADEBUG
	LogToFile(g_sLogAction, "un mute: %N type %d", iClient, g_iTargetMuteType[iClient]);
#endif
}

void KillTimerMute(int iClient)
{
	if(g_hTimerMute[iClient])
	{
		KillTimer(g_hTimerMute[iClient]);
		g_hTimerMute[iClient] = null;
	}
}

public Action TimerMute(Handle timer, any iClient)
{
#if MADEBUG
	LogToFile(g_sLogAction, "timer mute end: %N", iClient);
#endif
	g_hTimerMute[iClient] = null;
	if (IsClientInGame(iClient))
		UnMute(iClient);
}

void UnGag(int iClient)
{
	if (g_iTargetMuteType[iClient] == TYPESILENCE)
		g_iTargetMuteType[iClient] = TYPEMUTE;
	else if (g_iTargetMuteType[iClient] == TYPEGAG)
		g_iTargetMuteType[iClient] = 0;

	KillTimerGag(iClient);

#if MADEBUG
	LogToFile(g_sLogAction, "un gag: %N type %d", iClient, g_iTargetMuteType[iClient]);
#endif
}

void KillTimerGag(int iClient)
{
	if(g_hTimerGag[iClient])
	{
		KillTimer(g_hTimerGag[iClient]);
		g_hTimerGag[iClient] = null;
	}
}

public Action TimerGag(Handle timer, any iClient)
{
#if MADEBUG
	LogToFile(g_sLogAction, "timer gag end: %N", iClient);
#endif
	g_hTimerGag[iClient] = null;
	if (IsClientInGame(iClient))
		UnGag(iClient);
}

void UnSilence(int iClient)
{
	g_iTargetMuteType[iClient] = 0;
	KillTimerGag(iClient);
	KillTimerMute(iClient);
	FunMute(iClient);
#if MADEBUG
	LogToFile(g_sLogAction, "un silence: %N type %d", iClient, g_iTargetMuteType[iClient]);
#endif
}

void AddGag(int iClient, int iTime)
{
	if(g_iTargetMuteType[iClient] == 0)
		g_iTargetMuteType[iClient] = TYPEGAG;
	else if (g_iTargetMuteType[iClient] == TYPEMUTE)
	{
		AddSilence(iClient, iTime);
		return;
	}

	KillTimerGag(iClient);
	if (iTime > 0 && iTime < 86400)
	{
		if(!g_hTimerGag[iClient])
			g_hTimerGag[iClient] = CreateTimer(float(iTime), TimerGag, iClient);
	}
	
#if MADEBUG
	LogToFile(g_sLogAction, "add gag: %N type %d, time %d", iClient, g_iTargetMuteType[iClient], iTime);
#endif
}

void AddMute(int iClient, int iTime)
{
	if(g_iTargetMuteType[iClient] == 0)
		g_iTargetMuteType[iClient] = TYPEMUTE;
	else if (g_iTargetMuteType[iClient] == TYPEGAG)
	{
		AddSilence(iClient, iTime);
		return;
	}

	KillTimerMute(iClient);
	FunMute(iClient);
	if (iTime > 0 && iTime < 86400)
	{
		if(!g_hTimerMute[iClient])
			g_hTimerMute[iClient] = CreateTimer(float(iTime), TimerMute, iClient);
	}

#if MADEBUG
	LogToFile(g_sLogAction, "add mute: %N type %d, time %d", iClient, g_iTargetMuteType[iClient], iTime);
#endif
}

void FunMute(int iClient)
{
	if (g_iTargetMuteType[iClient] == TYPEMUTE || g_iTargetMuteType[iClient] == TYPESILENCE)
		SetClientListeningFlags(iClient, VOICE_MUTED);
	else if (!IsPlayerAlive(iClient) && g_iGameTyp != GAMETYP_CSGO && g_iCvar_Deadtalk)
	{
		if (g_iCvar_Deadtalk == 1)
			SetClientListeningFlags(iClient, VOICE_LISTENALL);
		else if (g_iCvar_Deadtalk == 2)
			SetClientListeningFlags(iClient, VOICE_TEAM);
	}
	else
		SetClientListeningFlags(iClient, VOICE_NORMAL);
}

void AddSilence(int iClient, int iTime)
{
	g_iTargetMuteType[iClient] = TYPESILENCE;
	FunMute(iClient);
	KillTimerMute(iClient);
	KillTimerGag(iClient);
	if (iTime > 0 && iTime < 86400)
	{
		if(!g_hTimerMute[iClient])
			g_hTimerMute[iClient] = CreateTimer(float(iTime), TimerMute, iClient);
		if(!g_hTimerGag[iClient])
			g_hTimerGag[iClient] = CreateTimer(float(iTime), TimerGag, iClient);
	}

#if MADEBUG
	LogToFile(g_sLogAction, "add silence: %N type %d, time %d", iClient, g_iTargetMuteType[iClient], iTime);
#endif
}
//----------------------------------------------------------------------------------------------
void KillTimerBekap()
{
	if (g_hTimerBekap)
	{
		KillTimer(g_hTimerBekap);
		g_hTimerBekap = null;
		SentBekapInBd();
	}
}

public Action TimerBekap(Handle timer, any data)
{
#if MADEBUG
	LogToFile(g_sLogDateBase, "TimerBekap");
#endif
	if (ConnectBd(g_dDatabase))
	{
	#if MADEBUG
		LogToFile(g_sLogDateBase, "TimerBekap yes connect bd");
	#endif
		g_hTimerBekap = null;
		SentBekapInBd();
		return Plugin_Stop;
	}
	return Plugin_Continue;
}
//--------------------------------------------------------------------------------------------------
void CreateSayBanned(char[] sAdminName, int iClient, int iCreated, int iTime, char[] sLength, char[] sReason)
{
	char sCreated[128];
	FormatTime(sCreated, sizeof(sCreated), FORMAT_TIME, iCreated);

	if(g_bBanSayPanel)
	{
		char sEnds[128];
		if(!iTime)
			FormatEx(sEnds, sizeof(sEnds), "%T", "No ends", iClient);
		else
			FormatTime(sEnds, sizeof(sEnds), FORMAT_TIME, iCreated + iTime);
		CreateTeaxtDialog(iClient, "%T", "Banned Admin panel", iClient, sAdminName, sReason, sCreated, sEnds, sLength, g_sWebsite);
	}
	else
	{
		if(IsClientInGame(iClient))
			KickClient(iClient, "%T", "Banned Admin", iClient, sAdminName, sReason, sCreated, sLength, g_sWebsite);
	}
}

void CreateTeaxtDialog(int iClient, const char[] sMesag, any ...)
{
	char sTitle[125],
		sText[1025];
	VFormat(sText, sizeof(sText), sMesag, 3);
	KeyValues kvKey = new KeyValues("text");
	kvKey.SetNum("time", 200);
	FormatEx(sTitle, sizeof(sTitle), "%T", "Title Banned", iClient);
	kvKey.SetString("title", sTitle);
	kvKey.SetNum("level", 0);
	kvKey.SetString("msg", sText);
	if(IsClientInGame(iClient))
	{
	#if MADEBUG
		LogToFile(g_sLogAction, "CreateTeaxtDialog %N", iClient);
	#endif
		CreateDialog(iClient, kvKey, DialogType_Text);
		CreateTimer(0.1, TimerKick, GetClientUserId(iClient));
	}
	delete kvKey;
}

public Action TimerKick(Handle timer, any iUserId)
{
	int iClient = GetClientOfUserId(iUserId);
	if (iClient && IsClientInGame(iClient))
		KickClient(iClient, "%T", "Banneds", iClient);
}

public Action TimerBan(Handle timer, any data)
{
	DataPack dPack = view_as<DataPack>(data);
	dPack.Reset();
	char sBuffer[MAX_IP_LENGTH];
	dPack.ReadString(sBuffer, sizeof(sBuffer));
	delete dPack;
	
	if (g_bServerBanTyp)
		ServerCommand("banid %d %s", g_iServerBanTime, sBuffer);
	else
		ServerCommand("addip %d %s", g_iServerBanTime, sBuffer);
	
#if MADEBUG
	if (g_bServerBanTyp)
		LogToFile(g_sLogAction, "banid %d %s", g_iServerBanTime, sBuffer);
	else
		LogToFile(g_sLogAction, "addip %d %s", g_iServerBanTime, sBuffer);
#endif
}
//-------------------------------------------------------------------------------------------------------------
void LogOn()
{
	char sTime[64],
		sBuffer[64];
	FormatTime(sTime, sizeof(sTime), "%Y%m%d");
	
	FormatEx(sBuffer, sizeof(sBuffer), "logs/materialadmin/LogAdmin_%s.log", sTime);
	BuildPath(Path_SM, g_sLogAdmin, sizeof(g_sLogAdmin), sBuffer);
	
	FormatEx(sBuffer, sizeof(sBuffer), "logs/materialadmin/LogConfig_%s.log", sTime);
	BuildPath(Path_SM, g_sLogConfig, sizeof(g_sLogConfig), sBuffer);
	
	FormatEx(sBuffer, sizeof(sBuffer), "logs/materialadmin/LogDateBase_%s.log", sTime);
	BuildPath(Path_SM, g_sLogDateBase, sizeof(g_sLogDateBase), sBuffer);
	
	FormatEx(sBuffer, sizeof(sBuffer), "logs/materialadmin/LogNative_%s.log", sTime);
	BuildPath(Path_SM, g_sLogNative, sizeof(g_sLogNative), sBuffer);
	
	FormatEx(sBuffer, sizeof(sBuffer), "logs/materialadmin/LogAction_%s.log", sTime);
	BuildPath(Path_SM, g_sLogAction, sizeof(g_sLogAction), sBuffer);
	
#if MADEBUG
	LogToFile(g_sLogAdmin, "plugin version %s", MAVERSION);
	LogToFile(g_sLogConfig, "plugin version %s", MAVERSION);
	LogToFile(g_sLogDateBase, "plugin version %s", MAVERSION);
	LogToFile(g_sLogAction, "plugin version %s", MAVERSION);
#endif
}