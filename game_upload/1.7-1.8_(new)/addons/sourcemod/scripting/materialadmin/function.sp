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
					  sColorC[][] = {"\x01", "\x02", "\x03", "\x04", "\x05", "\x06", "\x07", "\x08", "\x09", "\x10", "\x0B", "\x0C", "\x0E"};
	char sBufer[256];
	VFormat(sBufer, sizeof(sBufer), sMesag, 3);
	Format(sBufer, sizeof(sBufer), "%T %s.", "prifix", iClient, sBufer);
	for(int i = 0; i < 13; i++)
		ReplaceString(sBufer, sizeof(sBufer), sColorT[i], sColorC[i]);

	if (GetUserMessageType() == UM_Protobuf)
		PrintToChat(iClient, " \x01%s.", sBufer);
	else
		PrintToChat(iClient, "\x01%s.", sBufer);
}

void ShowAdminAction(int iClient, const char[] sMesag, any ...)
{
	static const char sColorT[][] = {"#1",   "#2",   "#3",   "#4",   "#5",   "#6",   "#7",   "#8",   "#9",   "#10", "#OB",   "#OC",  "#OE"},
					  sColorC[][] = {"\x01", "\x02", "\x03", "\x04", "\x05", "\x06", "\x07", "\x08", "\x09", "\x10", "\x0B", "\x0C", "\x0E"};
	char sBufer[256],
		 sName[MAX_NAME_LENGTH];

	switch(g_iShowAdminAction)
	{
		case 0: return;
		case 1: FormatEx(sName, sizeof(sName), "%T", "Admin", iClient);
		case 2: 
		{
			if (iClient)
				GetClientName(iClient, sName, sizeof(sName));
			else
				FormatEx(sName, sizeof(sName), "%T", "Server", iClient);
		}
	}

	VFormat(sBufer, sizeof(sBufer), sMesag, 3);
	Format(sBufer, sizeof(sBufer), "%T %s %s.", "prifix", iClient, sName, sBufer);
	for(int i = 0; i < 13; i++)
		ReplaceString(sBufer, sizeof(sBufer), sColorT[i], sColorC[i]);

	if (GetUserMessageType() == UM_Protobuf)
		PrintToChatAll(" \x01%s.", sBufer);
	else
		PrintToChatAll("\x01%s.", sBufer);
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

int GetImmuneAdmin(AdminId idAdmin)
{
	int iAdminImun = idAdmin.ImmunityLevel;
	int iCount = idAdmin.GroupCount;
	int iGroupImun,
		iImune = 0;

	if (iCount)
	{
		for (int i = 0; i < iCount; i++)
		{
			char sNameGroup[64];
			GroupId idGroup = idAdmin.GetGroup(i, sNameGroup, sizeof(sNameGroup));
			iGroupImun = idGroup.ImmunityLevel;
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
		AdminId idAdmin = GetUserAdmin(iAdminClient);
		int iTargetImun = GetImmuneAdmin(idAdminTarget);
		int iAdminImun = GetImmuneAdmin(idAdmin);
	#if DEBUG
		LogToFile(g_sLogFile, "CheckAdminImune: (admin %N - %d)  (target %N - %d)", iAdminClient, iAdminImun, iAdminTarget, iTargetImun);
	#endif
		switch(g_iCvar_ImmunityMode)
		{
			case 1:
			{
				if (iTargetImun < iAdminImun)
					return true;
				else
					return false;
			}
			case 2: 
			{
				if (iTargetImun >= iAdminImun)
					return false;
			}
			case 3: 
			{
				if (iTargetImun == 0 && iAdminImun == 0)
					return true;
				else if (iTargetImun >= iAdminImun)
					return false;
			}
		}
	}
	return true;
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
	else if (sArg[0] == '@')
		return -5;
	else
		return -4;
}

bool ValidTime(int iClient)
{
	if (g_iTargetType[iClient] < 3 && g_iTarget[iClient][TTIME] < 0)
	{
		if (iClient)
			PrintToChat2(iClient, "%T", "Invalid time", iClient, 0);
		else
			ReplyToCommand(iClient, "%sUsage: [time] invalid", PREFIX);
		return false;
	}
	else if (g_iTarget[iClient][TTIME] < -1)
	{
		if (iClient)
			PrintToChat2(iClient, "%T", "Invalid time", iClient, -1);
		else
			ReplyToCommand(iClient, "%sUsage: [time] invalid", PREFIX);
		return false;
	}
	else if (g_iTarget[iClient][TTIME] == 0 && iClient && !CheckAdminFlags(iClient, ADMFLAG_UNBAN))
	{
		PrintToChat2(iClient, "%T", "No Access time 0", iClient);
		return false;
	}
	return true;
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
					PrintToChat2(iClient, "%T", "No Access all", iClient);
				else
					ReplyToCommand(iClient, "%sSelect all players allowed Admins with flag ROOT.", PREFIX);
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
		#if DEBUG
			LogToFile(g_sLogFile,"Command get target: UserId %d.", iUserId);
		#endif
			int iTarget = GetClientOfUserId(iUserId);
			if (iTarget)
			{
				if(CheckAdminImune(iClient, iTarget))
					DoCreateDB(iClient, iTarget);
				else
				{
					if (iClient)
						PrintToChat2(iClient, "%T", "No admin", iClient);
					else
						ReplyToCommand(iClient, "%sThis Admin immunity.", PREFIX);
				}
			}
			else
			{
				if (iClient)
					PrintToChat2(iClient, "%T", "No matching client", iClient);
				else
					ReplyToCommand(iClient, "%sNo matching client was found.", PREFIX);
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
						COMMAND_FILTER_NO_BOTS|COMMAND_TARGET_IMMUNE, 
						sTargetName, 
						MAX_TARGET_LENGTH, 
						bTnIsMl)) <= 0)
			{
				ReplyToTargetError(iClient, iTargetCount);
				return;
			}

		
			for (int i = 0; i < iTargetCount; i++)
				DoCreateDB(iClient, iTargetList[i]);
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

int FindTargetName(char[] sName)
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
}
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
	int idx = g_aAdminsExpired.Push(idAdmin);
	g_aAdminsExpired.Set(idx, iExpire, 1);
}

int GetAdminExpire(AdminId idAdmin)
{
	int idx = g_aAdminsExpired.FindValue(idAdmin);
	if(idx != -1)
		return g_aAdminsExpired.Get(idx, 1);
	
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
	#if DEBUG
		LogToFile(g_sLogFile, "format vrema: days %d, hours %d, minutes %d, sec %d ", iDays, iHours, iMinutes, iSec);
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

#if DEBUG
	LogToFile(g_sLogFile, "un mute: %N type %d", iClient, g_iTargetMuteType[iClient]);
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
#if DEBUG
	LogToFile(g_sLogFile, "timer mute end: %N", iClient);
#endif
	UnMute(iClient);
	g_hTimerMute[iClient] = null;
}

void UnGag(int iClient)
{
	if (g_iTargetMuteType[iClient] == TYPESILENCE)
		g_iTargetMuteType[iClient] = TYPEMUTE;
	else if (g_iTargetMuteType[iClient] == TYPEGAG)
		g_iTargetMuteType[iClient] = 0;

	KillTimerGag(iClient);

#if DEBUG
	LogToFile(g_sLogFile, "un gag: %N type %d", iClient, g_iTargetMuteType[iClient]);
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
#if DEBUG
	LogToFile(g_sLogFile, "timer gag end: %N", iClient);
#endif
	UnGag(iClient);
	g_hTimerGag[iClient] = null;
}

void UnSilence(int iClient)
{
	g_iTargetMuteType[iClient] = 0;
	KillTimerGag(iClient);
	KillTimerMute(iClient);
	FunMute(iClient);
#if DEBUG
	LogToFile(g_sLogFile, "un silence: %N type %d", iClient, g_iTargetMuteType[iClient]);
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
	
#if DEBUG
	LogToFile(g_sLogFile, "add gag: %N type %d, time %d", iClient, g_iTargetMuteType[iClient], iTime);
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

#if DEBUG
	LogToFile(g_sLogFile, "add mute: %N type %d, time %d", iClient, g_iTargetMuteType[iClient], iTime);
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

#if DEBUG
	LogToFile(g_sLogFile, "add silence: %N type %d, time %d", iClient, g_iTargetMuteType[iClient], iTime);
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
#if DEBUG
	LogToFile(g_sLogFile, "TimerBekap");
#endif
	if (ConnectBd(g_dDatabase))
	{
	#if DEBUG
		LogToFile(g_sLogFile, "TimerBekap yes connect bd");
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
	#if DEBUG
		LogToFile(g_sLogFile, "CreateTeaxtDialog %N", iClient);
	#endif
		CreateDialog(iClient, kvKey, DialogType_Text);
		CreateTimer(0.1, TimerKick, GetClientUserId(iClient));
	}
	delete kvKey;
}

public Action TimerKick(Handle timer, any iUserId)
{
	int iClient = GetClientOfUserId(iUserId);
	if(iClient)
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
	
#if DEBUG
	if (g_bServerBanTyp)
		LogToFile(g_sLogFile, "banid %d %s", g_iServerBanTime, sBuffer);
	else
		LogToFile(g_sLogFile, "addip %d %s", g_iServerBanTime, sBuffer);
#endif
}
//-------------------------------------------------------------------------------------------------------------
