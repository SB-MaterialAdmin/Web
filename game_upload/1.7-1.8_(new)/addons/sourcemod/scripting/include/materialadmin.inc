#if defined _materialadmin_included
  #endinput
#endif
#define _materialadmin_included

public SharedPlugin:__pl_materialadmin = 
{
	name = "materialadmin",
	file = "materialadmin.smx",
#if defined REQUIRE_PLUGIN
    required = 1
#else
    required = 0
#endif
};

#if !defined REQUIRE_PLUGIN
public __pl_materialadmin_SetNTVOptional()
{
	MarkNativeAsOptional("MAOffBanPlayer");
	MarkNativeAsOptional("MABanPlayer");
	MarkNativeAsOptional("MAGetAdminExpire");
	MarkNativeAsOptional("MASetClientMuteType");
	MarkNativeAsOptional("MAGetClientMuteType");
}
#endif


/*********************************************************
 * Офлайн бан
 *
 * @param iClient	Клиент ид админа кто банит
 * @param sSteamID	Стим айди кого банят
 * @param sIp		Айпи кого банят
 * @param sName		Ник кого банят
 * @param iTime		Время бана (0 - навсегда) мин
 * @param sReason	Причина
 * @return			true, false
 *********************************************************/
native bool MAOffBanPlayer(int iClient, char[] sSteamID, char[] sIp, char[] sName, int iTime, char[] sReason);

/*********************************************************
 * Бан
 *
 * @param iClient	Клиент ид админа кто банит
 * @param iTarget	Клиент ид кого банят
 * @param iTime		Время бана (0 - навсегда) мин
 * @param sReason	Причина
 * @return			true, false
 *********************************************************/
native bool MABanPlayer(int iClient, int iTarget, int iTime, char[] sReason);

/*********************************************************
 * Временная админка
 *
 * @param idAdmin	ид админа
 * @return			время в UNIX_TIMESTAMP
 *********************************************************/
native int MAGetAdminExpire(AdminId idAdmin);

#define MA_GAG		3
#define MA_MUTE		4
#define MA_SILENCE	5
/*********************************************************
 * Установить мут
 *
 * @param iClient	Клиент ид админа кто банит
 * @param iTarget	Клиент ид кого банят
 * @param iTime		Время бана (0 - навсегда) мин
 * @param sReason	Причина
 * @param iType		Тип (MA_GAG - текстовый чат, MA_MUTE - голосовой чат, MA_SILENCE - всё вместе)
 * @return			true, false
 *********************************************************/
native bool MASetClientMuteType(int iClient, int iTarget, int iTime, char[] sReason, int iType);

/*********************************************************
 * Узнать тип мута
 *
 * @param 	iClient		клиент ид кого проверяем
 * @return 	integer 	value. 0 - нету, 1 - голосовой чат, 2 - текстовый чат, 3 - всё вместе
 *********************************************************/
native int MAGetClientMuteType(int iClient);

/*********************************************************
 * Событие отключение голосовго или текстового чата
 * 
 * @param iClient	клиент ид админа
 * @param iTarget	клиент ид замутеного
 * @param iType		Тип (1 - голосовой чат, 2 - текстовый чат, 3 - всё вместе)
 * @param iTime		время
 * @param sReason	причина
 *********************************************************/
 forward void MAOnClientMuted(int iClient, int iTarget, int iType, int iTime, char[] sReason);
 
 /*********************************************************
 * Событие включение голосовго или текстового чата
 * 
 * @param iClient	клиент ид админа
 * @param iTarget	клиент ид размутеного
 * @param iType		Тип (1 - голосовой чат, 2 - текстовый чат, 3 - всё вместе)
 * @param iTime		время
 * @param sReason	причина
 *********************************************************/
 forward void MAOnClientUnMuted(int iClient, int iTarget, int iType, char[] sReason);
 
 /*********************************************************
 * Событие бана клиента
 * 
 * @param iClient	клиент ид админа
 * @param iTarget	клиент ид забаненого
 * @param iTime		время бана
 * @param sReason	причина бана
 *********************************************************/
 forward void MAOnClientBanned(int iClient, int iTarget, int iTime, char[] sReason);
 
 /*********************************************************
 * Событие добавление бана
 * 
 * @param iClient	клиент ид админа
 * @param sIp		бан айпи
 * @param sSteamID	бан стима
 * @param iTime		время бана
 * @param sReason	причина бана
 *********************************************************/
 forward void MAOnClientAddBanned(int iClient, char[] sIp, char[] sSteamID, int iTime, char[] sReason);
 
  /*********************************************************
 * Событие разбана
 * 
 * @param iClient	клиент ид админа
 * @param sIp		разбан айпи
 * @param sSteamID	разбан стима
 * @param sReason	причина разбана
 *********************************************************/
 forward void MAOnClientUnBanned(int iClient, char[] sIp, char[] sSteamID, char[] sReason);