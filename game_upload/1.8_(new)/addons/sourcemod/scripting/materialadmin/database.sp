void ConnectSourceBan()
{
	if (!SQL_CheckConfig("materialadmin"))
	{
		LogToFile(g_sLogFile, "Database failure: Could not find Database conf \"materialadmin\"");
		SetFailState("Database failure: Could not find Database conf \"materialadmin\"");
		return;
	}
	Database.Connect(ConnectDatabase, "materialadmin");

	char sError[256];
	g_dSQLite = SQLite_UseDatabase("maDatabase", sError, sizeof(sError));
	if (g_dSQLite == null)
		SetFailState("Lokal Database failure (%s)", sError);
	
	InsertServerInfo();
}

public void ConnectDatabase(Database db, const char[] sError, any data)
{
	if (db == null || sError[0])
		SetFailState("Database failure (%s)", sError);
	
	g_dDatabase = db;
	g_dDatabase.SetCharset("utf8");
	AdminHash();
	SentBekapInBd();
}

void CreateTables()
{
	SQL_LockDatabase(g_dSQLite);
	SQL_FastQuery(g_dSQLite, "PRAGMA encoding = \"UTF-8\"");
	if(SQL_FastQuery(g_dSQLite, "CREATE TABLE IF NOT EXISTS `offline` (`id` INTEGER PRIMARY KEY AUTOINCREMENT, \
										`auth` VARCHAR(32) UNIQUE ON CONFLICT REPLACE,\
										`ip` VARCHAR(24) NOT NULL, \
										`name` VARCHAR(64) DEFAULT 'unknown',\
										`disc_time` NUMERIC NOT NULL);") == false)
	{
		char sError[256];
		SQL_GetError(g_dSQLite, sError, sizeof(sError));
		SetFailState("%s Query CREATE TABLE failed! %s", PREFIX, sError);
	}
	if(SQL_FastQuery(g_dSQLite, "CREATE TABLE IF NOT EXISTS `bekap` (`id` INTEGER PRIMARY KEY AUTOINCREMENT, \
										`query` text NOT NULL);") == false)
	{
		char sError[256];
		SQL_GetError(g_dSQLite, sError, sizeof(sError));
		SetFailState("%s Query CREATE TABLE failed! %s", PREFIX, sError);
	}
	SQL_UnlockDatabase(g_dSQLite);
}
//------------------------------------------------------------------------------------------------------------
// offline
void ClearHistories()
{
	char sQuery[64];
	FormatEx(sQuery, sizeof(sQuery), "DROP TABLE  `offline`");
	g_dSQLite.Query(SQL_Callback_DeleteClients, sQuery);
}

public void SQL_Callback_DeleteClients(Database db, DBResultSet dbRs, const char[] sError, any iData)
{
	if (sError[0])
		LogToFile(g_sLogFile, "SQL_Callback_DeleteClients: %s", sError);
	else
		CreateTables();
}

void SetOflineInfo(char[] sSteamID, char[] sName, char[] sIP)
{
	char sEName[MAX_NAME_LENGTH*2+1],
		sQuery[512];

	g_dSQLite.Escape(sName, sEName, sizeof(sEName));

	FormatEx(sQuery, sizeof(sQuery), "INSERT INTO `offline` (auth, ip, name, disc_time) VALUES \
										('%s', '%s', '%s', %i)", sSteamID, sIP, sEName, GetTime());
	g_dSQLite.Query(SQL_Callback_AddClient, sQuery);
#if DEBUG
	LogToFile(g_sLogFile, "SetOflineInfo:QUERY: %s", sQuery);
#endif
}

public void SQL_Callback_AddClient(Database db, DBResultSet dbRs, const char[] sError, any iData)
{
	if (dbRs == null || sError[0])
		LogToFile(g_sLogFile, "SQL_Callback_AddClient: %s", sError);
}

void DelOflineInfo(char[] sSteamID)
{
	char sQuery[256];
	FormatEx(sQuery, sizeof(sQuery), "DELETE FROM `offline` WHERE `auth` = '%s'", sSteamID);
	g_dSQLite.Query(SQL_Callback_DeleteClient, sQuery);
#if DEBUG
	LogToFile(g_sLogFile, "DelOflineInfo:QUERY: %s", sQuery);
#endif
}

public void SQL_Callback_DeleteClient(Database db, DBResultSet dbRs, const char[] sError, any iData)
{
	if (sError[0])
		LogToFile(g_sLogFile, "SQL_Callback_DeleteClient: %s", sError);
}

//меню выбора игрока офлайн
void BdTargetOffline(int iClient)
{
	char sQuery[324];
	FormatEx(sQuery, sizeof(sQuery), "SELECT `id`, `auth`, `name`, `disc_time` FROM `offline` ORDER BY `id` DESC LIMIT %d;", g_iOffMaxPlayers);
	g_dSQLite.Query(ShowTargetOffline, sQuery, iClient, DBPrio_High);
}

void BdGetInfoOffline(int iClient, int iId)
{
	char sQuery[224];
	FormatEx(sQuery, sizeof(sQuery), "SELECT `auth`, `ip`, `name` FROM `offline` WHERE `id` = '%i'", iId);
	g_dSQLite.Query(SQL_Callback_GetInfoOffline, sQuery, iClient, DBPrio_High);
#if DEBUG
	LogToFile(g_sLogFile, "GetInfoOffline:QUERY: %s", sQuery);
#endif
}

public void SQL_Callback_GetInfoOffline(Database db, DBResultSet dbRs, const char[] sError, any iClient)
{
	if (dbRs == null || sError[0])
	{
		LogToFile(g_sLogFile, "SQL_Callback_GetInfoOffline: %s", sError);
		PrintToChat2(iClient, "%T", "Failed to player", iClient);
	}

	if (dbRs.FetchRow())
	{
		dbRs.FetchString(0, g_sTarget[iClient][TSTEAMID], sizeof(g_sTarget[][]));
		dbRs.FetchString(1, g_sTarget[iClient][TIP], sizeof(g_sTarget[][]));
		dbRs.FetchString(2, g_sTarget[iClient][TNAME], sizeof(g_sTarget[][]));
		ShowTypeMenu(iClient);
	}
	else
		PrintToChat2(iClient, "%T", "Failed to player", iClient);
}
//------------------------------------------------------------------------------------------
void BdGetMuteType(int iClient, int iTarget, int iType)
{
	DataPack dPack = new DataPack();
	dPack.WriteCell(iClient);
	dPack.WriteCell(iTarget);
	dPack.WriteCell(iType);
	char sQuery[524];
	FormatEx(sQuery, sizeof(sQuery), "SELECT    type \
            FROM        %s_comms \
            WHERE       RemoveType IS NULL \
                        AND authid REGEXP '^STEAM_[0-9]:%s$' \
                        AND (length = '0' OR ends > UNIX_TIMESTAMP())", 
			g_sDatabasePrefix, g_sTarget[iClient][TSTEAMID][8]);

	g_dDatabase.Query(SQL_Callback_GetMuteType, sQuery, dPack, DBPrio_High);
#if DEBUG
	LogToFile(g_sLogFile, "GetMuteType:QUERY: %s", sQuery);
#endif
}

public void SQL_Callback_GetMuteType(Database db, DBResultSet dbRs, const char[] sError, any data)
{
	if (dbRs == null || sError[0])
		LogToFile(g_sLogFile, "SQL_Callback_GetMuteType: %s", sError);
	
	DataPack dPack = view_as<DataPack>(data);
	dPack.Reset();
	int iClient = dPack.ReadCell();
	int iTarget = dPack.ReadCell();
	int iType = dPack.ReadCell();

	if (dbRs.FetchRow())
		g_iTargetMuteType[iTarget] = dbRs.FetchInt(0);
	else
		g_iTargetMuteType[iTarget] = 0;

#if DEBUG
	LogToFile(g_sLogFile, "GetMuteType:%N: %d", iTarget, g_iTargetMuteType[iTarget]);
#endif

	if (iType)
		CreateDB(iClient, iTarget);
	else
		ShowTypeMuteMenu(iClient);
}

void BdDelMute(int iClient, int iTarget)
{
	DataPack dPack = new DataPack();
	dPack.WriteCell(iClient);
	dPack.WriteCell(iTarget);
	char sQuery[524];
	FormatEx(sQuery, sizeof(sQuery), "DELETE \
            FROM        %s_comms \
            WHERE       RemoveType IS NULL \
                        AND authid REGEXP '^STEAM_[0-9]:%s$' \
                        AND (length = '0' OR ends > UNIX_TIMESTAMP())", 
			g_sDatabasePrefix, g_sTarget[iClient][TSTEAMID][8]);
	g_dDatabase.Query(SQL_Callback_DelMute , sQuery, dPack, DBPrio_High);
#if DEBUG
	LogToFile(g_sLogFile, "BdDelMute:QUERY: %s", sQuery);
#endif
}

public void SQL_Callback_DelMute(Database db, DBResultSet dbRs, const char[] sError, any data)
{
	if (dbRs == null || sError[0])
		LogToFile(g_sLogFile, "SQL_Callback_DelMute: %s", sError);
#if DEBUG
	if (dbRs.RowCount)
		LogToFile(g_sLogFile, "BdDelMute:yes");
	else
		LogToFile(g_sLogFile, "BdDelMute:no");
#endif
	
	DataPack dPack = view_as<DataPack>(data);
	dPack.Reset();
	int iClient = dPack.ReadCell();
	int iTarget = dPack.ReadCell();

	CreateDB(iClient, iTarget);
}
//-----------------------------------------------------------------------------------------------------------------------------
void CheckBanInBd(int iClient, int iTarget, int iType, char[] sSteamIp)
{
	char sQuery[324];
	if (strncmp(sSteamIp, "STEAM_", 6) == 0)
		FormatEx(sQuery, sizeof(sQuery), "SELECT bid FROM %s_bans WHERE (type = 0 AND authid = '%s') AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", g_sDatabasePrefix, sSteamIp);
	else 
		FormatEx(sQuery, sizeof(sQuery), "SELECT bid FROM %s_bans WHERE (type = 1 AND ip     = '%s') AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", g_sDatabasePrefix, sSteamIp);

	DataPack dPack = new DataPack();
	if(iClient)
		dPack.WriteCell(GetClientUserId(iClient));
	else
		dPack.WriteCell(iClient);
	dPack.WriteCell(iTarget);
	dPack.WriteCell(iType);
	dPack.WriteString(sSteamIp);
#if DEBUG
	LogToFile(g_sLogFile, "Checking ban in bd: %s. QUERY: %s", sSteamIp, sQuery);
#endif
	g_dDatabase.Query(SQL_Callback_CheckBanInBd , sQuery, dPack, DBPrio_High);
}

public void SQL_Callback_CheckBanInBd(Database db, DBResultSet dbRs, const char[] sError, any data)
{
	DataPack dPack = view_as<DataPack>(data);
	dPack.Reset();
	int iClient = GetClientOfUserId(dPack.ReadCell());
	int iTarget = dPack.ReadCell();
	int iType = dPack.ReadCell();
	char sSteamIp[56];
	dPack.ReadString(sSteamIp, sizeof(sSteamIp));
	
	if (dbRs == null || sError[0])
	{
		LogToFile(g_sLogFile, "SQL_Callback_CheckBanInBd: %s", sError);
		CreateDB(iClient, iTarget, sSteamIp);
		return;
	}
	
	if(iType)
	{
		if (dbRs.RowCount)
		{
			if (iClient)
				PrintToChat2(iClient, "%T", "Is already banned", iClient, sSteamIp);
			else
				ReplyToCommand(iClient, "%s %s is already banned", PREFIX, sSteamIp);
		}
		else
			CreateDB(iClient, iTarget, sSteamIp);
	}
	else
	{
		if (!dbRs.RowCount)
		{
			if (iClient)
				PrintToChat2(iClient, "%T", "No active bans", iClient, sSteamIp);
			else
				ReplyToCommand(iClient, "%s No active bans found for that filter %s", PREFIX, sSteamIp);
		}
		else
		{
			if (dbRs.FetchRow())
				CreateDB(iClient, iTarget, sSteamIp);
		}
	}
}

void DoCreateDB(int iClient, int iTarget)
{
	if(g_iTargetType[iClient] > 2 && g_iTargetType[iClient] < 6)
	{
		if (g_iTarget[iClient][TTIME] == -1)
			BdDelMute(iClient, iTarget);
		else
			BdGetMuteType(iClient, iTarget, 1);
	}
	else
		CreateDB(iClient, iTarget);
}

//------------------------------------------------------------------------------------------------------------------------------
//занесение в бд
void CreateDB(int iClient, int iTarget, char[] sSteamIp = "")
{
#if DEBUG
	LogToFile(g_sLogFile,"Create sb: client %d, target %d, TargetType %d, TargetMuteType %d", iClient, iTarget, g_iTargetType[iClient], g_iTargetMuteType[iTarget]);
#endif
	if (g_iTargetType[iClient] > 7 && g_iTargetMuteType[iTarget] == 0 && iTarget && GetClientListeningFlags(iTarget) == VOICE_MUTED)
	{
		SetClientListeningFlags(iClient, VOICE_NORMAL);
		return;
	}
	else if (g_iTargetType[iClient] > 6 && g_iTargetMuteType[iTarget] == 0)
		return;
	
	char sBanName[MAX_NAME_LENGTH*2+1],
		 sQuery[1024],
		 sReason[256],
		 sLog[1024],
		 sLength[64],
		 sAdmin_SteamID[MAX_STEAMID_LENGTH],
		 sAdminIp[MAX_IP_LENGTH],
		 sAdminName[MAX_NAME_LENGTH];
		 
	int iTime;
	int iCreated = GetTime();
	
	if (iClient)
	{
		GetClientAuthId(iClient, TYPE_STEAM, sAdmin_SteamID, sizeof(sAdmin_SteamID));
		GetClientIP(iClient, sAdminIp, sizeof(sAdminIp));
		GetClientName(iClient, sAdminName, sizeof(sAdminName));
	}
	else
	{
		strcopy(sAdmin_SteamID, sizeof(sAdmin_SteamID), "STEAM_ID_SERVER");
		strcopy(sAdminIp, sizeof(sAdminIp), g_sServerIP);
		FormatEx(sAdminName, sizeof(sAdminName), "%T", "Server", iClient);
	}
	
	if (iTarget)
	{
		GetClientAuthId(iTarget, TYPE_STEAM, g_sTarget[iClient][TSTEAMID], sizeof(g_sTarget[][]));
		GetClientIP(iTarget, g_sTarget[iClient][TIP], sizeof(g_sTarget[][]));
		GetClientName(iTarget, g_sTarget[iClient][TNAME], sizeof(g_sTarget[][]));
	}

	if (g_iTarget[iClient][TTIME] == -1)
		iTime = g_iTarget[iClient][TTIME];
	else
		iTime = g_iTarget[iClient][TTIME]*60;
	
	if(!iTime)
		FormatEx(sLength, sizeof(sLength), "%T", "Permanent", iClient);
	else
		FormatVrema(iClient, iTime, sLength, sizeof(sLength));
	
	if (!g_sTarget[iClient][TREASON][0])
		FormatEx(g_sTarget[iClient][TREASON], sizeof(g_sTarget[][]), "%T", "No reason", iClient);

	g_dDatabase.Escape(g_sTarget[iClient][TREASON], sReason, sizeof(sReason));
	g_dDatabase.Escape(g_sTarget[iClient][TNAME], sBanName, sizeof(sBanName));
#if DEBUG
	LogToFile(g_sLogFile,"name do %s : posle %s", g_sTarget[iClient][TNAME], sBanName);
#endif

	switch(g_iTargetType[iClient])
	{
		case TYPE_ADDBAN:
		{
			if (iTarget)
			{
				if (strncmp(sSteamIp, "STEAM_", 6) == 0)
				{
					if(g_iServerID == -1) 
					{
						FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_bans (type, ip, authid, name, created, ends, length, reason, aid, adminIp, sid, country) \
														VALUES (0, '%s', '%s', '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
														IFNULL((SELECT aid FROM %s_admins WHERE authid = '%s' OR authid REGEXP '^STEAM_[0-9]:%s$'),'0'), '%s', \
														(SELECT sid FROM %s_servers WHERE ip = '%s' AND port = '%s' LIMIT 0,1), ' ')", 
						g_sDatabasePrefix, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], sBanName, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, sAdmin_SteamID[8], 
						sAdminIp, g_sDatabasePrefix, g_sServerIP, g_sServerPort);
					}
					else
					{
						FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_bans (type, ip, authid, name, created, ends, length, reason, aid, adminIp, sid, country) \
														VALUES (0, '%s', '%s', '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
														IFNULL((SELECT aid FROM %s_admins WHERE authid = '%s' OR authid REGEXP '^STEAM_[0-9]:%s$'),'0'), '%s', \
														%d, ' ')",
						g_sDatabasePrefix, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], sBanName, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, 
						sAdmin_SteamID[8], sAdminIp, g_iServerID);	
					}
				}
				else
				{
					if(g_iServerID == -1) 
					{
						FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_bans (type, ip, authid, name, created, ends, length, reason, aid, adminIp, sid, country) \
														VALUES (1, '%s', '%s', '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
														IFNULL((SELECT aid FROM %s_admins WHERE authid = '%s' OR authid REGEXP '^STEAM_[0-9]:%s$'),'0'), '%s', \
														(SELECT sid FROM %s_servers WHERE ip = '%s' AND port = '%s' LIMIT 0,1), ' ')", 
						g_sDatabasePrefix, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], sBanName, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, sAdmin_SteamID[8], 
						sAdminIp, g_sDatabasePrefix, g_sServerIP, g_sServerPort);
					}
					else
					{
						FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_bans (type, ip, authid, name, created, ends, length, reason, aid, adminIp, sid, country) \
														VALUES (1, '%s', '%s', '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
														IFNULL((SELECT aid FROM %s_admins WHERE authid = '%s' OR authid REGEXP '^STEAM_[0-9]:%s$'),'0'), '%s', \
														%d, ' ')",
						g_sDatabasePrefix, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], sBanName, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, 
						sAdmin_SteamID[8], sAdminIp, g_iServerID);	
					}
				}
				FireOnClientBanned(iClient, iTarget, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], g_sTarget[iClient][TNAME], g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
				ShowAdminAction(iClient, "%T", "Banned show", iClient, g_iTarget[iClient][TTIME], sLength, g_sTarget[iClient][TREASON]);
				CreateSayBanned(sAdminName, iTarget, iCreated, iTime, sLength, g_sTarget[iClient][TREASON]);
				FormatEx(sLog, sizeof(sLog), "\"%L\" add banned \"%s (%s IP_%s)\" (minutes \"%d\") (reason \"%s\")", iClient, g_sTarget[iClient][TNAME], g_sTarget[iClient][TSTEAMID], 
										g_sTarget[iClient][TIP], g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
			}
			else
			{
				if (strncmp(sSteamIp, "STEAM_", 6) == 0)
				{
					if(g_iServerID == -1) 
					{
						FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_bans (type, authid, created, ends, length, reason, aid, adminIp, sid, country) VALUES \
														(0, '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
														(SELECT `aid` FROM %s_admins WHERE `authid` = '%s' LIMIT 0,1), '%s', \
														(SELECT `sid` FROM %s_servers WHERE `ip` = '%s' AND `port` = '%s' LIMIT 0,1), ' ')", 
						g_sDatabasePrefix, sSteamIp, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, sAdminIp, g_sDatabasePrefix, g_sServerIP, g_sServerPort);
					}
					else
					{
						FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_bans (type, authid, created, ends, length, reason, aid, adminIp, sid, country) VALUES \
														(0, '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
														(SELECT `aid` FROM %s_admins WHERE `authid` = '%s' LIMIT 0,1), '%s', %d, ' ')", 
														g_sDatabasePrefix, sSteamIp, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, sAdminIp, g_iServerID);	
					}
					FireOnClientAddBanned(iClient, "", sSteamIp, g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
				}
				else
				{
					if(g_iServerID == -1) 
					{
						FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_bans (type, ip, created, ends, length, reason, aid, adminIp, sid, country) VALUES \
														(1, '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
														(SELECT `aid` FROM %s_admins WHERE `authid` = '%s' LIMIT 0,1), '%s', \
														(SELECT `sid` FROM %s_servers WHERE `ip` = '%s' AND `port` = '%s' LIMIT 0,1), ' ')", 
						g_sDatabasePrefix, sSteamIp, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, sAdminIp, g_sDatabasePrefix, g_sServerIP, g_sServerPort);
					}
					else
					{
						FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_bans (type, ip, created, ends, length, reason, aid, adminIp, sid, country) VALUES \
														(1, '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
														(SELECT `aid` FROM %s_admins WHERE `authid` = '%s' LIMIT 0,1), '%s', %d, ' ')", 
														g_sDatabasePrefix, sSteamIp, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, sAdminIp, g_iServerID);	
					}
					FireOnClientAddBanned(iClient, sSteamIp, "", g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
				}
				ShowAdminAction(iClient, "%T", "Banned show", iClient, sSteamIp, sLength, g_sTarget[iClient][TREASON]);
				FormatEx(sLog, sizeof(sLog), "\"%L\" add banned \"%s\" (minutes \"%d\") (reason \"%s\")", iClient, sSteamIp, g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
			}
		}
		case TYPE_UNBAN:
		{
			if (strncmp(sSteamIp, "STEAM_", 6) == 0)
			{
				FormatEx(sQuery, sizeof(sQuery), "UPDATE %s_bans SET RemovedBy = (SELECT aid FROM %s_admins WHERE authid = '%s' OR authid REGEXP '^STEAM_[0-9]:%s$'),\
											RemoveType = 'U', RemovedOn = UNIX_TIMESTAMP(), ureason = '%s' WHERE (type = 0 AND authid = '%s') AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
				g_sDatabasePrefix, g_sDatabasePrefix, sAdmin_SteamID, sAdmin_SteamID[8], sReason, sSteamIp);
				ServerCommand("removeid %s", sSteamIp);
				FireOnClientUnBanned(iClient, "", sSteamIp, g_sTarget[iClient][TREASON]);
			}
			else 
			{
				FormatEx(sQuery, sizeof(sQuery), "UPDATE %s_bans SET RemovedBy = (SELECT aid FROM %s_admins WHERE authid = '%s' OR authid REGEXP '^STEAM_[0-9]:%s$'),\
											RemoveType = 'U', RemovedOn = UNIX_TIMESTAMP(), ureason = '%s' WHERE (type = 1 AND ip     = '%s') AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
				g_sDatabasePrefix, g_sDatabasePrefix, sAdmin_SteamID, sAdmin_SteamID[8], sReason, sSteamIp);
				ServerCommand("removeip %s", sSteamIp);
				FireOnClientUnBanned(iClient, sSteamIp, "", g_sTarget[iClient][TREASON]);
			}
			
			ShowAdminAction(iClient, "%T", "UnBanned show", iClient, sSteamIp);
			FormatEx(sLog, sizeof(sLog), "\"%L\" unbanned \"%s\" (reason \"%s\")", iClient, sSteamIp, g_sTarget[iClient][TREASON]);
		}
		case TYPE_BAN:
		{
			if(g_iServerID == -1) 
			{
				FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_bans (ip, authid, name, created, ends, length, reason, aid, adminIp, sid, country) \
												VALUES ('%s', '%s', '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
												IFNULL((SELECT aid FROM %s_admins WHERE authid = '%s' OR authid REGEXP '^STEAM_[0-9]:%s$'),'0'), '%s', \
												(SELECT sid FROM %s_servers WHERE ip = '%s' AND port = '%s' LIMIT 0,1), ' ')", 
				g_sDatabasePrefix, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], sBanName, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, sAdmin_SteamID[8], 
				sAdminIp, g_sDatabasePrefix, g_sServerIP, g_sServerPort);
			}
			else
			{
				FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_bans (ip, authid, name, created, ends, length, reason, aid, adminIp, sid, country) \
												VALUES ('%s', '%s', '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
												IFNULL((SELECT aid FROM %s_admins WHERE authid = '%s' OR authid REGEXP '^STEAM_[0-9]:%s$'),'0'), '%s', \
												%d, ' ')",
				g_sDatabasePrefix, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], sBanName, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, 
				sAdmin_SteamID[8], sAdminIp, g_iServerID);	
			}
			FireOnClientBanned(iClient, iTarget, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], g_sTarget[iClient][TNAME], g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
			ShowAdminAction(iClient, "%T", "Banned show", iClient, g_sTarget[iClient][TNAME], sLength, g_sTarget[iClient][TREASON]);
			CreateSayBanned(sAdminName, iTarget, iCreated, iTime, sLength, g_sTarget[iClient][TREASON]);
			FormatEx(sLog, sizeof(sLog), "\"%L\" banned \"%s (%s IP_%s)\" (minutes \"%d\") (reason \"%s\")", iClient, g_sTarget[iClient][TNAME], g_sTarget[iClient][TSTEAMID], 
										g_sTarget[iClient][TIP], g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
		}
		case TYPE_GAG, TYPE_MUTE, TYPE_SILENCE:
		{
			int iType;
			bool bSetQ = true;
			switch(g_iTargetType[iClient])
			{
				case TYPE_GAG:
				{
					iType = TYPEGAG;
					if (g_iTargetMuteType[iTarget] == TYPEMUTE)
					{
						if(iTime == -1)
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     type = 3 , reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 1 AND authid = '%s' AND length = '-1' ORDER BY `bid` DESC LIMIT 1", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						else
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     type = 3 , reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 1 AND authid = '%s' AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						bSetQ = false;
					}
					else if (g_iTargetMuteType[iTarget] == TYPEGAG)
					{
						if(iTime == -1)
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 2 AND authid = '%s' AND length = '-1' ORDER BY `bid` DESC LIMIT 1", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						else
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 2 AND authid = '%s' AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						bSetQ = false;
					}
					if (iTarget)
					{
						AddGag(iTarget, iTime);
						PrintToChat2(iTarget, "%T", "Target gag", iTarget, sLength, g_sTarget[iClient][TREASON]);
					}
					FireOnClientMuted(iClient, iTarget, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], g_sTarget[iClient][TNAME], TYPEGAG, g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
					ShowAdminAction(iClient, "%T", "Gag show", iClient, g_sTarget[iClient][TNAME], sLength, g_sTarget[iClient][TREASON]);
					FormatEx(sLog, sizeof(sLog), "\"%L\" gag \"%s (%s IP_%s)\" (minutes \"%d\") (reason \"%s\")", iClient, g_sTarget[iClient][TNAME], g_sTarget[iClient][TSTEAMID], 
										g_sTarget[iClient][TIP], g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
				}
				case TYPE_MUTE:
				{
					iType = TYPEMUTE;
					if (g_iTargetMuteType[iTarget] == TYPEGAG)
					{
						if(iTime == -1)
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     type = 3 , reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 2 AND authid = '%s' AND length = '-1' ORDER BY `bid` DESC LIMIT 1", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						else
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     type = 3 , reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 2 AND authid = '%s' AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						bSetQ = false;
					}
					else if (g_iTargetMuteType[iTarget] == TYPEMUTE)
					{
						if(iTime == -1)
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 1 AND authid = '%s' AND length = '-1' ORDER BY `bid` DESC LIMIT 1", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						else
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 1 AND authid = '%s' AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						bSetQ = false;
					}
					if (iTarget)
					{
						AddMute(iTarget, iTime);
						PrintToChat2(iTarget, "%T", "Target mute", iTarget, sLength, g_sTarget[iClient][TREASON]);
					}
					FireOnClientMuted(iClient, iTarget, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], g_sTarget[iClient][TNAME], TYPEMUTE, g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
					ShowAdminAction(iClient, "%T", "Mute show", iClient, g_sTarget[iClient][TNAME], sLength, g_sTarget[iClient][TREASON]);
					FormatEx(sLog, sizeof(sLog), "\"%L\" mute \"%s (%s IP_%s)\" (minutes \"%d\") (reason \"%s\")", iClient, g_sTarget[iClient][TNAME], g_sTarget[iClient][TSTEAMID], 
										g_sTarget[iClient][TIP], g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
				}
				case TYPE_SILENCE:
				{
					iType = TYPESILENCE;
					if (g_iTargetMuteType[iTarget] == TYPEGAG)
					{
						if(iTime == -1)
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     type = 3 , reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 2 AND authid = '%s' AND length = '-1' ORDER BY `bid` DESC LIMIT 1", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						else
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     type = 3, reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 2 AND authid = '%s' AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						bSetQ = false;
					}
					else if (g_iTargetMuteType[iTarget] == TYPEMUTE)
					{
						if(iTime == -1)
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     type = 3 , reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 1 AND authid = '%s' AND length = '-1' ORDER BY `bid` DESC LIMIT 1", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						else
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     type = 3, reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 1 AND authid = '%s' AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						bSetQ = false;
					}
					else if (g_iTargetMuteType[iTarget] == TYPESILENCE)
					{
						if(iTime == -1)
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 3 AND authid = '%s' AND length = '-1' ORDER BY `bid` DESC LIMIT 1", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						else
						{
							FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
								SET     reason = '%s', created = UNIX_TIMESTAMP(), ends = UNIX_TIMESTAMP() + %d, length = %d \
								WHERE   type = 3 AND authid = '%s' AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
								g_sDatabasePrefix, sReason, iTime, iTime, g_sTarget[iClient][TSTEAMID]);
						}
						bSetQ = false;
					}
					if (iTarget)
					{
						AddSilence(iTarget, iTime);
						PrintToChat2(iTarget, "%T", "Target silence", iTarget, sLength, g_sTarget[iClient][TREASON]);
					}
					FireOnClientMuted(iClient, iTarget, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], g_sTarget[iClient][TNAME], TYPESILENCE, g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
					ShowAdminAction(iClient, "%T", "Silence show", iClient, g_sTarget[iClient][TNAME], sLength, g_sTarget[iClient][TREASON]);
					FormatEx(sLog, sizeof(sLog), "\"%L\" silence \"%s (%s IP_%s)\" (minutes \"%d\") (reason \"%s\")", iClient, g_sTarget[iClient][TNAME], g_sTarget[iClient][TSTEAMID], 
										g_sTarget[iClient][TIP], g_iTarget[iClient][TTIME], g_sTarget[iClient][TREASON]);
				}
			}
			if (iTarget)
			{
				g_iTargenMuteTime[iTarget] = iCreated + iTime;
				strcopy(g_iTargetMuteReason[iTarget], sizeof(g_iTargetMuteReason[]), g_sTarget[iClient][TREASON]);
			}
			if(bSetQ)
			{
				if(g_iServerID == -1)
				{
					FormatEx(sQuery, sizeof(sQuery), "INSERT INTO     %s_comms (authid, name, created, ends, length, reason, aid, adminIp, sid, type) \
													VALUES         ('%s', '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
													IFNULL((SELECT aid FROM %s_admins WHERE authid = '%s' OR authid REGEXP '^STEAM_[0-9]:%s$'), '0'), '%s', \
													(SELECT sid FROM %s_servers WHERE ip = '%s' AND port = '%s' LIMIT 0,1), %d)", 
					g_sDatabasePrefix, g_sTarget[iClient][TSTEAMID], sBanName, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, sAdmin_SteamID[8], sAdminIp, g_sDatabasePrefix, 
					g_sServerIP, g_sServerPort, iType);
				}
				else
				{
					FormatEx(sQuery, sizeof(sQuery), "INSERT INTO     %s_comms (authid, name, created, ends, length, reason, aid, adminIp, sid, type) \
													VALUES         ('%s', '%s', UNIX_TIMESTAMP(), UNIX_TIMESTAMP() + %d, %d, '%s', \
													IFNULL((SELECT aid FROM %s_admins WHERE authid = '%s' OR authid REGEXP '^STEAM_[0-9]:%s$'), '0'), '%s', %d, %d)", 
					g_sDatabasePrefix, g_sTarget[iClient][TSTEAMID], sBanName, iTime, iTime, sReason, g_sDatabasePrefix, sAdmin_SteamID, sAdmin_SteamID[8], sAdminIp, g_iServerID, iType);
				}
			}
		}
		case TYPE_UNGAG, TYPE_UNMUTE, TYPE_UNSILENCE:
		{
			int iType;
			bool bSetQ = true;
			switch(g_iTargetType[iClient])
			{
				case TYPE_UNGAG:
				{
					iType = TYPEGAG;
					if (g_iTargetMuteType[iTarget] == TYPESILENCE)
					{
						FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
							SET     type = 1 \
							WHERE   type = 3 AND authid = '%s' AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
							g_sDatabasePrefix, g_sTarget[iClient][TSTEAMID]);
						bSetQ = false;
					}
					if (iTarget)
					{
						UnGag(iTarget);
						PrintToChat2(iTarget, "%T", "Target ungag", iTarget);
					}
					FireOnClientUnMuted(iClient, iTarget, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], g_sTarget[iClient][TNAME], TYPEGAG, g_sTarget[iClient][TREASON]);
					ShowAdminAction(iClient, "%T", "UnGag show", iClient, g_sTarget[iClient][TNAME]);
					FormatEx(sLog, sizeof(sLog), "\"%L\" un gag \"%s (%s IP_%s)\" (reason \"%s\")", iClient, g_sTarget[iClient][TNAME], g_sTarget[iClient][TSTEAMID], 
										g_sTarget[iClient][TIP], g_sTarget[iClient][TREASON]);
				}
				case TYPE_UNMUTE:
				{
					iType = TYPEMUTE;
					if (g_iTargetMuteType[iTarget] == TYPESILENCE)
					{
						FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
							SET     type = 2 \
							WHERE   type = 3 AND authid = '%s' AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
							g_sDatabasePrefix, g_sTarget[iClient][TSTEAMID]);
						bSetQ = false;
					}
					if (iTarget)
					{
						UnMute(iTarget);
						PrintToChat2(iTarget, "%T", "Target unmute", iTarget);
					}
					FireOnClientUnMuted(iClient, iTarget, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], g_sTarget[iClient][TNAME], TYPEMUTE, g_sTarget[iClient][TREASON]);
					ShowAdminAction(iClient, "%T", "UnMute show", iClient, g_sTarget[iClient][TNAME]);
					FormatEx(sLog, sizeof(sLog), "\"%L\" un mute \"%s (%s IP_%s)\" (reason \"%s\")", iClient, g_sTarget[iClient][TNAME], g_sTarget[iClient][TSTEAMID], 
										g_sTarget[iClient][TIP], g_sTarget[iClient][TREASON]);
				}
				case TYPE_UNSILENCE:
				{
					iType = TYPESILENCE;
					if (iTarget)
					{
						UnSilence(iTarget);
						PrintToChat2(iTarget, "%T", "Target unsilence", iTarget);
					}
					FireOnClientUnMuted(iClient, iTarget, g_sTarget[iClient][TIP], g_sTarget[iClient][TSTEAMID], g_sTarget[iClient][TNAME], TYPESILENCE, g_sTarget[iClient][TREASON]);
					ShowAdminAction(iClient, "%T", "UnSilence show", iClient, g_sTarget[iClient][TNAME]);
					FormatEx(sLog, sizeof(sLog), "\"%L\" un silence \"%s (%s IP_%s)\" (reason \"%s\")", iClient, g_sTarget[iClient][TNAME], g_sTarget[iClient][TSTEAMID], 
										g_sTarget[iClient][TIP], g_sTarget[iClient][TREASON]);
				}
			}
			if(bSetQ)
			{
				FormatEx(sQuery, sizeof(sQuery), "UPDATE  %s_comms \
						SET     RemovedBy = (SELECT aid FROM %s_admins WHERE authid = '%s' OR authid REGEXP '^STEAM_[0-9]:%s$'), RemoveType = 'U', RemovedOn = UNIX_TIMESTAMP(), ureason = '%s' \
						WHERE   type = %d AND authid = '%s' AND (length = '0' OR ends > UNIX_TIMESTAMP()) AND RemoveType IS NULL", 
						g_sDatabasePrefix, g_sDatabasePrefix, sAdmin_SteamID, sAdmin_SteamID[8], sReason, iType, g_sTarget[iClient][TSTEAMID]);
			}
		}
	}
	
	DataPack dPack = new DataPack();
	if(iClient)
		dPack.WriteCell(GetClientUserId(iClient));
	else
		dPack.WriteCell(iClient);
	if(sSteamIp[0])
		dPack.WriteString(sSteamIp);
	else
		dPack.WriteString(g_sTarget[iClient][TNAME]);
	dPack.WriteString(sQuery);

	g_dDatabase.SetCharset("utf8");
	g_dDatabase.Query(VerifyInsert, sQuery, dPack, DBPrio_High);
#if DEBUG
	LogToFile(g_sLogFile,"create sb: %s", sQuery);
#endif
	LogAction(iClient, -1, sLog);
}

//ответ занисения в бд(прошёл или нет)
public void VerifyInsert(Database db, DBResultSet dbRs, const char[] sError, any data)
{
	DataPack dPack = view_as<DataPack>(data);
	dPack.Reset();
	int iClient = GetClientOfUserId(dPack.ReadCell());
	char sTargetName[MAX_NAME_LENGTH];
	dPack.ReadString(sTargetName, sizeof(sTargetName));

	
	if (dbRs == null || sError[0])
	{
		LogToFile(g_sLogFile, "Verify Insert Query Failed: %s", sError);
		char sQuery[1024];
		dPack.ReadString(sQuery, sizeof(sQuery));
		BekapStart(sQuery);

		if (iClient)
			PrintToChat2(iClient, "%T", "Failed to bd", iClient, sTargetName);
		else
			ReplyToCommand(iClient, "%s Failed to add to the database %s", PREFIX, sTargetName);
	}
	else
	{
		if (iClient)
			PrintToChat2(iClient, "%T", "Added to bd", iClient, sTargetName);
		else
			ReplyToCommand(iClient, "%s Added to the database %s", PREFIX, sTargetName);
	}
	
	delete dPack;
}
//------------------------------------------------------------------------------------------------------------------------------
//проверка игрока на бан
void CheckClientBan(int iClient)
{
	char sSteamID[MAX_STEAMID_LENGTH];
	GetClientAuthId(iClient, TYPE_STEAM, sSteamID, sizeof(sSteamID));
	
	if (sSteamID[0] == 'B' || sSteamID[9] == 'L' || g_dDatabase == null)
		return;
	
	char sQuery[1204],
		sIp[30];
	GetClientIP(iClient, sIp, sizeof(sIp));
	
	FormatEx(sQuery, sizeof(sQuery), "SELECT a.bid, a.length, a.created, a.reason, b.user FROM %s_bans a LEFT JOIN %s_admins b ON a.aid=b.aid \
				WHERE ((a.type = 0 AND a.authid REGEXP '^STEAM_[0-9]:%s$') OR (a.type = 1 AND a.ip = '%s')) \
				AND (a.length = '0' OR a.ends > UNIX_TIMESTAMP()) AND a.RemoveType IS NULL", g_sDatabasePrefix, g_sDatabasePrefix, sSteamID[8], sIp);
#if DEBUG
	LogToFile(g_sLogFile, "Checking ban for: %s. QUERY: %s", sSteamID, sQuery);
#endif
	
	g_dDatabase.SetCharset("utf8");
	g_dDatabase.Query(VerifyBan, sQuery, GetClientUserId(iClient), DBPrio_High);
}

public void VerifyBan(Database db, DBResultSet dbRs, const char[] sError, any iUserId)
{
	if (dbRs == null || sError[0])
	{
		LogToFile(g_sLogFile, "Verify Ban Query Failed: %s", sError);
		return;
	}
	
	char sSteamID[MAX_STEAMID_LENGTH];
	int iClient = GetClientOfUserId(iUserId);
	
	if (!iClient)
		return;

	GetClientAuthId(iClient, TYPE_STEAM, sSteamID, sizeof(sSteamID));
	if (dbRs.FetchRow())
	{
		char sReason[256],
			sLength[64],
			sCreated[128],
			sEnds[128],
			sName[MAX_NAME_LENGTH],
			sEName[MAX_NAME_LENGTH*2+1],
			sIP[MAX_IP_LENGTH],
			sQuery[512];
		int iLength = dbRs.FetchInt(1);
		int iCreated = dbRs.FetchInt(2);
		dbRs.FetchString(3, sReason, sizeof(sReason));

		if(!iLength)
		{
			FormatEx(sLength, sizeof(sLength), "%T", "Permanent", iClient);
			if(g_bBanSayPanel)
				FormatEx(sEnds, sizeof(sEnds), "%T", "No ends", iClient);
		}
		else
		{
			FormatVrema(iClient, iLength, sLength, sizeof(sLength));
			if(g_bBanSayPanel)
				FormatTime(sEnds, sizeof(sEnds), FORMAT_TIME, iCreated + iLength);
		}
		
		FormatTime(sCreated, sizeof(sCreated), FORMAT_TIME, iCreated);	
		GetClientIP(iClient, sIP, sizeof(sIP));
		GetClientName(iClient, sName, sizeof(sName));
		g_dDatabase.Escape(sName, sEName, sizeof(sEName));

		if (g_iServerID == -1)
		{
			FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_banlog (sid ,time ,name ,bid) VALUES  \
												((SELECT sid FROM %s_servers WHERE ip = '%s' AND port = '%s' LIMIT 0,1), UNIX_TIMESTAMP(), '%s', \
												(SELECT bid FROM %s_bans WHERE ((type = 0 AND authid REGEXP '^STEAM_[0-9]:%s$') OR (type = 1 AND ip = '%s')) AND RemoveType IS NULL LIMIT 0,1))", 
												g_sDatabasePrefix, g_sDatabasePrefix, g_sServerIP, g_sServerPort, sEName, g_sDatabasePrefix, sSteamID[8], sIP);
		}
		else
		{
			FormatEx(sQuery, sizeof(sQuery), "INSERT INTO %s_banlog (sid ,time ,name ,bid) VALUES  \
												(%d, UNIX_TIMESTAMP(), '%s', \
												(SELECT bid FROM %s_bans WHERE ((type = 0 AND authid REGEXP '^STEAM_[0-9]:%s$') OR (type = 1 AND ip = '%s')) AND RemoveType IS NULL LIMIT 0,1))", 
												g_sDatabasePrefix, g_iServerID, sEName, g_sDatabasePrefix, sSteamID[8], sIP);
		}
	#if DEBUG
		LogToFile(g_sLogFile, "Ban log: QUERY: %s", sQuery);
	#endif
		g_dDatabase.SetCharset("utf8");
		g_dDatabase.Query(SQL_Callback_BanLog, sQuery, _, DBPrio_High);

		g_bBanClientConnect[iClient] = true;
		
		if (!dbRs.IsFieldNull(0))
		{
			char sAdmin[64];
			dbRs.FetchString(4, sAdmin, sizeof(sAdmin));
			if(g_bBanSayPanel)
				CreateTeaxtDialog(iClient, "%T", "Banned Admin panel", iClient, sAdmin, sReason, sCreated, sEnds, sLength, g_sWebsite);
			else
				KickClient(iClient, "%T", "Banned Admin", iClient, sAdmin, sReason, sCreated, sLength, g_sWebsite);
		}
		else
		{
			if(g_bBanSayPanel)
				CreateTeaxtDialog(iClient, "%T", "Banned panel", iClient, sReason, sCreated, sEnds, sLength, g_sWebsite);
			else
				KickClient(iClient, "%T", "Banned", iClient, sReason, sCreated, sLength, g_sWebsite);
		}
		ServerCommand("banid 5 %s", sSteamID);
	}
	else
	{
		g_bBanClientConnect[iClient] = false;
		CheckClientMute(iClient, sSteamID);
		
		AdminId idAdmin = GetUserAdmin(iClient);
		if(idAdmin != INVALID_ADMIN_ID)
		{
			int iExpire = GetAdminExpire(idAdmin);
			if(iExpire > GetTime())
			{
				DataPack dPack = new DataPack();
				dPack.WriteCell(GetClientUserId(iClient));
				dPack.WriteCell(iExpire);
				CreateTimer(15.0, TimerAdminExpire, dPack);
			}
			else
				RemoveAdmin(idAdmin);
		}
		else
			DelOflineInfo(sSteamID);	
	}
}

public void SQL_Callback_BanLog(Database db, DBResultSet dbRs, const char[] sError, any data)
{
	if(sError[0])
		LogToFile(g_sLogFile, "SQL_Callback_BanLog Query Failed: %s", sError);
}

//проверка игрока на мут
void CheckClientMute(int iClient, char[] sSteamID)
{
	char sQuery[1204];
	FormatEx(sQuery, sizeof(sQuery), 
			"SELECT    (ends - UNIX_TIMESTAMP()), type, ends, reason \
            FROM        %s_comms \
            WHERE       RemoveType IS NULL \
                        AND authid REGEXP '^STEAM_[0-9]:%s$' \
                        AND (length = '0' OR ends > UNIX_TIMESTAMP())", 
			g_sDatabasePrefix, sSteamID[8]);
#if DEBUG
	LogToFile(g_sLogFile, "Check Mute: %s. QUERY: %s", sSteamID, sQuery);
#endif
	g_dDatabase.Query(VerifyMute, sQuery, GetClientUserId(iClient), DBPrio_High);
}

public void VerifyMute(Database db, DBResultSet dbRs, const char[] sError, any iUserId)
{
	if (dbRs == null || sError[0])
	{
		LogToFile(g_sLogFile, "Verify Mute failed: %s", sError);
		return;
	}

	int iClient = GetClientOfUserId(iUserId);
	
	if (!iClient)
		return;

	if (dbRs.RowCount)
	{
		while (dbRs.FetchRow())
		{
			int iTime = dbRs.FetchInt(0);
			int iType = dbRs.FetchInt(1);
			g_iTargenMuteTime[iClient] = dbRs.FetchInt(2);
			dbRs.FetchString(3, g_iTargetMuteReason[iClient], sizeof(g_iTargetMuteReason[]));
			
		#if DEBUG
			LogToFile(g_sLogFile, "Fetched from DB: time %d, type %d", iTime, iType);
		#endif
			
			g_iTargetMuteType[iClient] = iType;
			
			if(iTime < 0)
				iTime = 0;
		
			switch (iType)
			{
				case TYPEMUTE:		AddMute(iClient, iTime);
				case TYPEGAG: 		AddGag(iClient, iTime);
				case TYPESILENCE:	AddSilence(iClient, iTime);
			}
		}
	}
	else
	{
	#if DEBUG
		LogToFile(g_sLogFile, "Fetched from DB: set %N type 0", iClient);
	#endif
		g_iTargetMuteType[iClient] = 0;
	}
}

//-----------------------------------------------------------------------------------------------------------------------------
// работа с админами
void AdminHash()
{
	g_aAdminsExpired.Clear();
	char sQuery[204];
	FormatEx(sQuery, sizeof(sQuery), "SELECT type, name, flags FROM %s_overrides", g_sDatabasePrefix);
	g_dDatabase.SetCharset("utf8");
	g_dDatabase.Query(OverridesDone, sQuery);
}

public void OverridesDone(Database db, DBResultSet dbRs, const char[] sError, any iData)
{
	if (dbRs == null || sError[0])
		LogToFile(g_sLogFile, "Failed to retrieve overrides from the database, %s", sError);
	else
	{
		KeyValues kvOverrides = new KeyValues("SB_Overrides");
		
		char sFlags[32], 
			sName[64],
			sType[64];
		while (dbRs.FetchRow())
		{
			dbRs.FetchString(0, sType, sizeof(sType));
			dbRs.FetchString(1, sName, sizeof(sName));
			dbRs.FetchString(2, sFlags, sizeof(sFlags));
			
			// KeyValuesToFile won't add that key, if the value is ""..
			if (sFlags[0] == '\0')
			{
				sFlags[0] = ' ';
				sFlags[1] = '\0';
			}
			
		#if DEBUG
			LogToFile(g_sLogFile, "Adding override (%s, %s, %s)", sType, sName, sFlags);
		#endif
			
			if (StrEqual(sType, "command"))
			{
				kvOverrides.JumpToKey("override_commands", true);
				kvOverrides.SetString(sName, sFlags);
				kvOverrides.GoBack();
			}
			else if (StrEqual(sType, "group"))
			{
				kvOverrides.JumpToKey("override_groups", true);
				kvOverrides.SetString(sName, sFlags);
				kvOverrides.GoBack();
			}
		}
		
		kvOverrides.Rewind();
		kvOverrides.ExportToFile(g_sOverridesLoc);
		delete kvOverrides;
	}
	
	ReadOverrides();
	
	char sQuery[254];
	FormatEx(sQuery, sizeof(sQuery), "SELECT name, flags, immunity   \
					FROM %s_srvgroups ORDER BY id", g_sDatabasePrefix);
	g_dDatabase.SetCharset("utf8");
	g_dDatabase.Query(GroupsDone, sQuery);
}

public void GroupsDone(Database db, DBResultSet dbRs, const char[] sError, any iData)
{
	if (dbRs == null || sError[0])
		LogToFile(g_sLogFile, "Failed to retrieve groups from the database, %s", sError);
	else
	{
		char sGrpName[128], 
			sGrpFlags[32];
		int iImmunity;
	#if DEBUG
		int	iGrpCount = 0;
	#endif
		KeyValues kvGroups = new KeyValues("groups");

		while (dbRs.MoreRows)
		{
			dbRs.FetchRow();
			if (dbRs.IsFieldNull(0))
				continue; // Sometimes some rows return NULL due to some setups
			dbRs.FetchString(0, sGrpName, sizeof(sGrpName));
			dbRs.FetchString(1, sGrpFlags, sizeof(sGrpFlags));
			iImmunity = dbRs.FetchInt(2);
			
			TrimString(sGrpName);
			TrimString(sGrpFlags);
			
			// Ignore empty rows..
			if (!sGrpName[0])
				continue;
			
			kvGroups.JumpToKey(sGrpName, true);
			if (sGrpFlags[0])
				kvGroups.SetString("flags", sGrpFlags);
			if (iImmunity)
				kvGroups.SetNum("immunity", iImmunity);
				
			kvGroups.Rewind();
			
		#if DEBUG
			LogToFile(g_sLogFile, "Add %s Group", sGrpName);
			iGrpCount++;
		#endif
		}
		
		kvGroups.ExportToFile(g_sGroupsLoc);
		delete kvGroups;
		
	#if DEBUG
		LogToFile(g_sLogFile, "Finished loading %i groups.", iGrpCount);
	#endif
	}
	
	// Load the group overrides
	char sQuery[512];
	FormatEx(sQuery, sizeof(sQuery), "SELECT sg.name, so.type, so.name, so.access FROM %s_srvgroups_overrides so LEFT JOIN %s_srvgroups sg ON sg.id = so.group_id ORDER BY sg.id", g_sDatabasePrefix, g_sDatabasePrefix);
	g_dDatabase.Query(LoadGroupsOverrides, sQuery);
}

public void LoadGroupsOverrides(Database db, DBResultSet dbRs, const char[] sError, any iData)
{
	if (dbRs == null || sError[0])
		LogToFile(g_sLogFile, "Failed to retrieve group overrides from the database, %s", sError);
	else
	{
		char sGroupName[128],
			sType[16],
			sCommand[64],
			sAllowed[16];
		OverrideType iType;
		
		KeyValues kvGroups = new KeyValues("groups");
		kvGroups.ImportFromFile(g_sGroupsLoc);
		
		GroupId idGroup = INVALID_GROUP_ID;
		while (dbRs.MoreRows)
		{
			dbRs.FetchRow();
			if (dbRs.IsFieldNull(0))
				continue; // Sometimes some rows return NULL due to some setups
			
			dbRs.FetchString(0, sGroupName, sizeof(sGroupName));
			TrimString(sGroupName);
			if (!sGroupName[0])
				continue;
			
			dbRs.FetchString(1, sType, sizeof(sType));
			dbRs.FetchString(2, sCommand, sizeof(sCommand));
			dbRs.FetchString(3, sAllowed, sizeof(sAllowed));
			
			idGroup = FindAdmGroup(sGroupName);
			if (idGroup == INVALID_GROUP_ID)
				continue;

			iType = StrEqual(sType, "group") ? Override_CommandGroup : Override_Command;
			
		#if DEBUG
			LogToFile(g_sLogFile, "AddAdmGroupCmdOverride(%i, %s, %i)", idGroup, sCommand, iType);
		#endif
			
			// Save overrides into admin_groups.cfg backup
			if (kvGroups.JumpToKey(sGroupName))
			{
				kvGroups.JumpToKey("Overrides", true);
				if (iType == Override_Command)
					kvGroups.SetString(sCommand, sAllowed);
				else
				{
					Format(sCommand, sizeof(sCommand), "@%s", sCommand);
					kvGroups.SetString(sCommand, sAllowed);
				}
				kvGroups.Rewind();
			}
		}
		
		kvGroups.ExportToFile(g_sGroupsLoc);
		delete kvGroups;
	}
	
	ReadGroups();
	
	char sQuery[1204];
	if (g_iServerID == -1)
	{
		FormatEx(sQuery, sizeof(sQuery), "SELECT authid, srv_password, (SELECT name FROM %s_srvgroups WHERE name = srv_group AND flags != '') AS srv_group, srv_flags, user, immunity, expired  \
						FROM %s_admins_servers_groups AS asg \
						LEFT JOIN %s_admins AS a ON a.aid = asg.admin_id \
						WHERE (expired > UNIX_TIMESTAMP() OR expired = 0 OR expired = NULL) AND \
						((server_id = (SELECT sid FROM %s_servers WHERE ip = '%s' AND port = '%s' LIMIT 0,1)  \
						OR srv_group_id = ANY (SELECT group_id FROM %s_servers_groups WHERE server_id = (SELECT sid FROM %s_servers WHERE ip = '%s' AND port = '%s' LIMIT 0,1)))) \
						GROUP BY aid, authid, srv_password, srv_group, srv_flags, user", 
				g_sDatabasePrefix, g_sDatabasePrefix, g_sDatabasePrefix, g_sDatabasePrefix, g_sServerIP, g_sServerPort, g_sDatabasePrefix, g_sDatabasePrefix, g_sServerIP, g_sServerPort);
	} 
	else 
	{
		FormatEx(sQuery, sizeof(sQuery), "SELECT authid, srv_password, (SELECT name FROM %s_srvgroups WHERE name = srv_group AND flags != '') AS srv_group, srv_flags, user, immunity, expired  \
						FROM %s_admins_servers_groups AS asg \
						LEFT JOIN %s_admins AS a ON a.aid = asg.admin_id \
						WHERE (expired > UNIX_TIMESTAMP() OR expired = 0 OR expired = NULL) AND \
						(server_id = %d OR srv_group_id = ANY (SELECT group_id FROM %s_servers_groups WHERE server_id = %d)) \
						GROUP BY aid, authid, srv_password, srv_group, srv_flags, user", 
				g_sDatabasePrefix, g_sDatabasePrefix, g_sDatabasePrefix, g_iServerID, g_sDatabasePrefix, g_iServerID);
	}
	g_aAdminsExpired.Clear();
	g_dDatabase.SetCharset("utf8");
	g_dDatabase.Query(AdminsDone, sQuery);
}

public void AdminsDone(Database db, DBResultSet dbRs, const char[] sError, any iData)
{
	//SELECT authid, srv_password , srv_group, srv_flags, user
	if (dbRs == null || sError[0])
		LogToFile(g_sLogFile, "Failed to retrieve admins from the database, %s", sError);
	else
	{
		char sAuthType[] = "steam",
			sIdentity[66],
			sPassword[66],
			sGroups[256],
			sFlags[32],
			sName[66];
	#if DEBUG
		int iAdmCount = 0;
	#endif
		int iImmunity = 0,
			iExpire = 0;
		KeyValues kvAdmins = new KeyValues("admins");
		
		while (dbRs.MoreRows)
		{
			dbRs.FetchRow();
			if (dbRs.IsFieldNull(0))
				continue; // Sometimes some rows return NULL due to some setups
			
			dbRs.FetchString(0, sIdentity, sizeof(sIdentity));
			dbRs.FetchString(1, sPassword, sizeof(sPassword));
			dbRs.FetchString(2, sGroups, sizeof(sGroups));
			dbRs.FetchString(3, sFlags, sizeof(sFlags));
			dbRs.FetchString(4, sName, sizeof(sName));
			
			iImmunity = dbRs.FetchInt(5);
			iExpire = dbRs.FetchInt(6);
			
			TrimString(sName);
			TrimString(sIdentity);
			TrimString(sGroups);
			TrimString(sFlags);
			
			kvAdmins.JumpToKey(sName, true);
				
			kvAdmins.SetString("auth", sAuthType);
			kvAdmins.SetString("identity", sIdentity);
				
			if (sFlags[0])
				kvAdmins.SetString("flags", sFlags);
				
			if (sGroups[0])
				kvAdmins.SetString("group", sGroups);
				
			if (sPassword[0])
				kvAdmins.SetString("password", sPassword);
				
			if (iImmunity)
				kvAdmins.SetNum("immunity", iImmunity);
			
			if (iExpire)
				kvAdmins.SetNum("expire", iExpire);
				
			kvAdmins.Rewind();
			
		#if DEBUG
			LogToFile(g_sLogFile, "Add %s (%s) admin", sName, sIdentity);
			++iAdmCount;
		#endif
		}
		
		kvAdmins.ExportToFile(g_sAdminsLoc);
		delete kvAdmins;
	#if DEBUG
		LogToFile(g_sLogFile, "Finished loading %i admins.", iAdmCount);
	#endif
	}
	
	ReadUsers();
}
//-------------------------------------------------------------------------------------------------------------
// бекап бд
void BekapStart(char[] sQuery)
{
	char sQuerys[2524],
		sEQuery[2124];
	g_dSQLite.Escape(sQuery, sEQuery, sizeof(sEQuery));
	FormatEx(sQuerys, sizeof(sQuerys), "INSERT INTO `bekap` (query) VALUES ('%s')", sEQuery);
	g_dSQLite.Query(SQL_Callback_AddQueryBekap, sQuerys);
	
#if DEBUG
	LogToFile(g_sLogFile, "BekapStart:QUERY: %s", sQuery);
#endif
	
	if (g_hTimerBekap == null)
		g_hTimerBekap = CreateTimer(g_fRetryTime, TimerBekap, _, TIMER_REPEAT|TIMER_FLAG_NO_MAPCHANGE);
}

public void SQL_Callback_AddQueryBekap(Database db, DBResultSet dbRs, const char[] sError, any iData)
{
	if (dbRs == null || sError[0])
		LogToFile(g_sLogFile, "SQL_Callback_AddQueryBekap: %s", sError);
}

void SentBekapInBd()
{
	char sQuery[1024];
	FormatEx(sQuery, sizeof(sQuery), "SELECT `id`, `query` FROM `bekap`");
	g_dSQLite.Query(SQL_Callback_QueryBekap, sQuery);
}

public void SQL_Callback_QueryBekap(Database db, DBResultSet dbRs, const char[] sError, any iData)
{
	if (dbRs == null || sError[0])
		LogToFile(g_sLogFile, "SQL_Callback_QueryBekap: %s", sError);

	if (dbRs.RowCount)
	{
		char sQuery[1024];
		int iId;
		while(dbRs.FetchRow())
		{
			iId = dbRs.FetchInt(0);
			dbRs.FetchString(1, sQuery, sizeof(sQuery));
		#if DEBUG
			LogToFile(g_sLogFile, "QueryBekap:QUERY: %s", sQuery);
		#endif
			g_dDatabase.SetCharset("utf8");
			g_dDatabase.Query(CheckCallbackBekap, sQuery, iId, DBPrio_High);
		}
	}
}

public void CheckCallbackBekap(Database db, DBResultSet dbRs, const char[] sError, any iId)
{
	if (dbRs == null || sError[0])
	{
		if (g_hTimerBekap == null)
			g_hTimerBekap = CreateTimer(g_fRetryTime, TimerBekap, _, TIMER_REPEAT|TIMER_FLAG_NO_MAPCHANGE);
		LogToFile(g_sLogFile, "CheckCallbackBekap: %s", sError);
	}
	else
	{
		char sQuery[256];
		FormatEx(sQuery, sizeof(sQuery), "DELETE FROM `bekap` WHERE `id` = '%d'", iId);
		g_dSQLite.Query(SQL_Callback_DeleteBekap, sQuery);
	#if DEBUG
		LogToFile(g_sLogFile, "DeleteBekap:QUERY: %s", sQuery);
	#endif
	}
}

public void SQL_Callback_DeleteBekap(Database db, DBResultSet dbRs, const char[] sError, any iData)
{
	if (dbRs == null || sError[0])
		LogToFile(g_sLogFile, "SQL_Callback_DeleteBekap: %s", sError);
}

//-----------------------------------------------------------------------------------------------------------------------------
// репорт
void SetBdReport(int iClient, const char[] sReason)
{
	if (!iClient)
		return;

	int iTarget	= GetClientOfUserId(g_iTargetReport[iClient]);
	
	if(!iTarget)
	{
		PrintToChat2(iClient, "%T", "No Client Game", iClient);
		return;
	}

	char sReportName[MAX_NAME_LENGTH],
		 sEReportName[MAX_NAME_LENGTH*2+1],
		 sQuery[1024],
		 sEReason[556],
		 sReport_SteamID[MAX_STEAMID_LENGTH],
		 sReportIp[MAX_IP_LENGTH],
		 sSteamID[MAX_STEAMID_LENGTH],
		 sIp[MAX_IP_LENGTH],
		 sName[MAX_NAME_LENGTH],
		 sEName[MAX_NAME_LENGTH*2+1];

	GetClientAuthId(iClient, TYPE_STEAM, sSteamID, sizeof(sSteamID));
	GetClientIP(iClient, sIp, sizeof(sIp));
	GetClientName(iClient, sName, sizeof(sName));
	
	GetClientAuthId(iTarget, TYPE_STEAM, sReport_SteamID, sizeof(sReport_SteamID));
	GetClientIP(iTarget, sReportIp, sizeof(sReportIp));
	GetClientName(iTarget, sReportName, sizeof(sReportName));
	
	g_dDatabase.Escape(sReason, sEReason, sizeof(sEReason));
	g_dDatabase.Escape(sReportName, sEReportName, sizeof(sEReportName));
	g_dDatabase.Escape(sName, sEName, sizeof(sEName));
	
	if(g_iServerID == -1)
	{
		FormatEx(sQuery, sizeof(sQuery), "INSERT INTO    %s_submissions (submitted, SteamId, name, email, ModID, reason, ip, subname, sip, archiv, server) \
										VALUES         	(UNIX_TIMESTAMP(), '%s', '%s', '%s', (SELECT modid FROM %s_servers WHERE ip = '%s' AND port = '%s' LIMIT 1), \
										'%s', '%s', '%s', '%s', 0, (SELECT sid FROM %s_servers WHERE ip = '%s' AND port = '%s' LIMIT 1))", 
				g_sDatabasePrefix, sReport_SteamID, sEReportName, sSteamID, g_sDatabasePrefix, g_sServerIP, g_sServerPort, sEReason, sIp, sEName, sReportIp, g_sServerIP, g_sServerPort);
	}
	else
	{
		FormatEx(sQuery, sizeof(sQuery), "INSERT INTO   %s_submissions (submitted, SteamId, name, email, ModID, reason, ip, subname, sip, archiv, server) \
										VALUES         (UNIX_TIMESTAMP(), '%s', '%s', '%s', (SELECT modid FROM %s_servers WHERE sid = %d LIMIT 1), '%s', '%s', '%s', '%s', 0, %d)", 
				g_sDatabasePrefix, sReport_SteamID, sEReportName, sSteamID, g_sDatabasePrefix, g_iServerID, sEReason, sIp, sEName, sReportIp, g_iServerID);
	}
	
	DataPack dPack = new DataPack();
	dPack.WriteCell(GetClientUserId(iClient));
	dPack.WriteString(sReportName);
	dPack.WriteString(sQuery);
	
#if DEBUG
	LogToFile(g_sLogFile, "SetBdReport:QUERY: %s", sQuery);
#endif
	g_dDatabase.SetCharset("utf8");
	g_dDatabase.Query(CheckCallbackReport, sQuery, dPack, DBPrio_High);
}

public void CheckCallbackReport(Database db, DBResultSet dbRs, const char[] sError, any data)
{
	DataPack dPack = view_as<DataPack>(data);
	dPack.Reset();
	int iClient = GetClientOfUserId(dPack.ReadCell());
	char sReportName[MAX_NAME_LENGTH];
	dPack.ReadString(sReportName, sizeof(sReportName));
	if (dbRs == null || sError[0])
	{
		LogToFile(g_sLogFile, "SQL_CheckCallbackReport: %s", sError);
		char sQuery[1024];
		dPack.ReadString(sQuery, sizeof(sQuery));
		BekapStart(sQuery);
	}

	if (iClient)
		PrintToChat2(iClient, "%T", "Yes report", iClient, sReportName);

	delete dPack;
}