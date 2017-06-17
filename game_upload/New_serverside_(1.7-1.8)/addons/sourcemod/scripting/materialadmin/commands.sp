void RegComands()
{
	RegAdminCmd("ma_off_clear", 	CommandClear, 		ADMFLAG_ROOT, 	"Clear history");
	RegAdminCmd("ma_reload", 		CommandReload, 		ADMFLAG_RCON, 	"Reload config and ban reason menu options"); // перезагрузка меню и конфгов
	RegAdminCmd("ma_bd_connect",	CommandConnectBd, 	ADMFLAG_RCON, 	"Reload connect bd");
	RegAdminCmd("sm_ban", 			CommandBan, 		ADMFLAG_BAN, 	"Ban client steam");
	RegAdminCmd("sm_banip", 		CommandBanIp, 		ADMFLAG_BAN, 	"Ban client ip");
	RegAdminCmd("sm_addban", 		CommandAddBan, 		ADMFLAG_RCON, 	"Add ban client");
	RegAdminCmd("sm_unban", 		CommandUnBan,		ADMFLAG_UNBAN, 	"Un ban client");
	RegAdminCmd("sm_gag", 			CommandGag, 		ADMFLAG_CHAT, 	"Add gag client");
	RegAdminCmd("sm_mute", 			CommandMute, 		ADMFLAG_CHAT, 	"Add mute client");
	RegAdminCmd("sm_silence", 		CommandSil, 		ADMFLAG_CHAT, 	"Add silence client");
	RegAdminCmd("sm_ungag", 		CommandUnGag, 		ADMFLAG_CHAT, 	"Un gag client");
	RegAdminCmd("sm_unmute", 		CommandUnMute, 		ADMFLAG_CHAT, 	"Un mute client");
	RegAdminCmd("sm_unsilence", 	CommandUnSil, 		ADMFLAG_CHAT, 	"Un silence client");
	
	// добавлене и удаление админа
	RegAdminCmd("ma_addadmin", 		CommandAddAdmin, 	ADMFLAG_ROOT, 	"Add admin");
	RegAdminCmd("ma_addadminoff", 	CommandAddAdminOff, ADMFLAG_ROOT, 	"Add admin off");
	RegAdminCmd("ma_deladmin", 		CommandDelAdmin, 	ADMFLAG_ROOT, 	"Del admin");

	RegServerCmd("ma_wb_ban", CommandWBan, "Ban player by command from web site");
	RegServerCmd("ma_wb_mute", CommandWMute, "Mute player by command from web site");
	RegServerCmd("ma_wb_unmute", CommandWUnMute, "Un mute player by command from web site");
	RegServerCmd("ma_wb_rehashadm", CommandWRehashAdm, "Reload SQL admins");
	
	ConVar Cvar;
	Cvar = FindConVar("sv_alltalk");
	Cvar.AddChangeHook(ConVarChange_Alltalk);
	g_bCvar_Alltalk = Cvar.BoolValue;
	if (g_iGameTyp == GAMETYP_CSGO)
	{
		
		Cvar = FindConVar("sv_talk_enemy_living");
		Cvar.AddChangeHook(ConVarChange);
		Cvar = FindConVar("sv_full_alltalk");
		Cvar.AddChangeHook(ConVarChange);
		Cvar = FindConVar("sv_deadtalk");
	}
	else
	{
		Cvar = CreateConVar("sm_deadtalk", "0", "Controls how dead communicate. 0 - Off. 1 - Dead players ignore teams. 2 - Dead players talk to living teammates.", 0, true, 0.0, true, 2.0);
		g_iCvar_Deadtalk = Cvar.IntValue;
	}

	Cvar.AddChangeHook(ConVarChange_Deadtalk);
	Cvar = FindConVar("sm_immunity_mode");
	Cvar.AddChangeHook(ConVarChange);
	g_iCvar_ImmunityMode = Cvar.IntValue;
	
}

public Action OnClientSayCommand(int iClient, const char[] sCommand, const char[] sArgs)
{
	if (g_iTargetMuteType[iClient] > 1)
	{
		char sLength[128];
		if (g_iTargenMuteTime[iClient] > 0)
			FormatVrema(iClient, g_iTargenMuteTime[iClient] - GetTime(), sLength, sizeof(sLength));
		else
			FormatVrema(iClient, g_iTargenMuteTime[iClient], sLength, sizeof(sLength));
		
		PrintToChat2(iClient, "%T", "Target no text chat", iClient, sLength, g_sTargetMuteReason[iClient]);
		return Plugin_Handled;
	}
	if (g_bReport && iClient && StrEqual(sArgs, "!report", false))
	{
		ReportMenu(iClient);
		return Plugin_Handled;
	}
	if (g_bSayReasonReport[iClient])
	{
	#if MADEBUG
		LogToFile(g_sLogFile, "Chat report reason: %s", sArgs);
	#endif
		PrintToChat2(iClient, "%T", "Own reason", iClient, sArgs);
		g_bSayReasonReport[iClient] = false;
		SetBdReport(iClient, sArgs);
		return Plugin_Handled;
	}
	if (g_bSayReason[iClient])
	{
		strcopy(g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]), sArgs);
	#if MADEBUG
		LogToFile(g_sLogFile, "Chat reason: %s", sArgs);
	#endif
		PrintToChat2(iClient, "%T", "Own reason", iClient, sArgs);
		g_bSayReason[iClient] = false;
		OnlineClientSet(iClient);
		return Plugin_Handled;
	}
	if (g_bAdminAdd[iClient][ADDIMUN])
	{
		int iImun = StringToInt(sArgs);
		if (iImun > -1 && iImun < 100)
		{
			g_bAdminAdd[iClient][ADDIMUN] = false;
			g_iAddAdmin[iClient][ADDIMUN] = iImun;
			g_bAdminAdd[iClient][ADDPASS] = true;
			PrintToChat2(iClient, "%T", "say set imune next pass", iClient, sArgs);
		}
		else
			PrintToChat2(iClient, "%T", "Failed imune", iClient);
		return Plugin_Handled;
	}
	if (g_bAdminAdd[iClient][ADDPASS])
	{
		strcopy(g_sAddAdminInfo[iClient][ADDPASS], sizeof(g_sAddAdminInfo[][]), sArgs);
		g_bAdminAdd[iClient][ADDPASS] = false;
		g_bAdminAdd[iClient][ADDTIME] = true;
		PrintToChat2(iClient, "%T", "say set pass next expire", iClient, sArgs);
		return Plugin_Handled;
	}
	if (g_bAdminAdd[iClient][ADDTIME])
	{
		int iTime = StringToInt(sArgs);
		if (iTime < 0)
			PrintToChat2(iClient, "%T", "Failed expire", iClient);
		else
		{
			g_iAddAdmin[iClient][ADDTIME] = iTime;
			PrintToChat2(iClient, "%T", "say set expire", iClient, sArgs);
			BDAddAdmin(iClient); // добавление админа в бд
		}

		return Plugin_Handled;
	}
	
	return Plugin_Continue;
}

public Action CommandClear(int iClient, int iArgc)
{
	ClearHistories();

	ReplyToCommand(iClient, "%sClear history", MAPREFIX);
	
	return Plugin_Handled;
}

public Action CommandReload(int iClient, int iArgc)
{
	ReadConfig();

	ReplyToCommand(iClient, "%sReload Config", MAPREFIX);
	
	return Plugin_Handled;
}

public Action CommandConnectBd(int iClient, int iArgc)
{
	if (ConnectBd(g_dDatabase))
	{
		ReplyToCommand(iClient, "%sYes connect bd", MAPREFIX);
		KillTimerBekap();
	}
	else
		ReplyToCommand(iClient, "%sNo connect bd", MAPREFIX);
	
	return Plugin_Handled;
}
//------------------------------------------------------------------------------------------------------------------
// добавление и удаление админа
public Action CommandAddAdmin(int iClient, int iArgc)
{
	int iFlag = GetAdminWebFlag(iClient);
	if (!iFlag || iFlag == 4)
	{
		if (iClient)
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "No Access setting admin", iClient);
		else
			ReplyToCommand(iClient, "%sNo Access add admin", MAPREFIX);
		return Plugin_Handled;
	}
	
	if (iArgc < 5)
	{
		ReplyToCommand(iClient, "%sUsage: ma_addadmin <#userid> <imunitet> <flag> <pass> <expire>", MAPREFIX);
		return Plugin_Handled;
	}
	
	char sArg[12],
		 sImun[12],
		 sFlags[32],
		 sPass[125],
		 sExpire[12];
	
	GetCmdArg(1, sArg, sizeof(sArg));
	GetCmdArg(2, sImun, sizeof(sImun));
	GetCmdArg(3, sFlags, sizeof(sFlags));
	GetCmdArg(4, sPass, sizeof(sPass));
	GetCmdArg(5, sExpire, sizeof(sExpire));

#if MADEBUG
	LogToFile(g_sLogFile,"Command ma_addadmin: arg - %s, imun - %s, flag - %s, pass - %s, expire - %s.", sArg, sImun, sFlags, sPass, sExpire);
#endif
	
	int iUserId = StringToInt(sArg[1]);
	#if MADEBUG
		LogToFile(g_sLogFile,"Command get target: UserId %d.", iUserId);
	#endif
	int iTarget = GetClientOfUserId(iUserId);
	
	if (!iTarget)
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed to player", iClient);
		return Plugin_Handled;
	}
	
	AdminId idAdmin = GetUserAdmin(iTarget);
	if(idAdmin != INVALID_ADMIN_ID)
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed to player admin", iClient);
		return Plugin_Handled;
	}
	
	int iImun = StringToInt(sImun);
	if (iImun < 0 || iImun > 100)
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed imune", iClient);
		return Plugin_Handled;
	}
	
	if (!sFlags[0])
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed flag pust", iClient);
		return Plugin_Handled;
	}
	
	if (SimpleRegexMatch(sFlags, "[zbacdefghijklmnopqrst]") < 1)
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed flag", iClient);
		return Plugin_Handled;
	}
	
	int iTime = StringToInt(sExpire);
	if (iTime < 0)
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed expire", iClient);
		return Plugin_Handled;
	}
	
	GetClientName(iTarget, g_sAddAdminInfo[iClient][ADDNAME], sizeof(g_sAddAdminInfo[][]));
	GetClientAuthId(iTarget, TYPE_STEAM, g_sAddAdminInfo[iClient][ADDSTEAM], sizeof(g_sAddAdminInfo[][]));
	strcopy(g_sAddAdminInfo[iClient][ADDPASS], sizeof(g_sAddAdminInfo[][]), sPass);
	g_iAddAdmin[iClient][ADDIMUN] = iImun;
	g_iAddAdmin[iClient][ADDTIME] = iTime;
	strcopy(g_sAddAdminInfo[iClient][ADDFLAG], sizeof(g_sAddAdminInfo[][]), sFlags);
	BDCheckAdmins(iClient, 3);
	return Plugin_Handled;
}

public Action CommandAddAdminOff(int iClient, int iArgc)
{
	int iFlag = GetAdminWebFlag(iClient);
	if (!iFlag || iFlag == 4)
	{
		if (iClient)
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "No Access setting admin", iClient);
		else
			ReplyToCommand(iClient, "%sNo Access add admin", MAPREFIX);
		return Plugin_Handled;
	}
	
	if (iArgc < 6)
	{
		ReplyToCommand(iClient, "%sUsage: ma_addadminoff <name|login> <steam> <imunitet> <flag> <pass> <expire>", MAPREFIX);
		return Plugin_Handled;
	}
	
	char sName[MAX_NAME_LENGTH],
		 sSteamID[MAX_STEAMID_LENGTH],
		 sImun[12],
		 sFlags[32],
		 sPass[125],
		 sExpire[12];
	
	GetCmdArg(1, sName, sizeof(sName));
	GetCmdArg(2, sSteamID, sizeof(sSteamID));
	GetCmdArg(3, sImun, sizeof(sImun));
	GetCmdArg(4, sFlags, sizeof(sFlags));
	GetCmdArg(5, sPass, sizeof(sPass));
	GetCmdArg(6, sExpire, sizeof(sExpire));
	
#if MADEBUG
	LogToFile(g_sLogFile,"Command ma_addadminoff: name - %s, steam %s, imun - %s, flag - %s, pass - %s, expire - %s.", sName, sSteamID, sImun, sFlags, sPass, sExpire);
#endif
	
	if (!ValidSteam(sSteamID))
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed steam", iClient);
		return Plugin_Handled;
	}
	
	if (sSteamID[0] == '[')
		ConvecterSteam3ToSteam2(sSteamID, sizeof(sSteamID));
	
	int iTarget = FindTargetSteam(sSteamID);
	
	if (iTarget)
	{
		AdminId idAdmin = GetUserAdmin(iTarget);
		if(idAdmin != INVALID_ADMIN_ID)
		{
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed to player admin", iClient);
			return Plugin_Handled;
		}
	}
	
	int iImun = StringToInt(sImun);
	if (iImun > 100 || iImun < 0)
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed imune", iClient);
		return Plugin_Handled;
	}
	
	if (!sFlags[0])
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed flag pust", iClient);
		return Plugin_Handled;
	}
	
	if (SimpleRegexMatch(sFlags, "[zbacdefghijklmnopqrst]") < 1)
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed flag", iClient);
		return Plugin_Handled;
	}
	
	int iTime = StringToInt(sExpire);
	if (iTime < 0)
	{
		ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed expire", iClient);
		return Plugin_Handled;
	}
	
	strcopy(g_sAddAdminInfo[iClient][ADDNAME], sizeof(g_sAddAdminInfo[][]), sName);
	strcopy(g_sAddAdminInfo[iClient][ADDSTEAM], sizeof(g_sAddAdminInfo[][]), sSteamID);
	strcopy(g_sAddAdminInfo[iClient][ADDPASS], sizeof(g_sAddAdminInfo[][]), sPass);
	g_iAddAdmin[iClient][ADDIMUN] = iImun;
	g_iAddAdmin[iClient][ADDTIME] = iTime;
	strcopy(g_sAddAdminInfo[iClient][ADDFLAG], sizeof(g_sAddAdminInfo[][]), sFlags);

	BDCheckAdmins(iClient, 3);
	return Plugin_Handled;
}

public Action CommandDelAdmin(int iClient, int iArgc)
{
	int iFlag = GetAdminWebFlag(iClient);
	if (!iFlag || iFlag == 3)
	{
		if (iClient)
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "No Access setting admin", iClient);
		else
			ReplyToCommand(iClient, "%sNo Access add admin", MAPREFIX);
		return Plugin_Handled;
	}
	
	if (iArgc < 2)
	{
		ReplyToCommand(iClient, "%sUsage: ma_deladmin <#userid|steam> <type> type - 0 all, 1 server", MAPREFIX);
		return Plugin_Handled;
	}
	
	char sArg[MAX_STEAMID_LENGTH],
		 sType[12];
	int iTarget;
	
	GetCmdArg(1, sArg, sizeof(sArg));
	GetCmdArg(2, sType, sizeof(sType));
	
#if MADEBUG
	LogToFile(g_sLogFile,"Command ma_deladmin: arg - %s, type %s.", sArg, sType);
#endif
	
	if (sArg[0] == '#')
	{
		int iUserId = StringToInt(sArg[1]);
		#if MADEBUG
			LogToFile(g_sLogFile,"Command get target: UserId %d.", iUserId);
		#endif
		iTarget = GetClientOfUserId(iUserId);
		
		if (!iTarget)
		{
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed to player", iClient);
			return Plugin_Handled;
		}
		
		GetClientName(iTarget, g_sAddAdminInfo[iClient][ADDNAME], sizeof(g_sAddAdminInfo[][]));
		GetClientAuthId(iTarget, TYPE_STEAM, g_sAddAdminInfo[iClient][ADDSTEAM], sizeof(g_sAddAdminInfo[][]));
	}
	else
	{
		if (!ValidSteam(sArg))
		{
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed steam", iClient);
			return Plugin_Handled;
		}
		
		iTarget = FindTargetSteam(sArg);
		
		strcopy(g_sAddAdminInfo[iClient][ADDNAME], sizeof(g_sAddAdminInfo[][]), sArg);
		strcopy(g_sAddAdminInfo[iClient][ADDSTEAM], sizeof(g_sAddAdminInfo[][]), sArg);
	}
	
	if (iTarget)
	{
		AdminId idAdmin = GetUserAdmin(iTarget);
		if(idAdmin == INVALID_ADMIN_ID)
		{
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed to player no admin", iClient);
			return Plugin_Handled;
		}
	}

	BDCheckAdmins(iClient, (StringToInt(sType) == 0)?2:1);
	return Plugin_Handled;
}
//------------------------------------------------------------------------------------------------------------------
public Action CommandGag(int iClient, int iArgc)
{
	if (iArgc < 2)
	{
		ReplyToCommand(iClient, "%sUsage: sm_gag <#userid|#all|#ct|#t|#blue|#red> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}
	char sArg[64],
		sBuffer[256];	
	GetCmdArgString(sBuffer, sizeof(sBuffer));
	
	if (!SBGetCmdArg2(iClient, sBuffer, sArg, sizeof(sArg)))
	{
		ReplyToCommand(iClient, "%sUsage: sm_gag <#userid|#all|#ct|#t|#blue|#red> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	g_iTargetType[iClient] = TYPE_GAG;
	if (!ValidTime(iClient))
		return Plugin_Handled;
	
	int iTyp = GetTypeClient(sArg);
#if MADEBUG
	LogToFile(g_sLogFile,"Command: sm_gag, arg %s, type %d, time %d, reason %s.", sArg, iTyp, g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
#endif
	GetClientToBd(iClient, iTyp, sArg);
	
	return Plugin_Handled;
}

public Action CommandMute(int iClient, int iArgc)
{
	if (iArgc < 2)
	{
		ReplyToCommand(iClient, "%sUsage: sm_mute <#userid|#all|#ct|#t|#blue|#red> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}
	char sArg[64],
		sBuffer[256];	
	GetCmdArgString(sBuffer, sizeof(sBuffer));
	
	if (!SBGetCmdArg2(iClient, sBuffer, sArg, sizeof(sArg)))
	{
		ReplyToCommand(iClient, "%sUsage: sm_mute <#userid|#all|#ct|#t|#blue|#red> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	g_iTargetType[iClient] = TYPE_MUTE;
	if (!ValidTime(iClient))
		return Plugin_Handled;
	
	int iTyp = GetTypeClient(sArg);
#if MADEBUG
	LogToFile(g_sLogFile,"Command: sm_mute, arg %s, type %d, time %d, reason %s.", sArg, iTyp, g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
#endif
	GetClientToBd(iClient, iTyp, sArg);
	
	return Plugin_Handled;
}

public Action CommandSil(int iClient, int iArgc)
{
	if (iArgc < 2)
	{
		ReplyToCommand(iClient, "%sUsage: sm_silence <#userid|#all|#ct|#t|#blue|#red> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	char sArg[64],
		sBuffer[256];	
	GetCmdArgString(sBuffer, sizeof(sBuffer));
	
	if (!SBGetCmdArg2(iClient, sBuffer, sArg, sizeof(sArg)))
	{
		ReplyToCommand(iClient, "%sUsage: sm_silence <#userid|#all|#ct|#t|#blue|#red> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	g_iTargetType[iClient] = TYPE_SILENCE;
	if (!ValidTime(iClient))
		return Plugin_Handled;
	
	int iTyp = GetTypeClient(sArg);
#if MADEBUG
	LogToFile(g_sLogFile,"Command: sm_silence, arg %s, type %d, time %d, reason %s.", sArg, iTyp, g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
#endif
	GetClientToBd(iClient, iTyp, sArg);
	
	return Plugin_Handled;
}

public Action CommandUnGag(int iClient, int iArgc)
{
	if (iArgc < 1)
	{
		ReplyToCommand(iClient, "%sUsage:  sm_ungag <#userid|#all|#ct|#t|#blue|#red> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	char sArg[64],
		sBuffer[256];	
	GetCmdArgString(sBuffer, sizeof(sBuffer));
	SBGetCmdArg1(iClient, sBuffer, sArg, sizeof(sArg));
	
	g_iTargetType[iClient] = TYPE_UNGAG;
	int iTyp = GetTypeClient(sArg);
#if MADEBUG
	LogToFile(g_sLogFile,"Command: sm_ungag, arg %s, type %d, reason %s.", sArg, iTyp, g_sTarget[iClient][TREASON]);
#endif
	GetClientToBd(iClient, iTyp, sArg);

	return Plugin_Handled;
}

public Action CommandUnMute(int iClient, int iArgc)
{
	if (iArgc < 1)
	{
		ReplyToCommand(iClient, "%sUsage:  sm_unmute <#userid|#all|#ct|#t|#blue|#red> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	char sArg[64],
		sBuffer[256];	
	GetCmdArgString(sBuffer, sizeof(sBuffer));
	SBGetCmdArg1(iClient, sBuffer, sArg, sizeof(sArg));
	
	g_iTargetType[iClient] = TYPE_UNMUTE;
	int iTyp = GetTypeClient(sArg);
#if MADEBUG
	LogToFile(g_sLogFile,"Command: sm_unmute, arg %s, type %d, reason %s.", sArg, iTyp, g_sTarget[iClient][TREASON]);
#endif
	GetClientToBd(iClient, iTyp, sArg);

	return Plugin_Handled;
}

public Action CommandUnSil(int iClient, int iArgc)
{
	if (iArgc < 1)
	{
		ReplyToCommand(iClient, "%sUsage:  sm_unsilence <#userid|#all|#ct|#t|#blue|#red> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	char sArg[64],
		sBuffer[256];	
	GetCmdArgString(sBuffer, sizeof(sBuffer));
	SBGetCmdArg1(iClient, sBuffer, sArg, sizeof(sArg));
	
	g_iTargetType[iClient] = TYPE_UNSILENCE;
	int iTyp = GetTypeClient(sArg);
#if MADEBUG
	LogToFile(g_sLogFile,"Command: sm_unsilence, arg %s, type %d, reason %s.", sArg, iTyp, g_sTarget[iClient][TREASON]);
#endif
	GetClientToBd(iClient, iTyp, sArg);

	return Plugin_Handled;
}

//-------------------------------------------------------------------------------------------------------------------
public Action CommandBan(int iClient, int iArgc)
{
	if (iArgc < 2)
	{
		ReplyToCommand(iClient, "%sUsage: sm_ban <#userid|#all|#ct|#t|#blue|#red> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	char sArg[64],
		sBuffer[256];	
	GetCmdArgString(sBuffer, sizeof(sBuffer));
	
	if (!SBGetCmdArg2(iClient, sBuffer, sArg, sizeof(sArg)))
	{
		ReplyToCommand(iClient, "%sUsage: sm_ban <#userid|#all|#ct|#t|#blue|#red> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	g_iTargetType[iClient] = TYPE_BAN;
	if (!ValidTime(iClient))
		return Plugin_Handled;
	
	int iTyp = GetTypeClient(sArg);
#if MADEBUG
	LogToFile(g_sLogFile,"Command: sm_ban, arg %s, type %d, time %d, reason %s.", sArg, iTyp, g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
#endif
	GetClientToBd(iClient, iTyp, sArg);
	
	return Plugin_Handled;
}

public Action CommandBanIp(int iClient, int iArgc)
{
	if (iArgc < 2)
	{
		ReplyToCommand(iClient, "%sUsage: sm_banip <#userid|#all|#ct|#t|#blue|#red> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	char sArg[64],
		sBuffer[256];	
	GetCmdArgString(sBuffer, sizeof(sBuffer));
	
	if (!SBGetCmdArg2(iClient, sBuffer, sArg, sizeof(sArg)))
	{
		ReplyToCommand(iClient, "%sUsage: sm_banip <#userid|#all|#ct|#t|#blue|#red> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	g_iTargetType[iClient] = TYPE_BANIP;
	if (!ValidTime(iClient))
		return Plugin_Handled;
	
	int iTyp = GetTypeClient(sArg);
#if MADEBUG
	LogToFile(g_sLogFile,"Command: sm_banip, arg %s, type %d, time %d, reason %s.", sArg, iTyp, g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
#endif
	GetClientToBd(iClient, iTyp, sArg);
	
	return Plugin_Handled;
}

public Action CommandAddBan(int iClient, int iArgc)
{
	if(!g_bAddBan)
		return Plugin_Handled;

	if (iArgc < 2)
	{
		ReplyToCommand(iClient, "%sUsage: sm_addban <steamid|ip> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	char sArg[64],
		sBuffer[256];	
	GetCmdArgString(sBuffer, sizeof(sBuffer));
	
	if (!SBGetCmdArg2(iClient, sBuffer, sArg, sizeof(sArg)))
	{
		ReplyToCommand(iClient, "%sUsage: sm_addban <steamid|ip> <time> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	g_iTargetType[iClient] = TYPE_ADDBAN;
	if (!ValidTime(iClient))
		return Plugin_Handled;

	int iTarget;
	if (sArg[0] == 'S' || sArg[0] == '[')
	{
		if (!ValidSteam(sArg))
		{
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed steam", iClient);
			return Plugin_Handled;
		}
		else
		{
			if (sArg[0] == '[')
				ConvecterSteam3ToSteam2(sArg, sizeof(sArg));
			iTarget = FindTargetSteam(sArg);
		}
	}
	else
	{
		if (SimpleRegexMatch(sArg, "\\d{1,3}.\\d{1,3}.\\d{1,3}.\\d{1,3}?") > 0)
			iTarget = FindTargetIp(sArg);
		else
		{
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed ip", iClient);
			return Plugin_Handled;
		}
	}

#if MADEBUG
	LogToFile(g_sLogFile,"Command: sm_addban, arg %s, target %d, time %d, reason %s.", sArg, iTarget, g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
#endif

	if(iTarget)
	{
		if(GetUserAdmin(iTarget) == INVALID_ADMIN_ID)
			CheckBanInBd(iClient, iTarget, 1, sArg);
		else
		{
			if (iClient)
				PrintToChat2(iClient, "%T", "No admin", iClient);
			else
				ReplyToCommand(iClient, "%sThis Admin immunity.", MAPREFIX);
		}
	}
	else
		CheckBanInBd(iClient, iTarget, 1, sArg);

	return Plugin_Handled;
}

public Action CommandUnBan(int iClient, int iArgc)
{
	if (!g_bUnBan)
		return Plugin_Handled;

	if (iArgc < 1)
	{
		ReplyToCommand(iClient, "%sUsage:  sm_unban <steamid|ip> [reason]", MAPREFIX);
		return Plugin_Handled;
	}

	char sArg[64],
		sBuffer[256];	
	GetCmdArgString(sBuffer, sizeof(sBuffer));
	SBGetCmdArg1(iClient, sBuffer, sArg, sizeof(sArg));
	
	if (sArg[0] == 'S' || sArg[0] == '[')
	{
		if (!ValidSteam(sArg))
		{
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed steam", iClient);
			return Plugin_Handled;
		}
		else
		{
			if (sArg[0] == '[')
				ConvecterSteam3ToSteam2(sArg, sizeof(sArg));
		}
	}
	else
	{
		if (SimpleRegexMatch(sArg, "\\d{1,3}.\\d{1,3}.\\d{1,3}.\\d{1,3}?") < 1)
		{
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "Failed ip", iClient);
			return Plugin_Handled;
		}
	}
	
	g_iTargetType[iClient] = TYPE_UNBAN;

#if MADEBUG
	LogToFile(g_sLogFile,"Command: sm_unban, arg %s, reason %s.", sArg, g_sTarget[iClient][TREASON]);
#endif
	CheckBanInBd(iClient, 0, 0, sArg);

	return Plugin_Handled;
}
//------------------------------------------------------------------------------------------------------------------------
public Action CommandWRehashAdm(int iArgc)
{
	g_bReshashAdmin = true;
#if MADEBUG
	LogToFile(g_sLogFile, "Rehash Admin web com.");
#endif
	AdminHash();
	ReplyToCommand(0, "Rehash Admin");
	return Plugin_Handled;
}

public Action CommandWMute(int iArgc)
{
	char sArgs[256],
		sArg[4][264];
	GetCmdArgString(sArgs, sizeof(sArgs));

	int iType, iTime;
	if (ExplodeString(sArgs, " ", sArg, 4, 264) != 4 || !StringToIntEx(sArg[0], iType) || iType < 1 || iType > 4 || !StringToIntEx(sArg[1], iTime))
	{
		LogToFile(g_sLogFile, "Wrong usage of ma_wb_mute");
		return Plugin_Stop;
	}
	
	int iClient = FindTargetSteam(sArg[2]);
	
	if(iClient)
	{
		if (iTime > 0)
			g_iTargenMuteTime[iClient] = GetTime() + iTime;
		else
			g_iTargenMuteTime[iClient] = iTime;
		strcopy(g_sTargetMuteReason[iClient], sizeof(g_sTargetMuteReason[]), sArg[3]);
		ReplyToCommand(0, "ok");
		switch(iType)
		{
			case TYPEMUTE:		AddMute(iClient, iTime);
			case TYPEGAG: 		AddGag(iClient, iTime);
			case TYPESILENCE:	AddSilence(iClient, iTime);
		}	
	}
	else
		ReplyToCommand(0, "nope");
	
#if MADEBUG
	LogToFile(g_sLogFile,"CommandWMute: %s", sArgs);
#endif
	
	return Plugin_Handled;
}

public Action CommandWUnMute(int iArgc)
{
	char sArgs[256],
		sArg[2][64];
	GetCmdArgString(sArgs, sizeof(sArgs));

	int iType;
	if (ExplodeString(sArgs, " ", sArg, 2, 64) != 2 || !StringToIntEx(sArg[0], iType) || iType < 1 || iType > 4)
	{
		LogToFile(g_sLogFile, "Wrong usage of ma_wb_unmute");
		return Plugin_Stop;
	}
	
	int iClient = FindTargetSteam(sArg[1]);
	
	if(iClient)
	{
		ReplyToCommand(0, "ok");
		switch(iType)
		{
			case TYPEMUTE:		UnMute(iClient);
			case TYPEGAG: 		UnGag(iClient);
			case TYPESILENCE:	UnSilence(iClient);
		}	
	}
	else
		ReplyToCommand(0, "nope");
	
#if MADEBUG
	LogToFile(g_sLogFile,"CommandWUnMute: %s", sArgs);
#endif
	
	return Plugin_Handled;
}

public Action CommandWBan(int iArgc)
{
	char sArgs[256],
		sArg[1][64];
	GetCmdArgString(sArgs, sizeof(sArgs));

	if (!ExplodeString(sArgs, " ", sArg, 1, 64))
	{
		LogToFile(g_sLogFile, "Wrong usage of ma_wb_ban");
		return Plugin_Stop;
	}
	
	int iClient;
	if (strncmp(sArg[0], "STEAM_", 6) == 0)
		iClient = FindTargetSteam(sArg[0]);
	else
		iClient = FindTargetIp(sArg[0]);
	
	if(iClient)
	{
		ReplyToCommand(0, "ok");
		CheckClientBan(iClient);
	}
	else
		ReplyToCommand(0, "nope");
	
#if MADEBUG
	LogToFile(g_sLogFile,"CommandWBan: %s", sArgs);
#endif
	
	return Plugin_Handled;
}