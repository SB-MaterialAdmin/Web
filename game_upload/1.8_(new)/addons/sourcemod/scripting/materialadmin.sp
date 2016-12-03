#pragma semicolon 1
//#pragma tabsize 0

#include <sourcemod>
#include <materialadmin>
#include <sdktools>

#undef REQUIRE_PLUGIN
#include <adminmenu>

#pragma newdecls required

#define MAX_STEAMID_LENGTH 	32
#define MAX_IP_LENGTH 		64
#define CS_TEAM_NONE		0	// No team yet. 
#define CS_TEAM_SPECTATOR	1	// Spectators.
#define CS_TEAM_T 			2	// Terrorists.
#define CS_TEAM_CT			3	// Counter-Terrorists.

#define PREFIX 		"[MA] "
#define DEBUG 		1 	//тестовый режим
#define TYPE_STEAM 	AuthId_Steam2
#define FORMAT_TIME NULL_STRING	// формат времени показывающий игроку при бане, NULL_STRING = sm_datetime_format

char g_sTarget[MAXPLAYERS+1][4][125];
#define TNAME 		0 	// Name
#define TIP 		1	// ip
#define TSTEAMID 	2 	// steam
#define TREASON 	3 	// Reason

int g_iTarget[MAXPLAYERS+1][2];
#define TTIME 	0	// time
#define TTYPE 	1	// type selkt

int g_iTargetType[MAXPLAYERS+1];
#define TYPE_BAN		1
#define TYPE_ADDBAN		2
#define TYPE_GAG		3
#define TYPE_MUTE		4
#define TYPE_SILENCE	5
#define TYPE_UNBAN		6
#define TYPE_UNGAG		7
#define TYPE_UNMUTE		8
#define TYPE_UNSILENCE	9

int g_iTargenMuteTime[MAXPLAYERS+1];
char g_iTargetMuteReason[MAXPLAYERS+1][125];

int g_iTargetMuteType[MAXPLAYERS+1];
#define TYPEMUTE 		1	// мут
#define TYPEGAG 		2  	// чат
#define TYPESILENCE 	3	// мут и чат

int	g_iServerID = -1,
	g_iOffMaxPlayers,
	g_iOffMenuItems,
	g_iShowAdminAction,
	g_iTargetReport[MAXPLAYERS+1]; // репорт юзер

Database g_dSQLite = null,
	g_dDatabase = null;
	
ArrayList g_aUserId[MAXPLAYERS+1],
	g_aAdminsExpired;

ConVar g_Cvar_Alltalk,
	g_Cvar_Deadtalk;
	
Handle g_hTimerMute[MAXPLAYERS+1] = null,
	g_hTimerGag[MAXPLAYERS+1] = null,
	g_hTimerBekap = null;
	
float g_fRetryTime;

TopMenu g_tmAdminMenu;
Menu g_mReasonBMenu,
	g_mReasonMMenu,
	g_mHackingMenu,
	g_mTimeMenu;

char g_sServerIP[32], 
	g_sServerPort[8],
	g_sLogFile[256],
	g_sOffFormatTime[56],
	g_sWebsite[256],
	g_sDatabasePrefix[10] = "sb";
	
bool g_bSayReason[MAXPLAYERS+1],
	g_bSayReasonReport[MAXPLAYERS+1],
	g_bOffMapClear,
	g_bAddBan,
	g_bUnBan,
	g_bReport,
	g_bBanSayPanel,
	g_bHooked = false,
	g_bLalod,
	g_bOnileTarget[MAXPLAYERS+1],
	g_bBanClientConnect[MAXPLAYERS+1];
	
/* Admin KeyValues */
char g_sGroupsLoc[128],
	g_sAdminsLoc[128],
	g_sOverridesLoc[128];
	
int g_iGameTyp;
#define GAMETYP_CCS 	0 //css
#define GAMETYP_CSGO 	1 //csgo
#define GAMETYP_TF2 	2 //tf2

SMCParser g_smcConfigParser;
enum ConfigState
{
	ConfigState_Non,
	ConfigState_Config,
	ConfigState_Time,
	ConfigState_Reason_Ban,
	ConfigState_Reason_Hacking,
	ConfigState_Reason_Mute,
}
ConfigState g_iConfigState = ConfigState_Non;

#include "materialadmin/admin.sp"
#include "materialadmin/menu.sp"
#include "materialadmin/function.sp"
#include "materialadmin/commands.sp"
#include "materialadmin/database.sp"
#include "materialadmin/native.sp"

#define VERSION "0.1.1b"

public Plugin myinfo = 
{
	name = "Material Admin",
	author = "Grey™",
	description = "For to sm 1.8",
	version = VERSION,
	url = "hlmod.ru Skype: wolf-1-ser"
};

public void OnPluginStart() 
{
	LoadTranslations("materialadmin.phrases");
	LoadTranslations("common.phrases");

	char sGameType[10];
	GetGameFolderName(sGameType, sizeof(sGameType));
	
	if (StrEqual(sGameType, "cstrike", false))
		g_iGameTyp = GAMETYP_CCS; 
	else if (StrEqual(sGameType, "csgo", false)) 
		g_iGameTyp = GAMETYP_CSGO;
	else if (StrEqual(sGameType, "tf", false)) 
		g_iGameTyp = GAMETYP_TF2; 

	RegComands();

	g_aAdminsExpired = CreateArray(2);

	BuildPath(Path_SM, g_sLogFile, sizeof(g_sLogFile), "logs/materialadmin.log");
	BuildPath(Path_SM, g_sGroupsLoc,sizeof(g_sGroupsLoc),"configs/materialadmin/admin_groups.cfg");
	BuildPath(Path_SM, g_sAdminsLoc,sizeof(g_sAdminsLoc),"configs/materialadmin/admins.cfg");
	BuildPath(Path_SM, g_sOverridesLoc, sizeof(g_sOverridesLoc), "configs/materialadmin/overrides.cfg");
	
#if DEBUG
	LogToFile(g_sLogFile, "plugin version %s", VERSION);
#endif
	
	for (int i = 1; i <= MAXPLAYERS; i++)
	{
		g_aUserId[i] = CreateArray(ByteCountToCells(12));
	#if DEBUG
		if(g_aUserId[i] == null)
			LogToFile(g_sLogFile, "g_aUserId %d no", i);
		else
			LogToFile(g_sLogFile, "g_aUserId %d yes", i);
	#endif
	}

	SBCreateMenu();
	ReadConfig();
	g_bLalod = false;
	ConnectSourceBan();
	CreateTables();
}

public void OnConfigsExecuted()
{
	char sFileName[200],
		sNewFileName[200];
	BuildPath(Path_SM, sFileName, sizeof(sFileName), "plugins/basebans.smx");
	if(FileExists(sFileName))
	{
		BuildPath(Path_SM, sNewFileName, sizeof(sNewFileName), "plugins/disabled/basebans.smx");
		ServerCommand("sm plugins unload basebans");
		if(FileExists(sNewFileName))
			DeleteFile(sNewFileName);
		RenameFile(sNewFileName, sFileName);
		LogToFile(g_sLogFile, "plugins/basebans.smx was unloaded and moved to plugins/disabled/basebans.smx");
	}
	
	BuildPath(Path_SM, sFileName, sizeof(sFileName), "plugins/basecomm.smx");
	if(FileExists(sFileName))
	{
		BuildPath(Path_SM, sNewFileName, sizeof(sNewFileName), "plugins/disabled/basecomm.smx");
		ServerCommand("sm plugins unload basecomm");
		if(FileExists(sNewFileName))
			DeleteFile(sNewFileName);
		RenameFile(sNewFileName, sFileName);
		LogToFile(g_sLogFile, "plugins/basecomm.smx was unloaded and moved to plugins/disabled/basecomm.smx");
	}
	
	BuildPath(Path_SM, sFileName, sizeof(sFileName), "plugins/sourcecomms.smx");
	if(FileExists(sFileName))
	{
		BuildPath(Path_SM, sNewFileName, sizeof(sNewFileName), "plugins/disabled/sourcecomms.smx");
		ServerCommand("sm plugins unload sourcecomms");
		if(FileExists(sNewFileName))
			DeleteFile(sNewFileName);
		RenameFile(sNewFileName, sFileName);
		LogToFile(g_sLogFile, "plugins/sourcecomms.smx was unloaded and moved to plugins/disabled/sourcecomms.smx");
	}
	
	BuildPath(Path_SM, sFileName, sizeof(sFileName), "plugins/sourcebans.smx");
	if(FileExists(sFileName))
	{
		BuildPath(Path_SM, sNewFileName, sizeof(sNewFileName), "plugins/disabled/sourcebans.smx");
		ServerCommand("sm plugins unload sourcebans");
		if(FileExists(sNewFileName))
			DeleteFile(sNewFileName);
		RenameFile(sNewFileName, sFileName);
		LogToFile(g_sLogFile, "plugins/sourcebans.smx was unloaded and moved to plugins/disabled/sourcebans.smx");
	}
	
	if (g_bLalod)
	{
		ReadConfig();
		AdminHash();
	}
	else
		g_bLalod = true;
	
	if(g_bOffMapClear) 
		ClearHistories();
}

// удаление игроков вошедших в игру
public void OnClientPostAdminCheck(int iClient)
{
	if (!IsClientInGame(iClient) || IsFakeClient(iClient)) 
		return;

	CheckClientBan(iClient);
}

 //зачисление в список игроков вышедших из игры
public void OnClientDisconnect(int iClient) 
{
	if (!IsClientInGame(iClient) || IsFakeClient(iClient) || g_bBanClientConnect[iClient]) 
		return;

	if (GetUserAdmin(iClient) != INVALID_ADMIN_ID) 
		return;

	g_bSayReason[iClient] = false;
	g_bSayReasonReport[iClient] = false;
	g_iTargetMuteType[iClient] = 0;
	KillTimerMute(iClient);
	KillTimerGag(iClient);
	
	char sSteamID[MAX_STEAMID_LENGTH],
		 sName[MAX_NAME_LENGTH],
		 sIP[MAX_IP_LENGTH];

	GetClientAuthId(iClient, TYPE_STEAM, sSteamID, sizeof(sSteamID));
	GetClientName(iClient, sName, sizeof(sName));
	GetClientIP(iClient, sIP, sizeof(sIP));
	SetOflineInfo(sSteamID, sName, sIP);

#if DEBUG
	char sTime[64];

	FormatTime(sTime, sizeof(sTime), g_sOffFormatTime, GetTime());
	LogToFile(g_sLogFile,"New: %s %s - %s ; %s.", sName, sSteamID, sIP, sTime);
#endif
}

//получение значений конфига сб
void ReadConfig()
{
	if (!g_smcConfigParser)
		g_smcConfigParser = new SMCParser();
	
	g_smcConfigParser.OnEnterSection = NewSection;
	g_smcConfigParser.OnKeyValue = KeyValue;
	g_smcConfigParser.OnLeaveSection = EndSection;

	char sConfigFile[PLATFORM_MAX_PATH];
	BuildPath(Path_SM, sConfigFile, sizeof(sConfigFile), "configs/materialadmin/materialadmin.cfg");

	if (g_mReasonMMenu != null)
		g_mReasonMMenu.RemoveAllItems();
	if (g_mReasonBMenu != null)
		g_mReasonBMenu.RemoveAllItems();
	if (g_mHackingMenu != null)
		g_mHackingMenu.RemoveAllItems();
	if (g_mTimeMenu != null)
		g_mTimeMenu.RemoveAllItems();

	if(FileExists(sConfigFile))
	{
		g_iConfigState = ConfigState_Non;
	
		int iLine;
		SMCError err = g_smcConfigParser.ParseFile(sConfigFile, iLine);
		if (err != SMCError_Okay)
		{
			char sError[256];
			g_smcConfigParser.GetErrorString(err, sError, sizeof(sError));
			LogError("Could not parse file (line %d, file \"%s\"):", iLine, sConfigFile);
			LogError("Parser encountered error: %s", sError);
		}
	}
	else 
	{
		char sError[PLATFORM_MAX_PATH+64];
		FormatEx(sError, sizeof(sError), "%sFATAL *** ERROR *** can not find %s", PREFIX, sConfigFile);
		LogError("FATAL *** ERROR *** can not find %s", sConfigFile);
		SetFailState(sError);
	}
}

public SMCResult NewSection(SMCParser Smc, const char[] sName, bool bOpt_quotes)
{
	if(sName[0])
	{
		if(strcmp("Config", sName, false) == 0)
			g_iConfigState = ConfigState_Config;
		else if(strcmp("MuteReasons", sName, false) == 0)
			g_iConfigState = ConfigState_Reason_Mute;
		else if(strcmp("BanReasons", sName, false) == 0)
			g_iConfigState = ConfigState_Reason_Ban;
		else if(strcmp("HackingReasons", sName, false) == 0)
			g_iConfigState = ConfigState_Reason_Hacking;
		else if(strcmp("Time", sName, false) == 0)
			g_iConfigState = ConfigState_Time;
		else
			g_iConfigState = ConfigState_Non;
	#if DEBUG
		LogToFile(g_sLogFile,"Loaded config. name %s", sName);
	#endif
	}
	
	return SMCParse_Continue;
}

public SMCResult KeyValue(SMCParser Smc, const char[] sKey, const char[] sValue, bool bKey_quotes, bool bValue_quotes)
{
	if(!sKey[0] || !sValue[0])
		return SMCParse_Continue;

	switch(g_iConfigState)
	{
		case ConfigState_Config:
		{
			if(strcmp("DatabasePrefix", sKey, false) == 0) 
				strcopy(g_sDatabasePrefix, sizeof(g_sDatabasePrefix), sValue);
			if(strcmp("Website", sKey, false) == 0) 
				strcopy(g_sWebsite, sizeof(g_sWebsite), sValue);
			if(strcmp("OffTimeFormat", sKey, false) == 0)
				strcopy(g_sOffFormatTime, sizeof(g_sOffFormatTime), sValue);
			if(strcmp("Addban", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bAddBan = false;
				else
					g_bAddBan = true;
			}
			if(strcmp("Unban", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bUnBan = false;
				else
					g_bUnBan = true;
			}
			if(strcmp("OffMapClear", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bOffMapClear = false;
				else
					g_bOffMapClear = true;
			}
			if(strcmp("Report", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bReport = false;
				else
					g_bReport = true;
			}
			if(strcmp("BanSayPanel", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bBanSayPanel = false;
				else
					g_bBanSayPanel = true;
			}
			if(strcmp("ServerID", sKey, false) == 0)
				g_iServerID = StringToInt(sValue);
			if(strcmp("OffMaxPlayers", sKey, false) == 0)
				g_iOffMaxPlayers = StringToInt(sValue);
			if(strcmp("OffMenuNast", sKey, false) == 0)
				g_iOffMenuItems = StringToInt(sValue);
			if(strcmp("RetryTime", sKey, false) == 0)
				g_fRetryTime = StringToFloat(sValue);
			if(strcmp("ShowAdminAction", sKey, false) == 0)
				g_iShowAdminAction = StringToInt(sValue);
		#if DEBUG
			LogToFile(g_sLogFile,"Loaded config. key \"%s\", value \"%s\"", sKey, sValue);
		#endif
		}
		case ConfigState_Reason_Mute:
		{
			g_mReasonMMenu.AddItem(sKey, sValue);
		#if DEBUG
			LogToFile(g_sLogFile,"Loaded mute reason. key \"%s\", display_text \"%s\"", sKey, sValue);
		#endif
		}
		case ConfigState_Reason_Ban:
		{
			g_mReasonBMenu.AddItem(sKey, sValue);
		#if DEBUG
			LogToFile(g_sLogFile,"Loaded ban reason. key \"%s\", display_text \"%s\"", sKey, sValue);
		#endif
		}
		case ConfigState_Reason_Hacking:
		{
			g_mHackingMenu.AddItem(sKey, sValue);
		#if DEBUG
			LogToFile(g_sLogFile,"Loaded hacking reason. key \"%s\", display_text \"%s\"", sKey, sValue);
		#endif
		}
		case ConfigState_Time:
		{
			g_mTimeMenu.AddItem(sKey, sValue);
		#if DEBUG
			LogToFile(g_sLogFile,"Loaded time. key \"%s\", display_text \"%s\"", sKey, sValue);
		#endif
		}
	}
	return SMCParse_Continue;
}

public SMCResult EndSection(SMCParser Smc)
{
	return SMCParse_Continue;
}