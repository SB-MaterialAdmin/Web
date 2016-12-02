public APLRes AskPluginLoad2(Handle myself, bool late, char[] error, int err_max)
{
	RegPluginLibrary("materialadmin");
	CreateNative("MAOffBanPlayer", Native_OffBan);
	CreateNative("MABanPlayer", Native_BanPlayer);
	CreateNative("MAGetAdminExpire", Native_GetAdminExpire);
	CreateNative("MASetClientMuteType", Native_SetClientMuteType);
	CreateNative("MAOffSetClientMuteType", Native_OffSetClientMuteType);
	CreateNative("MAGetClientMuteType", Native_GetClientMuteType);
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
		AdminId idAdmin = GetUserAdmin(iClient);
		if (idAdmin == INVALID_ADMIN_ID)
		{
			ThrowNativeError(1, "Ban Error: Player is not an admin.");
			return false;
		}
		
		if (!GetAdminFlag(idAdmin, Admin_Ban))
		{
			ThrowNativeError(2, "Ban Error: Player does not have BAN flag.");
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
		AdminId idAdmin = GetUserAdmin(iClient);
		if (idAdmin == INVALID_ADMIN_ID)
		{
			ThrowNativeError(1, "Ban Error: Player is not an admin.");
			return false;
		}
		
		if (!GetAdminFlag(idAdmin, Admin_Ban))
		{
			ThrowNativeError(2, "Ban Error: Player does not have BAN flag.");
			return false;
		}
	}
	if (!iTarget && !IsClientInGame(iTarget))
	{
		ThrowNativeError(3, "Ban Error: Player no game.");
		return false;
	}
	g_iTargetType[iClient] = TYPE_BAN;
	
	CreateDB(iClient, iTarget);
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
	g_iTargetType[iClient] = GetNativeCell(5);
	
	if (iClient && IsClientInGame(iClient))
	{
		AdminId idAdmin = GetUserAdmin(iClient);
		if (idAdmin == INVALID_ADMIN_ID)
		{
			ThrowNativeError(1, "Mute Error: Player is not an admin.");
			return false;
		}
		
		if (!GetAdminFlag(idAdmin, Admin_Chat))
		{
			ThrowNativeError(2, "Mute Error: Player does not have CHAT flag.");
			return false;
		}
	}
	if (!iTarget && !IsClientInGame(iTarget))
	{
		ThrowNativeError(3, "Mute Error: Player no game.");
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
	g_iTargetType[iClient] = GetNativeCell(7);
	
	if (iClient && IsClientInGame(iClient))
	{
		AdminId idAdmin = GetUserAdmin(iClient);
		if (idAdmin == INVALID_ADMIN_ID)
		{
			ThrowNativeError(1, "Ban Error: Player is not an admin.");
			return false;
		}
		
		if (!GetAdminFlag(idAdmin, Admin_Chat))
		{
			ThrowNativeError(2, "Mute Error: Player does not have CHAT flag.");
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

void FireOnClientMuted(int iClient, int iTarget, char[] sIp, char[] sSteamID, char[] sName, int iType, int iTime, const char[] sReason)
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

void FireOnClientUnMuted(int iClient, int iTarget, char[] sIp, char[] sSteamID, char[] sName, int iType, const char[] sReason)
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

void FireOnClientBanned(int iClient, int iTarget, char[] sIp, char[] sSteamID, char[] sName, int iTime, const char[] sReason)
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