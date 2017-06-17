// *************************************************************************
//  This file is part of SourceBans++.
//
//  Copyright (C) 2014-2016 Sarabveer Singh <me@sarabveer.me>
//
//  SourceBans++ is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, per version 3 of the License.
//
//  SourceBans++ is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with SourceBans++. If not, see <http://www.gnu.org/licenses/>.
//
//  This file is based off work covered by the following copyright(s):  
//
//   SourceBans Checker 1.0.2
//   Copyright (C) 2010-2013 Nicholas Hastings
//   Licensed under GNU GPL version 3, or later.
//   Page: <https://forums.alliedmods.net/showthread.php?p=1288490>
//
// *************************************************************************

#include <sourcemod>
#include <materialadmin>

#define LISTBANS_USAGE "ma_listbans <#userid|name> - Lists a user's prior bans from Material Admin"
#define MAX_STEAMID_LENGTH 	32
#define MAX_IP_LENGTH 		64

char g_sDatabasePrefix[12],
	g_sNameReples[MAX_NAME_LENGTH];
Database g_dDatabase = null;

public Plugin myinfo = 
{
	name = "Material Admin Checker", 
	author = "psychonic, Ca$h Munny, Sarabveer(VEER™)", 
	description = "Notifies admins of prior bans from Material Admin upon player connect.", 
	version = MAVERSION, 
	url = "https://github.com/CrazyHackGUT/SB_Material_Design/"
};

public void OnPluginStart()
{
	LoadTranslations("common.phrases");
	LoadTranslations("materialadmin.phrases");
	LoadTranslations("machecker.phrases");
	RegAdminCmd("ma_listbans", OnListSourceBansCmd, ADMFLAG_BAN, LISTBANS_USAGE);
}

public void MAOnConfigSetting()
{
	MAGetConfigSetting("DatabasePrefix", g_sDatabasePrefix);
}

public void MAOnConnectDatabase(Database db)
{
	g_dDatabase = db;
}

public void OnClientAuthorized(int iClient, const char[] sAuth)
{
	/* Do not check bots nor check player with lan steamid. */
	if (sAuth[0] == 'B' || sAuth[9] == 'L')
		return;

	if (!g_dDatabase)
		return;
	
	char sQuery[512], 
		sIp[30];
	GetClientIP(iClient, sIp, sizeof(sIp));
	FormatEx(sQuery, sizeof(sQuery), "\
			SELECT COUNT(bid) FROM `%s_bans` \
			WHERE ((`type` = 0 AND `authid` REGEXP '^STEAM_[0-9]:%s$') OR (`type` = 1 AND `ip` = '%s')) \
			AND ((`length` > '0' AND `ends` > UNIX_TIMESTAMP()) OR `RemoveType` IS NOT NULL)", 
		g_sDatabasePrefix, sAuth[8], sIp);
	
	g_dDatabase.Query(OnConnectBanCheck, sQuery, GetClientUserId(iClient), DBPrio_Low);
}

public OnConnectBanCheck(Database db, DBResultSet dbRs, const char[] sError, any iUserId)
{
	int iClient = GetClientOfUserId(iUserId);
	
	if (!iClient || !dbRs || !dbRs.FetchRow())
		return;
	
	int iBanCount = dbRs.FetchInt(0);
	if (iBanCount > 0)
	{
		GetClientName(iClient, g_sNameReples, sizeof(g_sNameReples));
		PrintToBanAdmins("%t", "Player connect", g_sNameReples, iBanCount);
	}
}

public Action OnListSourceBansCmd(int iClient, int iArgs)
{
	if (!iArgs)
	{
		ReplyToCommand(iClient, LISTBANS_USAGE);
		return Plugin_Handled;
	}
	
	if (!g_dDatabase)
	{
		if (iClient)
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "Database not ready", iClient);
		else
			ReplyToCommand(iClient, "%sError: Database not ready.", MAPREFIX);
		return Plugin_Handled;
	}
	
	char sArg[MAX_NAME_LENGTH],
		 sSteamID[MAX_STEAMID_LENGTH],
		 sName[MAX_NAME_LENGTH],
		 sIP[MAX_IP_LENGTH],
		 sQuery[1024];

	GetCmdArg(1, sArg, sizeof(sArg));
	
	int iTarget = FindTarget(iClient, sArg, true, true);
	if (iTarget == -1)
		return Plugin_Handled;

	GetClientAuthId(iTarget, AuthId_Steam2, sSteamID, sizeof(sSteamID));
	GetClientIP(iTarget, sIP, sizeof(sIP));
	GetClientName(iTarget, sName, sizeof(sName));
	
	if (!iClient)
		ReplyToCommand(iClient, "Note: if you are using this command through an rcon tool, you will not see results.");
	
	char sText[126];
	FormatEx(sText, sizeof(sText), "%10.10s  %10.10s  %10.10s  %1.1s  %8.10s %21.21s", "Ban Date", "Banned By", "End Date", "R", "Length", "Reason");
	if (!iClient)
		PrintToServer("%sListing bans for %s\n%s\n-------------------------------------------------------------------------------", MAPREFIX, sName, sText);
	else
		ReplyToCommand(iClient, "%s%T\n%s\n-------------------------------------------------------------------------------", MAPREFIX, "Listing bans", iClient, sName, sText);
	
	DataPack dPack = new DataPack();
	dPack.WriteCell((!iClient)?0:GetClientUserId(iClient));
	dPack.WriteString(sName);
	
	FormatEx(sQuery, sizeof(sQuery), "\
			SELECT `created`, `%s_admins`.`user`, `ends`, `length`, `reason`, `RemoveType` FROM `%s_bans` LEFT JOIN `%s_admins` ON `%s_bans`.`aid` = `%s_admins`.`aid` \
			WHERE ((`type` = 0 AND `%s_bans`.`authid` REGEXP '^STEAM_[0-9]:%s$') OR (`type` = 1 AND `ip` = '%s')) \
			AND ((`length` > 0 AND `ends` > UNIX_TIMESTAMP()) OR `RemoveType` IS NOT NULL) ORDER BY `created`;", 
		g_sDatabasePrefix, g_sDatabasePrefix, g_sDatabasePrefix, g_sDatabasePrefix, g_sDatabasePrefix, g_sDatabasePrefix, sSteamID[8], sIP);
	g_dDatabase.Query(OnListBans, sQuery, dPack, DBPrio_Low);
	
	return Plugin_Handled;
}

public OnListBans(Database db, DBResultSet dbRs, const char[] sError, any data)
{
	DataPack dPack = view_as<DataPack>(data);
	dPack.Reset();
	int iUserId = dPack.ReadCell();
	new iClient = GetClientOfUserId(iUserId);
	char sName[MAX_NAME_LENGTH];
	dPack.ReadString(sName, sizeof(sName));
	delete dPack;
	
	if (iUserId > 0 && !iClient)
		return;
	
	if (!dbRs || sError[0])
	{
		if (!iUserId)
			PrintToServer("%sDB error while retrieving bans for %s:\n%s", MAPREFIX, sName, sError);
		else
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "DB error while retrieving bans", iClient, sName, sError);
		return;
	}
	
	if (!dbRs.RowCount)
	{
		if (!iUserId)
			PrintToServer("%sNo bans found for %s.", MAPREFIX, sName);
		else
			ReplyToCommand(iClient, "%s%T", MAPREFIX, "No bans found", iClient, sName);
		return;
	}
	
	char sCreatedDate[11] = "<Unknown> ",
		 sBannedby[32] = "<Unknown> ",
		 sLenstring[32] = "N/A       ",
		 sEndDate[11] = "N/A       ",
		 sReason[126],
		 sRemoveType[2] = " ";

	int iLength;

	while (dbRs.FetchRow())
	{
	#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
		if (!SQL_IsFieldNull(dbRs, 0))
	#else
		if (!dbRs.IsFieldNull(0))
	#endif
		{
			FormatTime(sCreatedDate, sizeof(sCreatedDate), "%Y-%m-%d", dbRs.FetchInt(0));
		}
		
	#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
		if (!SQL_IsFieldNull(dbRs, 1))
	#else
		if (!dbRs.IsFieldNull(1))
	#endif
		{
			dbRs.FetchString(1, sBannedby, sizeof(sBannedby));
		}

		iLength = dbRs.FetchInt(3);
		if (!iLength)
		{
			if (!iUserId)
				strcopy(sLenstring, sizeof(sLenstring), "Permanent");
			else
				FormatEx(sLenstring, sizeof(sLenstring), "%T  ", "Permanent", iClient);
		}
		else
			IntToString(iLength, sLenstring, sizeof(sLenstring));
		
	#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
		if (!SQL_IsFieldNull(dbRs, 2))
	#else
		if (!dbRs.IsFieldNull(2))
	#endif
		{
			FormatTime(sEndDate, sizeof(sEndDate), "%Y-%m-%d", dbRs.FetchInt(2));
		}

		dbRs.FetchString(4, sReason, sizeof(sReason));
		
	#if SOURCEMOD_V_MAJOR == 1 && SOURCEMOD_V_MINOR == 7
		if (!SQL_IsFieldNull(dbRs, 5))
	#else
		if (!dbRs.IsFieldNull(5))
	#endif
		{
			dbRs.FetchString(5, sRemoveType, sizeof(sRemoveType));
		}

		if (!iUserId)
			PrintToServer("%10.10s  %10.32s  %10.10s  %1.1s  %8.32s %21.32s", sCreatedDate, sBannedby, sEndDate, sRemoveType, sLenstring, sReason);
		else
			ReplyToCommand(iClient, "%10.10s  %10.32s  %10.10s  %1.1s  %8.32s %21.32s", sCreatedDate, sBannedby, sEndDate, sRemoveType, sLenstring, sReason);
	}
}

void PrintToBanAdmins(const char[] sBuffer, any ...)
{
	char sText[128];
	
	for (int i = 1; i <= MaxClients; i++)
	{
		if (IsClientInGame(i) && !IsFakeClient(i) && CheckCommandAccess(i, "sm_ban", ADMFLAG_BAN))
		{
			SetGlobalTransTarget(i);
			VFormat(sText, sizeof(sText), sBuffer, 2);
			PrintToChat2(i, "%s", sText);
		}
	}
}

void PrintToChat2(int iClient, const char[] sMesag, any ...)
{
	static const char sColorT[][] = {"#1",   "#2",   "#3",   "#4",   "#5",   "#6",   "#7",   "#8",   "#9",   "#10", "#OB",   "#OC",  "#OE"},
					  sColorC[][] = {"\x01", "\x02", "\x03", "\x04", "\x05", "\x06", "\x07", "\x08", "\x09", "\x10", "\x0B", "\x0C", "\x0E"},
					  sNameD[] = "name1";
	char sBufer[256];
	VFormat(sBufer, sizeof(sBufer), sMesag, 3);
	
	// del name ???
	if (g_sNameReples[0])
		ReplaceString(sBufer, sizeof(sBufer), g_sNameReples, sNameD);
	
	Format(sBufer, sizeof(sBufer), "%T %s", "prifix", iClient, sBufer);
	for(int i = 0; i < 13; i++)
		ReplaceString(sBufer, sizeof(sBufer), sColorT[i], sColorC[i]);
	
	// add name ????
	ReplaceString(sBufer, sizeof(sBufer), sNameD, g_sNameReples);

	if (GetUserMessageType() == UM_Protobuf)
		PrintToChat(iClient, " \x01%s.", sBufer);
	else
		PrintToChat(iClient, "\x01%s.", sBufer);
}