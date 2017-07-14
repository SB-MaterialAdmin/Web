#pragma semicolon 1
//#pragma tabsize 0

#include <sourcemod>
#include <SteamWorks>
#include <materialadmin>
#include <sdktools>
#include <regex>

#undef REQUIRE_PLUGIN
#include <adminmenu>
#include <basecomm>

#pragma newdecls required

#define MAX_STEAMID_LENGTH 	32
#define MAX_IP_LENGTH 		64
#define CS_TEAM_NONE		0	// No team yet. 
#define CS_TEAM_SPECTATOR	1	// Spectators.
#define CS_TEAM_T 			2	// Terrorists.
#define CS_TEAM_CT			3	// Counter-Terrorists.

#define TYPE_STEAM 	AuthId_Steam2 // вид стим
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
#define TYPE_BANIP		2
#define TYPE_ADDBAN		3
#define TYPE_UNBAN		4
#define TYPE_GAG		5
#define TYPE_MUTE		6
#define TYPE_SILENCE	7
#define TYPE_UNGAG		8
#define TYPE_UNMUTE		9
#define TYPE_UNSILENCE	10

int g_iTargenMuteTime[MAXPLAYERS+1],
	g_iTargenMuteImun[MAXPLAYERS+1];
char g_sTargetMuteReason[MAXPLAYERS+1][125],
	g_sTargetMuteSteamAdmin[MAXPLAYERS+1][MAX_STEAMID_LENGTH],
	g_sNameReples[2][MAX_NAME_LENGTH];

int g_iTargetMuteType[MAXPLAYERS+1];
#define TYPEMUTE 		1	// мут
#define TYPEGAG 		2  	// чат
#define TYPESILENCE 	3	// мут и чат

char g_sAddAdminInfo[MAXPLAYERS+1][4][256];
#define ADDNAME 	0	// ник
#define ADDSTEAM 	1	// стим
#define ADDFLAG 	3	// флаг
int g_iAddAdmin[MAXPLAYERS+1][2];
#define ADDTIME 	0	// время админки
bool g_bAddAdminFlag[MAXPLAYERS+1][21];
#define MFLAG_ROOT			0	// 	"z"  root
#define MFLAG_GENERIC		1	// 	"b"	 Generic admin, required for admins
#define MFLAG_RESERVATION	2	// 	"a"	 Reserved slots
#define MFLAG_KICK			3	//	"c"	 Kick other players
#define MFLAG_BAN			4	//	"d"	 Banning other players
#define MFLAG_UNBAN			5	// 	"e"	 Removing bans
#define MFLAG_SLAY			6	//	"f"	 Slaying other players
#define MFLAG_CHANGEMAP		7	//	"g"	 Changing the map
#define MFLAG_CONVARS		8	//	"h"	 Changing cvars
#define MFLAG_CONFIG		9	//	"i"	 Changing configs
#define MFLAG_CHAT			10	//	"j"	 Special chat privileges
#define MFLAG_VOTE			11	//	"k"	 Voting
#define MFLAG_PASSWORD		12	//	"l"	 Password the server
#define MFLAG_RCON			13	//	"m"	 Remote console
#define MFLAG_CHEATS		14	//	"n"	 Change sv_cheats and related commands
#define MFLAG_CUSTOM1		15	//	"o"
#define MFLAG_CUSTOM2		16	//	"p"
#define MFLAG_CUSTOM3		17	//	"q"
#define MFLAG_CUSTOM4		18	//	"r"
#define MFLAG_CUSTOM5		19	//	"s"
#define MFLAG_CUSTOM6		20	//	"t"
char g_sAddAdminFlag[][] = {"z", "b", "a", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t"};
bool g_bAdminAdd[MAXPLAYERS+1][4];
#define ADDIMUN 	1	// имун
#define ADDPASS 	2	// пароль
#define ADDMENU 	3 	// меню

int	g_iServerID = -1,
	g_iOffMaxPlayers,
	g_iOffMenuItems,
	g_iShowAdminAction,
	g_iServerBanTime,
	g_iBasecommTime,
	g_iServerImmune,
	g_iMassBan,
	g_iBanTypMenu,
	g_iTargetReport[MAXPLAYERS+1]; // репорт юзер

Database g_dSQLite = null,
	g_dDatabase = null;
	
ArrayList g_aUserId[MAXPLAYERS+1],
	g_aGroupArray;
StringMap g_tAdminsExpired,
	g_tGroupBanTimeMax,
	g_tGroupMuteTimeMax,
	g_tAdminBanTimeMax,
	g_tAdminMuteTimeMax,
	g_tAdminWebFlag,
	g_tMenuTime;

bool g_bCvar_Alltalk;
int g_iCvar_ImmunityMode,
	g_iCvar_Deadtalk;
	
Handle g_hTimerMute[MAXPLAYERS+1] = null,
	g_hTimerGag[MAXPLAYERS+1] = null,
	g_hTimerBekap = null,
	g_hOnConfigSettingForward;
	
float g_fRetryTime = 60.0;

TopMenu g_tmAdminMenu;
Menu g_mReasonBMenu,
	g_mReasonMMenu,
	g_mHackingMenu;

char g_sServerIP[32], 
	g_sServerPort[8],
	g_sOffFormatTime[56],
	g_sWebsite[256],
	g_sDatabasePrefix[10] = "sb";
	
char g_sLogAdmin[256],
	g_sLogConfig[256],
	g_sLogDateBase[256],
	g_sLogNative[256],
	g_sLogAction[256];
	
bool g_bSayReason[MAXPLAYERS+1],
	g_bSayReasonReport[MAXPLAYERS+1],
	g_bOffMapClear,
	g_bAddBan,
	g_bUnBan,
	g_bReport,
	g_bBanSayPanel,
	g_bActionOnTheMy,
	g_bHooked,
	g_bLalod,
	g_bLalodAdmin,
	g_bReshashAdmin,
	g_bIgnoreBanServer,
	g_bIgnoreMuteServer,
	g_bServerBanTyp,
	g_bNewConnect[MAXPLAYERS+1],
	g_bOnileTarget[MAXPLAYERS+1],
	g_bReportReason[MAXPLAYERS+1],
	g_bBanClientConnect[MAXPLAYERS+1];
	
// Admin KeyValues
char g_sGroupsLoc[128],
	g_sAdminsLoc[128],
	g_sOverridesLoc[128];
	
int g_iGameTyp;
#define GAMETYP_CCS 	1 //css
#define GAMETYP_TF2 	2 //tf2
#define GAMETYP_CSGO 	3 //csgo
#define GAMETYP_l4d 	4 //Left4Dead
#define GAMETYP_l4d2 	5 //Left4Dead2


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

public Plugin myinfo = 
{
	name = "Material Admin",
	author = "Material Admin Dev Team",
	description = "For to sm 1.7",
	version = MAVERSION,
	url = "https://github.com/CrazyHackGUT/SB_Material_Design/"
};

public void OnPluginStart() 
{
	LoadTranslations("materialadmin.phrases");
	LoadTranslations("common.phrases");

	EngineVersion enVersion = GetEngineVersion();
	
	if (enVersion == Engine_CSS)
		g_iGameTyp = GAMETYP_CCS; 
	else if (enVersion == Engine_CSGO)
		g_iGameTyp = GAMETYP_CSGO;
	else if (enVersion == Engine_TF2)
		g_iGameTyp = GAMETYP_TF2; 
	else if (enVersion == Engine_Left4Dead)
		g_iGameTyp = GAMETYP_l4d; 
	else if (enVersion == Engine_Left4Dead2)
		g_iGameTyp = GAMETYP_l4d2; 

	RegComands();

	char sPath[56];
	BuildPath(Path_SM, sPath, sizeof(sPath), "configs/materialadmin/admin");
	if(!DirExists(sPath))
		CreateDirectory(sPath, 511);
	BuildPath(Path_SM, g_sGroupsLoc,sizeof(g_sGroupsLoc),"configs/materialadmin/admin/groups.cfg");
	BuildPath(Path_SM, g_sAdminsLoc,sizeof(g_sAdminsLoc),"configs/materialadmin/admin/admins.cfg");
	BuildPath(Path_SM, g_sOverridesLoc, sizeof(g_sOverridesLoc), "configs/materialadmin/admin/overrides.cfg");
	
	BuildPath(Path_SM, sPath, sizeof(sPath), "logs/materialadmin");
	if(!DirExists(sPath))
		CreateDirectory(sPath, 511);

	LogOn();
	
	g_hOnConfigSettingForward = CreateGlobalForward("MAOnConfigSetting", ET_Ignore);
	
	for (int i = 1; i <= MAXPLAYERS; i++)
		g_aUserId[i] = new ArrayList(ByteCountToCells(12));
	
	g_aGroupArray = new ArrayList(ByteCountToCells(12));
	g_tAdminsExpired = new StringMap();
	g_tGroupBanTimeMax = new StringMap();
	g_tGroupMuteTimeMax = new StringMap();
	g_tAdminBanTimeMax = new StringMap();
	g_tAdminMuteTimeMax = new StringMap();
	g_tAdminWebFlag = new StringMap();
	g_tMenuTime = new StringMap();
	
	TopMenu topmenu;
	if (LibraryExists("adminmenu") && ((topmenu = GetAdminTopMenu()) != null))
		OnAdminMenuReady(topmenu);
	
	HookEvent("player_disconnect", Event_PlayerDisconnect, EventHookMode_Pre);

	MACreateMenu();
	ReadConfig();
	MAConnectDB();
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
		LogToFile(g_sLogAction, "plugins/basebans.smx was unloaded and moved to plugins/disabled/basebans.smx");
	}
	
	BuildPath(Path_SM, sFileName, sizeof(sFileName), "plugins/basecomm.smx");
	if(FileExists(sFileName))
	{
		BuildPath(Path_SM, sNewFileName, sizeof(sNewFileName), "plugins/disabled/basecomm.smx");
		ServerCommand("sm plugins unload basecomm");
		if(FileExists(sNewFileName))
			DeleteFile(sNewFileName);
		RenameFile(sNewFileName, sFileName);
		LogToFile(g_sLogAction, "plugins/basecomm.smx was unloaded and moved to plugins/disabled/basecomm.smx");
	}
	
	BuildPath(Path_SM, sFileName, sizeof(sFileName), "plugins/ma_adminmenu.smx");
	if(FileExists(sFileName))
	{
		BuildPath(Path_SM, sFileName, sizeof(sFileName), "plugins/adminmenu.smx");
		if(FileExists(sFileName))
		{
			BuildPath(Path_SM, sNewFileName, sizeof(sNewFileName), "plugins/disabled/adminmenu.smx");
			ServerCommand("sm plugins unload adminmenu");
			if(FileExists(sNewFileName))
				DeleteFile(sNewFileName);
			RenameFile(sNewFileName, sFileName);
			LogToFile(g_sLogAction, "plugins/adminmenu.smx was unloaded and moved to plugins/disabled/adminmenu.smx");
		}
	}
	
	BuildPath(Path_SM, sFileName, sizeof(sFileName), "plugins/sourcecomms.smx");
	if(FileExists(sFileName))
	{
		BuildPath(Path_SM, sNewFileName, sizeof(sNewFileName), "plugins/disabled/sourcecomms.smx");
		ServerCommand("sm plugins unload sourcecomms");
		if(FileExists(sNewFileName))
			DeleteFile(sNewFileName);
		RenameFile(sNewFileName, sFileName);
		LogToFile(g_sLogAction, "plugins/sourcecomms.smx was unloaded and moved to plugins/disabled/sourcecomms.smx");
	}
	
	BuildPath(Path_SM, sFileName, sizeof(sFileName), "plugins/sourcebans.smx");
	if(FileExists(sFileName))
	{
		BuildPath(Path_SM, sNewFileName, sizeof(sNewFileName), "plugins/disabled/sourcebans.smx");
		ServerCommand("sm plugins unload sourcebans");
		if(FileExists(sNewFileName))
			DeleteFile(sNewFileName);
		RenameFile(sNewFileName, sFileName);
		LogToFile(g_sLogAction, "plugins/sourcebans.smx was unloaded and moved to plugins/disabled/sourcebans.smx");
	}
	
	if (g_bLalod)
	{
		LogOn();
		ReadConfig();
		if (ConnectBd(g_dDatabase))
			KillTimerBekap();
	}
	else
		g_bLalod = true;
	
	if(g_bOffMapClear) 
		ClearHistories();
	
	// Отправка статы
	int iIp[4];
	if (SteamWorks_GetPublicIP(iIp))
	{
		Handle plugin = GetMyHandle();
		if (GetPluginStatus(plugin) == Plugin_Running)
		{
			char cBuffer[256], cVersion[12];
			GetPluginInfo(plugin, PlInfo_Version, cVersion, sizeof(cVersion));
			FormatEx(cBuffer, sizeof(cBuffer), "http://stats.scriptplugs.info/add_server.php");
			Handle hndl = SteamWorks_CreateHTTPRequest(k_EHTTPMethodPOST, cBuffer);
			FormatEx(cBuffer, sizeof(cBuffer), "key=c207ce6cda32a958e83a5897db41ac73&ip=%d.%d.%d.%d:%d&version=%s", iIp[0], iIp[1], iIp[2], iIp[3], FindConVar("hostport").IntValue, cVersion);
			SteamWorks_SetHTTPRequestRawPostBody(hndl, "application/x-www-form-urlencoded", cBuffer, sizeof(cBuffer));
			SteamWorks_SendHTTPRequest(hndl);
			delete hndl;
		}
	}
}

public void OnClientPostAdminCheck(int iClient)
{
	if (!IsClientInGame(iClient) || IsFakeClient(iClient)) 
		return;

	if(!g_bNewConnect[iClient])
	{
		if (g_dDatabase)
			CheckClientBan(iClient);
		else
		{
			char sSteamID[MAX_STEAMID_LENGTH];
			GetClientAuthId(iClient, TYPE_STEAM, sSteamID, sizeof(sSteamID));
			CheckClientAdmin(iClient, sSteamID);
		}
	}
	else
	{
		if (g_iTargetMuteType[iClient] == TYPEMUTE || g_iTargetMuteType[iClient] == TYPESILENCE)
			FunMute(iClient);
	}
}

public void Event_PlayerDisconnect(Event eEvent, const char[] sEName, bool bDontBroadcast)
{
	int iClient = GetClientOfUserId(eEvent.GetInt("userid"));

	if (!iClient || IsFakeClient(iClient) || g_bBanClientConnect[iClient]) 
	{
		eEvent.BroadcastDisabled = true;
		return;
	}

	ResetFlagAddAdmin(iClient);
	g_bNewConnect[iClient] = false;
	g_bSayReason[iClient] = false;
	g_bSayReasonReport[iClient] = false;
	g_bReportReason[iClient] = false;
	g_iTargetMuteType[iClient] = 0;
	KillTimerMute(iClient);
	KillTimerGag(iClient);
	
	char sSteamID[MAX_STEAMID_LENGTH];
	GetClientAuthId(iClient, TYPE_STEAM, sSteamID, sizeof(sSteamID));
	
	if (GetUserAdmin(iClient) == INVALID_ADMIN_ID) 
	{
		char sName[MAX_NAME_LENGTH],
			 sIP[MAX_IP_LENGTH];

		GetClientName(iClient, sName, sizeof(sName));
		GetClientIP(iClient, sIP, sizeof(sIP));
		SetOflineInfo(sSteamID, sName, sIP);

	#if MADEBUG
		char sTime[64];
		FormatTime(sTime, sizeof(sTime), g_sOffFormatTime, GetTime());
		LogToFile(g_sLogAction,"New: %s %s - %s ; %s.", sName, sSteamID, sIP, sTime);
	#endif
	}
	/*else
	{
		if (g_dDatabase)
			BDSetActivityAdmin(iClient, sSteamID); // new 
	}*/
}

//получение значений конфига
void ReadConfig()
{
	if (!g_smcConfigParser)
		g_smcConfigParser = new SMCParser();
	
	g_smcConfigParser.OnEnterSection = NewSection;
	g_smcConfigParser.OnKeyValue = KeyValue;
	g_smcConfigParser.OnLeaveSection = EndSection;

	char sConfigFile[PLATFORM_MAX_PATH];
	BuildPath(Path_SM, sConfigFile, sizeof(sConfigFile), "configs/materialadmin/materialadmin.cfg");

	if (g_mReasonMMenu)
		g_mReasonMMenu.RemoveAllItems();
	if (g_mReasonBMenu)
		g_mReasonBMenu.RemoveAllItems();
	if (g_mHackingMenu)
		g_mHackingMenu.RemoveAllItems();
	g_tMenuTime.Clear();

	if(FileExists(sConfigFile))
	{
		g_iConfigState = ConfigState_Non;
	
		int iLine;
		SMCError err = g_smcConfigParser.ParseFile(sConfigFile, iLine);
		if (err != SMCError_Okay)
		{
			char sError[256];
			g_smcConfigParser.GetErrorString(err, sError, sizeof(sError));
			LogToFile(g_sLogConfig, "Could not parse file (line %d, file \"%s\"):", iLine, sConfigFile);
			LogToFile(g_sLogConfig, "Parser encountered error: %s", sError);
		}
	}
	else 
	{
		LogToFile(g_sLogConfig, "FATAL *** ERROR *** can not find %s", sConfigFile);
		SetFailState("%sFATAL *** ERROR *** can not find %s", MAPREFIX, sConfigFile);
	}

	CreateTimer(2.0, TimerOnConfigSettingForward);
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
	#if MADEBUG
		LogToFile(g_sLogConfig,"Loaded config. name %s", sName);
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
			else if(strcmp("Website", sKey, false) == 0) 
				strcopy(g_sWebsite, sizeof(g_sWebsite), sValue);
			else if(strcmp("OffTimeFormat", sKey, false) == 0)
				strcopy(g_sOffFormatTime, sizeof(g_sOffFormatTime), sValue);
			else if(strcmp("Addban", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bAddBan = false;
				else
					g_bAddBan = true;
			}
			else if(strcmp("Unban", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bUnBan = false;
				else
					g_bUnBan = true;
			}
			else if(strcmp("OffMapClear", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bOffMapClear = false;
				else
					g_bOffMapClear = true;
			}
			else if(strcmp("Report", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bReport = false;
				else
					g_bReport = true;
			}
			else if(strcmp("BanSayPanel", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bBanSayPanel = false;
				else
					g_bBanSayPanel = true;
			}
			else if(strcmp("ActionOnTheMy", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bActionOnTheMy = false;
				else
					g_bActionOnTheMy = true;
			}
			else if(strcmp("IgnoreBanServer", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bIgnoreBanServer = false;
				else
					g_bIgnoreBanServer = true;
			}
			else if(strcmp("IgnoreMuteServer", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bIgnoreMuteServer = false;
				else
					g_bIgnoreMuteServer = true;
			}
			else if(strcmp("ServerBanTyp", sKey, false) == 0)
			{
				if(StringToInt(sValue) == 0)
					g_bServerBanTyp = false;
				else
					g_bServerBanTyp = true;
			}
			else if(strcmp("MassBan", sKey, false) == 0)
				g_iMassBan = StringToInt(sValue);
			else if(strcmp("ServerBanTime", sKey, false) == 0)
				g_iServerBanTime = StringToInt(sValue);
			else if(strcmp("ServerID", sKey, false) == 0)
				g_iServerID = StringToInt(sValue);
			else if(strcmp("OffMaxPlayers", sKey, false) == 0)
				g_iOffMaxPlayers = StringToInt(sValue);
			else if(strcmp("OffMenuNast", sKey, false) == 0)
				g_iOffMenuItems = StringToInt(sValue);
			else if(strcmp("RetryTime", sKey, false) == 0)
				g_fRetryTime = StringToFloat(sValue);
			else if(strcmp("ShowAdminAction", sKey, false) == 0)
				g_iShowAdminAction = StringToInt(sValue);
			else if(strcmp("BasecommTime", sKey, false) == 0)
				g_iBasecommTime = StringToInt(sValue);
			else if(strcmp("ServerImmune", sKey, false) == 0)
				g_iServerImmune = StringToInt(sValue);
			else if(strcmp("BanTypMenu", sKey, false) == 0)
				g_iBanTypMenu = StringToInt(sValue);
		#if MADEBUG
			LogToFile(g_sLogConfig,"Loaded config. key \"%s\", value \"%s\"", sKey, sValue);
		#endif
		}
		case ConfigState_Reason_Mute:
		{
			g_mReasonMMenu.AddItem(sKey, sValue);
		#if MADEBUG
			LogToFile(g_sLogConfig,"Loaded mute reason. key \"%s\", display_text \"%s\"", sKey, sValue);
		#endif
		}
		case ConfigState_Reason_Ban:
		{
			g_mReasonBMenu.AddItem(sKey, sValue);
		#if MADEBUG
			LogToFile(g_sLogConfig,"Loaded ban reason. key \"%s\", display_text \"%s\"", sKey, sValue);
		#endif
		}
		case ConfigState_Reason_Hacking:
		{
			g_mHackingMenu.AddItem(sKey, sValue);
		#if MADEBUG
			LogToFile(g_sLogConfig,"Loaded hacking reason. key \"%s\", display_text \"%s\"", sKey, sValue);
		#endif
		}
		case ConfigState_Time:
		{
			g_tMenuTime.SetString(sKey, sValue, false);
		#if MADEBUG
			LogToFile(g_sLogConfig,"Loaded time. key \"%s\", display_text \"%s\"", sKey, sValue);
		#endif
		}
	}
	return SMCParse_Continue;
}

public SMCResult EndSection(SMCParser Smc)
{
	return SMCParse_Continue;
}
