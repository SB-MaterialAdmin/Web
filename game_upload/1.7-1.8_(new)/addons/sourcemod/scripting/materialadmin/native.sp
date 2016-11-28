public APLRes AskPluginLoad2(Handle myself, bool late, char[] error, int err_max)
{
	RegPluginLibrary("materialadmin");
	CreateNative("MAOffBanPlayer", Native_OffBan);
	CreateNative("MABanPlayer", Native_BanPlayer);
	CreateNative("MAGetAdminExpire", Native_GetAdminExpire);
	CreateNative("MASetClientMuteType", Native_SetClientMuteType);
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
	
	CreateDB(iClient, 0);
	return true;
}

public int Native_BanPlayer(Handle plugin, int numParams)
{
	int iClient = GetNativeCell(1);
	int iTarget = GetNativeCell(2);
	g_iTarget[iClient][TTIME] = GetNativeCell(3);
	char sReason[128];
	GetNativeString(4, sReason, sizeof(sReason));
	
	if (sReason[0] == '\0')
		strcopy(g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]), "Banned by Material Admin");
	else
		strcopy(g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]), sReason);
	
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
	char sReason[128];
	GetNativeString(4, sReason, sizeof(sReason));
	g_iTargetType[iClient] = GetNativeCell(5);
	
	if (sReason[0] == '\0')
		strcopy(g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]), "Muted by Material Admin");
	else
		strcopy(g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]), sReason);
	
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
	
	CreateDB(iClient, iTarget);
	return true;
}

public int Native_GetClientMuteType(Handle plugin, int numParams)
{
	int iClient = GetNativeCell(1);
	return g_iTargetMuteType[iClient];
}