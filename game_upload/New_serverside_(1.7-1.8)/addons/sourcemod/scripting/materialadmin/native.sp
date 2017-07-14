public APLRes AskPluginLoad2(Handle myself, bool late, char[] error, int err_max)
{
	RegPluginLibrary("materialadmin");
	CreateNative("MAOffBanPlayer", Native_OffBan);
	CreateNative("MABanPlayer", Native_BanPlayer);
	CreateNative("MAUnBanPlayer", Native_UnBanPlayer);
	CreateNative("MAGetAdminExpire", Native_GetAdminExpire);
	CreateNative("MASetClientMuteType", Native_SetClientMuteType);
	CreateNative("MAOffSetClientMuteType", Native_OffSetClientMuteType);
	CreateNative("MAGetClientMuteType", Native_GetClientMuteType);
	CreateNative("MAGetConfigSetting", Native_GetConfigSetting);
	CreateNative("MAGetDatabase", Native_GetDatabase);
	CreateNative("MALog", Native_Log);
}

public int Native_GetDatabase(Handle plugin, int numParams)
{
	return view_as<int>(CloneHandle(g_dDatabase, plugin));
}

public int Native_Log(Handle plugin, int numParams)
{
	char sBufer[256];
	FormatNativeString(0, 1, 2, sizeof(sBufer), _, sBufer);
	LogToFile(g_sLogNative, sBufer);
}

public int Native_GetConfigSetting(Handle plugin, int numParams)
{
	int iLen;
	GetNativeStringLength(1, iLen);

	if (iLen <= 0)
		return ThrowNativeError(SP_ERROR_NATIVE, "Error: Config Setting invalid.");

	char sSetting[126],
		 sValue[512];
	GetNativeString(1, sSetting, sizeof(sSetting));

	if(StrEqual("DatabasePrefix", sSetting, false)) 
		strcopy(sValue, sizeof(sValue), g_sDatabasePrefix);
	else if(StrEqual("Website", sSetting, false)) 
		strcopy(sValue, sizeof(sValue), g_sWebsite);
	else if(StrEqual("OffTimeFormat", sSetting, false))
		strcopy(sValue, sizeof(sValue), g_sOffFormatTime);
	else if(StrEqual("Addban", sSetting, false))
	{
		if(g_bAddBan)
			sValue = "1";
		else
			sValue = "0";
	}
	else if(StrEqual("Unban", sSetting, false))
	{
		if(g_bUnBan)
			sValue = "1";
		else
			sValue = "0";
	}
	else if(StrEqual("OffMapClear", sSetting, false))
	{
		if(g_bOffMapClear)
			sValue = "1";
		else
			sValue = "0";
	}
	else if(StrEqual("Report", sSetting, false))
	{
		if(g_bReport)
			sValue = "1";
		else
			sValue = "0";
	}
	else if(StrEqual("BanSayPanel", sSetting, false))
	{
		if(g_bBanSayPanel)
			sValue = "1";
		else
			sValue = "0";
	}
	else if(StrEqual("ActionOnTheMy", sSetting, false))
	{
		if(g_bActionOnTheMy)
			sValue = "1";
		else
			sValue = "0";
	}
	else if(StrEqual("IgnoreBanServer", sSetting, false))
	{
		if(g_bIgnoreBanServer)
			sValue = "1";
		else
			sValue = "0";
	}
	else if(StrEqual("IgnoreMuteServer", sSetting, false))
	{
		if(g_bIgnoreMuteServer)
			sValue = "1";
		else
			sValue = "0";
	}
	else if(StrEqual("ServerBanTyp", sSetting, false))
	{
		if(g_bServerBanTyp)
			sValue = "1";
		else
			sValue = "0";
	}
	else if(StrEqual("MassBan", sSetting, false))
		IntToString(g_iMassBan, sValue, sizeof(sValue));
	else if(StrEqual("ServerBanTime", sSetting, false))
		IntToString(g_iServerBanTime, sValue, sizeof(sValue));
	else if(StrEqual("ServerID", sSetting, false))
		IntToString(g_iServerID, sValue, sizeof(sValue));
	else if(StrEqual("OffMaxPlayers", sSetting, false))
		IntToString(g_iOffMaxPlayers, sValue, sizeof(sValue));
	else if(StrEqual("OffMenuNast", sSetting, false))
		IntToString(g_iOffMenuItems, sValue, sizeof(sValue));
	else if(StrEqual("RetryTime", sSetting, false))
		FloatToString(g_fRetryTime, sValue, sizeof(sValue));
	else if(StrEqual("ShowAdminAction", sSetting, false))
		IntToString(g_iShowAdminAction, sValue, sizeof(sValue));
	else if(StrEqual("BasecommTime", sSetting, false))
		IntToString(g_iBasecommTime, sValue, sizeof(sValue));
	else if(StrEqual("ServerImmune", sSetting, false))
		IntToString(g_iServerImmune, sValue, sizeof(sValue));
	else if(StrEqual("BanTypMenu", sSetting, false))
		IntToString(g_iBanTypMenu, sValue, sizeof(sValue));
	else
		return ThrowNativeError(SP_ERROR_NATIVE, "Error: Config Setting invalid.");

	SetNativeString(2, sValue, sizeof(sValue), false);
	return true;
}

public int Native_OffBan(Handle plugin, int numParams)
{
	int iClient = GetNativeCell(1);
	int iType = GetNativeCell(2);
	GetNativeString(3, g_sTarget[iClient][TSTEAMID], sizeof(g_sTarget[][]));
	GetNativeString(4, g_sTarget[iClient][TIP], sizeof(g_sTarget[][]));
	GetNativeString(5, g_sTarget[iClient][TNAME], sizeof(g_sTarget[][]));
	g_iTarget[iClient][TTIME] = GetNativeCell(6);
	GetNativeString(7, g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]));
	
	if (iType < 3 && iType > 0)
		g_iTargetType[iClient] = iType;
	else
		return ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Invalid Type.");
	
	if (iClient && IsClientInGame(iClient))
	{
		if (GetUserAdmin(iClient) == INVALID_ADMIN_ID)
			return ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Player is not an admin.");
		
		if (!CheckAdminFlags(iClient, ADMFLAG_BAN))
			return ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Player does not have BAN flag.");
	}
	
	CheckBanInBd(iClient, 0, 1, g_sTarget[iClient][TSTEAMID]);
	return true;
}

public int Native_BanPlayer(Handle plugin, int numParams)
{
	int iClient = GetNativeCell(1);
	int iTarget = GetNativeCell(2);
	int iType = GetNativeCell(3);
	g_iTarget[iClient][TTIME] = GetNativeCell(4);
	GetNativeString(5, g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]));
	
	if (iType < 3 && iType > 0)
		g_iTargetType[iClient] = iType;
	else
		return ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Invalid Type.");
	
	if (iClient && IsClientInGame(iClient))
	{
		if (GetUserAdmin(iClient) == INVALID_ADMIN_ID)
			return ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Player is not an admin.");
		
		if (!CheckAdminFlags(iClient, ADMFLAG_BAN))
			return ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Player does not have BAN flag.");
	}
	if (!iTarget || !IsClientInGame(iTarget))
		return ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Player no game.");
	
	CreateDB(iClient, iTarget);
	return true;
}

public int Native_UnBanPlayer(Handle plugin, int numParams)
{
	int iClient = GetNativeCell(1);
	char sId[MAX_IP_LENGTH];
	GetNativeString(2, sId, sizeof(sId));
	GetNativeString(3, g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]));
	
	if (iClient && IsClientInGame(iClient))
	{
		if (GetUserAdmin(iClient) == INVALID_ADMIN_ID)
			return ThrowNativeError(SP_ERROR_NATIVE, "UnBan Error: Player is not an admin.");
		
		if (!CheckAdminFlags(iClient, ADMFLAG_UNBAN))
			return ThrowNativeError(SP_ERROR_NATIVE, "UnBan Error: Player does not have UNBAN flag.");
	}
	g_iTargetType[iClient] = TYPE_UNBAN;
	
	CheckBanInBd(iClient, 0, 0, sId);
	return true;
}

public int Native_GetAdminExpire(Handle plugin, int numParams)
{
	AdminId idAdmin = GetNativeCell(1);
	return GetAdminExpire(idAdmin);
}

public int Native_SetClientMuteType(Handle plugin, int numParams)
{
	int iClient = GetNativeCell(1);
	int iTarget = GetNativeCell(2);
	GetNativeString(3, g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]));
	int iType = GetNativeCell(4);
	g_iTarget[iClient][TTIME] = GetNativeCell(5);
	
	if (iType > 4 && iType < 11)
		g_iTargetType[iClient] = iType;
	else
		return ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Invalid Type.");
	
	if (iClient && IsClientInGame(iClient))
	{
		if (GetUserAdmin(iClient) == INVALID_ADMIN_ID)
			return ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Player is not an admin.");
		
		if (!CheckAdminFlags(iClient, ADMFLAG_CHAT))
			return ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Player does not have CHAT flag.");
	}
	if (!iTarget || !IsClientInGame(iTarget))
		return ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Player no game.");
	
	DoCreateDB(iClient, iTarget);
	return true;
}

public int Native_OffSetClientMuteType(Handle plugin, int numParams)
{
	int iClient = GetNativeCell(1);
	GetNativeString(2, g_sTarget[iClient][TSTEAMID], sizeof(g_sTarget[][]));
	GetNativeString(3, g_sTarget[iClient][TIP], sizeof(g_sTarget[][]));
	GetNativeString(4, g_sTarget[iClient][TNAME], sizeof(g_sTarget[][]));
	GetNativeString(5, g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]));
	int iType = GetNativeCell(6);
	g_iTarget[iClient][TTIME] = GetNativeCell(7);
	
	if (iType > 4 && iType < 11)
		g_iTargetType[iClient] = iType;
	else
		return ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Invalid Type.");
	
	if (iClient && IsClientInGame(iClient))
	{
		if (GetUserAdmin(iClient) == INVALID_ADMIN_ID)
			return ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Player is not an admin.");
		
		if (!CheckAdminFlags(iClient, ADMFLAG_CHAT))
			return ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Player does not have CHAT flag.");
	}
	
	DoCreateDB(iClient, 0);
	return true;
}

public int Native_GetClientMuteType(Handle plugin, int numParams)
{
	int iClient = GetNativeCell(1);
	return g_iTargetMuteType[iClient];
}

void FireOnClientMuted(int iClient, int iTarget, const char[] sIp, const char[] sSteamID, const char[] sName, int iType, int iTime, const char[] sReason)
{
 	static Handle hForward;
	
	if(hForward == null)
		hForward = CreateGlobalForward("MAOnClientMuted", ET_Ignore, Param_Cell, Param_Cell, Param_String, Param_String, Param_String, Param_Cell, Param_Cell, Param_String);
	
	Call_StartForward(hForward);
	Call_PushCell(iClient);
	Call_PushCell(iTarget);
	Call_PushString(sIp);
	Call_PushString(sSteamID);
	Call_PushString(sName);
	Call_PushCell(iType);
	Call_PushCell(iTime);
	Call_PushString(sReason);
	Call_Finish();
	
	if (iTarget)
	{
		switch(iType)
		{
			case 1:	BaseComm_SetClientMute(iTarget, true);
			case 2:	BaseComm_SetClientGag(iTarget, true);
			case 3:
			{
				BaseComm_SetClientMute(iTarget, true);
				BaseComm_SetClientGag(iTarget, true);
			}
		}
	}
}

void FireOnClientUnMuted(int iClient, int iTarget, const char[] sIp, const char[] sSteamID, const char[] sName, int iType, const char[] sReason)
{
 	static Handle hForward;
	
	if(hForward == null)
		hForward = CreateGlobalForward("MAOnClientUnMuted", ET_Ignore, Param_Cell, Param_Cell, Param_String, Param_String, Param_String, Param_Cell, Param_String);
	
	Call_StartForward(hForward);
	Call_PushCell(iClient);
	Call_PushCell(iTarget);
	Call_PushString(sIp);
	Call_PushString(sSteamID);
	Call_PushString(sName);
	Call_PushCell(iType);
	Call_PushString(sReason);
	Call_Finish();
	
	if (iTarget)
	{
		switch(iType)
		{
			case 1:	BaseComm_SetClientMute(iTarget, false);
			case 2:	BaseComm_SetClientGag(iTarget, false);
			case 3:
			{
				BaseComm_SetClientMute(iTarget, false);
				BaseComm_SetClientGag(iTarget, false);
			}
		}
	}
}

void FireOnClientBanned(int iClient, int iTarget, const char[] sIp, const char[] sSteamID, const char[] sName, int iTime, const char[] sReason)
{
 	static Handle hForward;
	
	if(hForward == null)
		hForward = CreateGlobalForward("MAOnClientBanned", ET_Ignore, Param_Cell, Param_Cell, Param_String, Param_String, Param_String, Param_Cell, Param_String);
	
	Call_StartForward(hForward);
	Call_PushCell(iClient);
	Call_PushCell(iTarget);
	Call_PushString(sIp);
	Call_PushString(sSteamID);
	Call_PushString(sName);
	Call_PushCell(iTime);
	Call_PushString(sReason);
	Call_Finish();
}

void FireOnClientAddBanned(int iClient, const char[] sIp, const char[] sSteamID, int iTime, const char[] sReason)
{
 	static Handle hForward;
	
	if(hForward == null)
		hForward = CreateGlobalForward("MAOnClientAddBanned", ET_Ignore, Param_Cell, Param_String, Param_String, Param_Cell, Param_String);
	
	Call_StartForward(hForward);
	Call_PushCell(iClient);
	Call_PushString(sIp);
	Call_PushString(sSteamID);
	Call_PushCell(iTime);
	Call_PushString(sReason);
	Call_Finish();
}

void FireOnClientUnBanned(int iClient, const char[] sIp, const char[] sSteamID, const char[] sReason)
{
 	static Handle hForward;
	
	if(hForward == null)
		hForward = CreateGlobalForward("MAOnClientUnBanned", ET_Ignore, Param_Cell, Param_String, Param_String, Param_String);
	
	Call_StartForward(hForward);
	Call_PushCell(iClient);
	Call_PushString(sIp);
	Call_PushString(sSteamID);
	Call_PushString(sReason);
	Call_Finish();
}

void FireOnConnectDatabase(Database db)
{
 	static Handle hForward;
	
	if(hForward == null)
		hForward = CreateGlobalForward("MAOnConnectDatabase", ET_Ignore, Param_Cell);
	
	Call_StartForward(hForward);
	Call_PushCell(db);
	Call_Finish();
}

public Action TimerOnConfigSettingForward(Handle timer, any data)
{
	Call_StartForward(g_hOnConfigSettingForward);
	Call_Finish();	
}

public void BaseComm_OnClientMute(int iClient, bool bState)
{
	if (!iClient || !IsClientInGame(iClient))
		return;

	if (bState)
	{
		if (g_iTargetMuteType[iClient] == 0 || g_iTargetMuteType[iClient] == 2)
		{
			strcopy(g_sTarget[0][TREASON], sizeof(g_sTarget[][]), "Muted through base commands natives");
			g_iTarget[0][TTIME] = g_iBasecommTime;
			g_iTargetType[0] = TYPE_MUTE;
			DoCreateDB(0, iClient);
		}
	}
	else
	{
		if (g_iTargetMuteType[iClient] == 1 || g_iTargetMuteType[iClient] == 3)
		{
			g_iTargetType[0] = TYPE_UNMUTE;
			CreateDB(0, iClient);
		}
	}
}

public void BaseComm_OnClientGag(int iClient, bool bState)
{
	if (!iClient || !IsClientInGame(iClient))
		return;

	if (bState)
	{
		if (g_iTargetMuteType[iClient] < 2)
		{
			strcopy(g_sTarget[0][TREASON], sizeof(g_sTarget[][]), "Gag through base commands natives");
			g_iTarget[0][TTIME] = g_iBasecommTime;
			g_iTargetType[0] = TYPE_GAG;
			DoCreateDB(0, iClient);
		}
	}
	else
	{
		if (g_iTargetMuteType[iClient] > 1)
		{
			g_iTargetType[0] = TYPE_UNGAG;
			CreateDB(0, iClient);
		}
	}
}