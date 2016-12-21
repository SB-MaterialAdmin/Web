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
	CreateNative("MAGetDatabaseConnect", Native_GetDatabaseConnect);
}

public int Native_GetDatabaseConnect(Handle plugin, int numParams)
{
	return view_as<int>(CloneHandle(g_dDatabase, plugin));
}

public int Native_GetConfigSetting(Handle plugin, int numParams)
{
	int iLen;
	GetNativeStringLength(1, iLen);

	if (iLen <= 0)
	{
		ThrowNativeError(SP_ERROR_NATIVE, "Error: Config Setting invalid.");
		return false;
	}

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
	else if(StrEqual("MassBan", sSetting, false))
	{
		if(g_bMassBan)
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
	else
	{
		ThrowNativeError(SP_ERROR_NATIVE, "Error: Config Setting invalid.");
		return false;
	}

	SetNativeString(2, sValue, sizeof(sValue), false);
	return true;
}

public int Native_OffBan(Handle plugin, int numParams)
{
	int iClient = GetNativeCell(1);
	GetNativeString(2, g_sTarget[iClient][TSTEAMID], sizeof(g_sTarget[][]));
	GetNativeString(3, g_sTarget[iClient][TIP], sizeof(g_sTarget[][]));
	GetNativeString(4, g_sTarget[iClient][TNAME], sizeof(g_sTarget[][]));
	g_iTarget[iClient][TTIME] = GetNativeCell(5);
	GetNativeString(6, g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]));
	
	if (iClient && IsClientInGame(iClient))
	{
		if (GetUserAdmin(iClient) == INVALID_ADMIN_ID)
		{
			ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Player is not an admin.");
			return false;
		}
		
		if (!CheckAdminFlags(iClient, ADMFLAG_BAN))
		{
			ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Player does not have BAN flag.");
			return false;
		}
	}
	g_iTargetType[iClient] = TYPE_BAN;
	
	CheckBanInBd(iClient, 0, 1, g_sTarget[iClient][TSTEAMID]);
	return true;
}

public int Native_BanPlayer(Handle plugin, int numParams)
{
	int iClient = GetNativeCell(1);
	int iTarget = GetNativeCell(2);
	g_iTarget[iClient][TTIME] = GetNativeCell(3);
	GetNativeString(4, g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]));
	
	if (iClient && IsClientInGame(iClient))
	{
		if (GetUserAdmin(iClient) == INVALID_ADMIN_ID)
		{
			ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Player is not an admin.");
			return false;
		}
		
		if (!CheckAdminFlags(iClient, ADMFLAG_BAN))
		{
			ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Player does not have BAN flag.");
			return false;
		}
	}
	if (!iTarget && !IsClientInGame(iTarget))
	{
		ThrowNativeError(SP_ERROR_NATIVE, "Ban Error: Player no game.");
		return false;
	}
	g_iTargetType[iClient] = TYPE_BAN;
	
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
		{
			ThrowNativeError(SP_ERROR_NATIVE, "UnBan Error: Player is not an admin.");
			return false;
		}
		
		if (!CheckAdminFlags(iClient, ADMFLAG_UNBAN))
		{
			ThrowNativeError(SP_ERROR_NATIVE, "UnBan Error: Player does not have UNBAN flag.");
			return false;
		}
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
	g_iTarget[iClient][TTIME] = GetNativeCell(3);
	GetNativeString(4, g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]));
	int iType = GetNativeCell(5);
	
	if (iType > 2 && iType < 10 && iType != 6)
		g_iTargetType[iClient] = iType;
	else
	{
		ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Invalid Type.");
		return false;
	}
	
	if (iClient && IsClientInGame(iClient))
	{
		if (GetUserAdmin(iClient) == INVALID_ADMIN_ID)
		{
			ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Player is not an admin.");
			return false;
		}
		
		if (!CheckAdminFlags(iClient, ADMFLAG_CHAT))
		{
			ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Player does not have CHAT flag.");
			return false;
		}
	}
	if (!iTarget && !IsClientInGame(iTarget))
	{
		ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Player no game.");
		return false;
	}
	
	DoCreateDB(iClient, iTarget);
	return true;
}

public int Native_OffSetClientMuteType(Handle plugin, int numParams)
{
	int iClient = GetNativeCell(1);
	GetNativeString(2, g_sTarget[iClient][TSTEAMID], sizeof(g_sTarget[][]));
	GetNativeString(3, g_sTarget[iClient][TIP], sizeof(g_sTarget[][]));
	GetNativeString(4, g_sTarget[iClient][TNAME], sizeof(g_sTarget[][]));
	g_iTarget[iClient][TTIME] = GetNativeCell(5);
	GetNativeString(6, g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]));
	int iType = GetNativeCell(7);
	
	if (iType > 2 && iType < 10 && iType != 6)
		g_iTargetType[iClient] = iType;
	else
	{
		ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Invalid Type.");
		return false;
	}
	
	if (iClient && IsClientInGame(iClient))
	{
		if (GetUserAdmin(iClient) == INVALID_ADMIN_ID)
		{
			ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Player is not an admin.");
			return false;
		}
		
		if (!CheckAdminFlags(iClient, ADMFLAG_CHAT))
		{
			ThrowNativeError(SP_ERROR_NATIVE, "Mute Error: Player does not have CHAT flag.");
			return false;
		}
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

public Action TimerOnConfigSettingForward(Handle timer, any data)
{
	Call_StartForward(g_hOnConfigSettingForward);
	Call_Finish();	
}