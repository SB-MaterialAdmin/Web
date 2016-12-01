void SBCreateMenu()
{
	g_mReasonBMenu = new Menu(MenuHandler_MenuBReason);
	g_mReasonBMenu.ExitBackButton = true;
	
	g_mReasonMMenu = new Menu(MenuHandler_MenuMReason);
	g_mReasonMMenu.ExitBackButton = true;

	g_mHackingMenu = new Menu(MenuHandler_MenuHacking);
	g_mHackingMenu.ExitBackButton = true;

	g_mTimeMenu = new Menu(MenuHandler_MenuTime);
	g_mTimeMenu.ExitBackButton = true;
}

public void OnAdminMenuReady(Handle aTopMenu)
{
	TopMenu aTopMenus = TopMenu.FromHandle(aTopMenu);

	if (aTopMenus == g_tmAdminMenu)
		return;

	g_tmAdminMenu = aTopMenus;
	
	TopMenuObject CategoryId = g_tmAdminMenu.AddCategory("materialadmin", Handle_Sourcebans, "materialadmin", ADMFLAG_GENERIC);

	if (CategoryId == INVALID_TOPMENUOBJECT)
		return;
	
	g_tmAdminMenu.AddItem("ma_target_online", Handle_MenuTargetOnline, CategoryId, "target_online", ADMFLAG_BAN|ADMFLAG_CHAT);
	g_tmAdminMenu.AddItem("ma_target_offline", Handle_MenuTargetOffline, CategoryId, "target_offline", ADMFLAG_BAN|ADMFLAG_CHAT);
	g_tmAdminMenu.AddItem("ma_setting", Handle_MenuSetting, CategoryId, "Setting", ADMFLAG_ROOT);
}

public void Handle_Sourcebans(Handle topmenu, TopMenuAction action, TopMenuObject topobj_id, int iClient, char[] buffer, int maxlength)
{
	switch(action)
	{
		case TopMenuAction_DisplayOption: 	FormatEx(buffer, maxlength, "%T", "AdminMenu_Main", iClient);
		case TopMenuAction_DisplayTitle: 	FormatEx(buffer, maxlength, "%T:", "AdminMenu_Main", iClient);
	}
}

public void Handle_MenuTargetOnline(Handle topmenu, TopMenuAction action, TopMenuObject object_id, int iClient, char[] sBuffer, int maxlength)
{
	switch(action)
	{
		case TopMenuAction_DisplayOption: FormatEx(sBuffer, maxlength, "%T", "OnlineTitle", iClient);
		case TopMenuAction_SelectOption: 
		{
			g_aUserId[iClient].Clear();
			ShowTargetOnline(iClient);
		}
	}
}

public void Handle_MenuTargetOffline(Handle topmenu, TopMenuAction action, TopMenuObject object_id, int iClient, char[] sBuffer, int maxlength)
{
	switch(action)
	{
		case TopMenuAction_DisplayOption: FormatEx(sBuffer, maxlength, "%T", "OfflineTitle", iClient);
		case TopMenuAction_SelectOption: BdTargetOffline(iClient);
	}
}

public void Handle_MenuSetting(Handle topmenu, TopMenuAction action, TopMenuObject object_id, int iClient, char[] sBuffer, int maxlength)
{
	switch(action)
	{
		case TopMenuAction_DisplayOption: FormatEx(sBuffer, maxlength, "%T", "SettingTitle", iClient);
		case TopMenuAction_SelectOption: ShowSetting(iClient);
	}
}

void ShowSetting(int iClient)
{
	Menu Mmenu = new Menu(MenuHandler_Setting);
	Mmenu.SetTitle("%T:", "SettingTitle", iClient);
	
	char sTitle[128];
	FormatEx(sTitle, sizeof(sTitle), "%T", "ReheshAdmin", iClient);
	Mmenu.AddItem("", sTitle);
	
	Mmenu.ExitBackButton = true;
	Mmenu.Display(iClient, MENU_TIME_FOREVER);
}

public int MenuHandler_Setting(Menu Mmenu, MenuAction mAction, int iClient, int iSlot) 
{
	switch(mAction)
	{
		case MenuAction_End: delete Mmenu;
		case MenuAction_Cancel:
		{
			if (iSlot == MenuCancel_ExitBack && g_tmAdminMenu != null)
				g_tmAdminMenu.Display(iClient, TopMenuPosition_LastCategory);
		}
		case MenuAction_Select:
		{
			if (iSlot == 0)
			{
				AdminHash();
				PrintToChat2(iClient, "%T",  "ReheshAdminOk", iClient);
			}
		}
	}
}

//меню выбора игрока офлайн
public void ShowTargetOffline(Database db, DBResultSet dbRs, const char[] sError, any iClient)
{
	if(dbRs == null || sError[0])
	{
		LogError("Error loading offline (%s)", sError);
		return;
	}

	if(!IsClientInGame(iClient))
		return;
	
	Menu Mmenu = new Menu(MenuHandler_OfflineList);
	Mmenu.SetTitle("%T:", "SelectPlayerTitle", iClient);
	char sTitle[128];

	if (dbRs.RowCount)
	{
		char sName[MAX_NAME_LENGTH],
			 sSteamID[MAX_STEAMID_LENGTH],
			 sID[12],
			 sTime[64];

		while(dbRs.FetchRow())
		{
			dbRs.FetchString(0, sID, sizeof(sID));
			dbRs.FetchString(1, sSteamID, sizeof(sSteamID));
			dbRs.FetchString(2, sName, sizeof(sName));
			FormatTime(sTime, sizeof(sTime), g_sOffFormatTime, dbRs.FetchInt(3));
			switch(g_iOffMenuItems)
			{
				case 1:	FormatEx(sTitle, sizeof(sTitle), "%s (%s)", sName, sTime);
				case 2: FormatEx(sTitle, sizeof(sTitle), "%s (%s)", sName, sSteamID); 
				case 3: FormatEx(sTitle, sizeof(sTitle), "%s - %s (%s)", sName, sSteamID, sTime); 
			}
			Mmenu.AddItem(sID, sTitle);
			#if DEBUG
				LogToFile(g_sLogFile,"Menu: %s, %s - %s", sID, sSteamID, sTitle);
			#endif
		}
	}
	else
	{
		FormatEx(sTitle, sizeof(sTitle), "%T", "No players history", iClient);
		Mmenu.AddItem("", sTitle, ITEMDRAW_DISABLED);	
	}
	
	Mmenu.ExitBackButton = true;
	Mmenu.Display(iClient, MENU_TIME_FOREVER);
}

public int MenuHandler_OfflineList(Menu Mmenu, MenuAction mAction, int iClient, int iSlot) 
{
	switch(mAction)
	{
		case MenuAction_End: delete Mmenu;
		case MenuAction_Cancel:
		{
			if (iSlot == MenuCancel_ExitBack && g_tmAdminMenu != null)
				g_tmAdminMenu.Display(iClient, TopMenuPosition_LastCategory);
		}
		case MenuAction_Select:
		{
			char sID[12];
			Mmenu.GetItem(iSlot, sID, sizeof(sID));
			
			g_bOnileTarget[iClient] = false;
			#if DEBUG
				LogToFile(g_sLogFile, "Menu BanList: %s", sID);
			#endif
			
			BdGetInfoOffline(iClient, StringToInt(sID));
		}
	}
}
// online
void ShowTargetOnline(int iClient)
{
	char sTitle[192];
	bool bIsClien = false;
	
	Menu Mmenu = new Menu(MenuHandler_OnlineList);
	Mmenu.SetTitle("%T:", "SelectPlayerTitle", iClient);
	
	if (CheckAdminFlags(iClient, ADMFLAG_ROOT))
	{
		FormatEx(sTitle, sizeof(sTitle), "%T", "All", iClient);
		Mmenu.AddItem("-1", sTitle);
	}
	
	if(g_iGameTyp == GAMETYP_TF2)
		FormatEx(sTitle, sizeof(sTitle), "%T", "Blue", iClient);
	else
		FormatEx(sTitle, sizeof(sTitle), "%T", "CT", iClient);
	Mmenu.AddItem("-2", sTitle);
	
	if(g_iGameTyp == GAMETYP_TF2)
		FormatEx(sTitle, sizeof(sTitle), "%T", "Red", iClient);
	else
		FormatEx(sTitle, sizeof(sTitle), "%T", "T", iClient);
	Mmenu.AddItem("-3", sTitle);
	
	if (g_aUserId[iClient].Length != 0)
	{
		FormatEx(sTitle, sizeof(sTitle), "%T", "Clients", iClient);
		Mmenu.AddItem("0", sTitle);
	}
	for (int i = 1; i <= MaxClients; i++)
	{
		if (IsClientInGame(i) && !IsFakeClient(i) && GetUserAdmin(i) == INVALID_ADMIN_ID)
		{
			AdminMenuAddClients(Mmenu, iClient, i);
			bIsClien = true;
		}
	}
	
	if (!bIsClien)
	{
		if (CheckAdminFlags(iClient, ADMFLAG_ROOT))
			Mmenu.RemoveItem(2);
		Mmenu.RemoveItem(1);
		Mmenu.RemoveItem(0);
		FormatEx(sTitle, sizeof(sTitle), "%T", "no target", iClient);
		Mmenu.AddItem("", sTitle, ITEMDRAW_DISABLED);
	}
	Mmenu.ExitBackButton = true;
	Mmenu.Display(iClient, MENU_TIME_FOREVER);
}

void AdminMenuAddClients(Menu Mmenu, int iClient, int iTarget)
{
	char sTitle[128],
		sBuffer[128],
		sOption[32];
	int iUserId = GetClientUserId(iTarget);
	FormatEx(sOption, sizeof(sOption), "%d", iUserId);
	
#if DEBUG
	LogToFile(g_sLogFile,"add clients menu: admin %d -  target %d userid %d", iClient, iTarget, iUserId);
#endif

	int iPos = g_aUserId[iClient].FindValue(iUserId);
	if (iPos > -1)
		FormatEx(sBuffer, sizeof(sBuffer), "[v] %N (%d)", iTarget, iUserId);
	else
		FormatEx(sBuffer, sizeof(sBuffer), "[ ] %N (%d)", iTarget, iUserId);
	
	switch(g_iTargetMuteType[iClient])
	{
		case 0: FormatEx(sTitle, sizeof(sTitle), "[ ] %s", sBuffer);
		case 1: FormatEx(sTitle, sizeof(sTitle), "[m] %s", sBuffer);
		case 2: FormatEx(sTitle, sizeof(sTitle), "[g] %s", sBuffer);
		case 3: FormatEx(sTitle, sizeof(sTitle), "[s] %s", sBuffer);
	}

	Mmenu.AddItem(sOption, sTitle);
}

public int MenuHandler_OnlineList(Menu Mmenu, MenuAction mAction, int iClient, int iSlot) 
{
	switch(mAction)
	{
		case MenuAction_End: delete Mmenu;
		case MenuAction_Cancel:
		{
			if (iSlot == MenuCancel_ExitBack && g_tmAdminMenu != null)
				g_tmAdminMenu.Display(iClient, TopMenuPosition_LastCategory);
		}
		case MenuAction_Select:
		{
			char sOption[32];
			Mmenu.GetItem(iSlot, sOption, sizeof(sOption));

			g_iTarget[iClient][TTYPE] = StringToInt(sOption);
			
			if (g_iTarget[iClient][TTYPE] > 0)
			{
				int iPos = g_aUserId[iClient].FindValue(g_iTarget[iClient][TTYPE]);
				if (iPos > -1)
					g_aUserId[iClient].Erase(iPos);
				else
					g_aUserId[iClient].Push(g_iTarget[iClient][TTYPE]);
				ShowTargetOnline(iClient);
			}
			else
			{
				g_bOnileTarget[iClient] = true;
				ShowTypeMenu(iClient);
			}
		}
	}
}

void ShowTypeMenu(int iClient)
{
	char sTitle[192];

	Menu Mmenu = new Menu(MenuHandler_MenuType);
	Mmenu.SetTitle("%T:", "SetTitle", iClient);
	
	FormatEx(sTitle, sizeof(sTitle), "%T", "SetBan", iClient); // ban
	Mmenu.AddItem("", sTitle);

	FormatEx(sTitle, sizeof(sTitle), "%T", "SetMute", iClient); // mute
	Mmenu.AddItem("", sTitle);
	
	Mmenu.ExitBackButton = true;
	Mmenu.Display(iClient, MENU_TIME_FOREVER);
}

public int MenuHandler_MenuType(Menu Mmenu, MenuAction mAction, int iClient, int iSlot) 
{
	switch(mAction)
	{
		case MenuAction_End: delete Mmenu;
		case MenuAction_Cancel:
		{
			if (iSlot == MenuCancel_ExitBack && g_tmAdminMenu != null)
				g_tmAdminMenu.Display(iClient, TopMenuPosition_Start);
		}
		case MenuAction_Select:
		{
			switch(iSlot)
			{
				case 0:
				{
					g_iTargetType[iClient] = TYPE_BAN;
					ShowTimeMenu(iClient);
				}
				case 1: 
				{
					if(g_bOnileTarget[iClient])
						ShowTypeMuteMenu(iClient);
					else
						BdGetMuteType(iClient, 0, 0);
				}
			}
		}
	}
}

void ShowTypeMuteMenu(int iClient)
{
	char sTitle[192];

	Menu Mmenu = new Menu(MenuHandler_MenuTypeMute);
	Mmenu.SetTitle("%T:", "SetTitle", iClient);
	
	if(g_bOnileTarget[iClient])
	{
		if (g_aUserId[iClient].Length == 1)
		{
			int iTarget = GetClientOfUserId(g_aUserId[iClient].Get(0));
			MenuTypeAdd(iClient, iTarget, Mmenu);
		}
		else
		{
			FormatEx(sTitle, sizeof(sTitle), "%T", "Mute", iClient); // мут
			Mmenu.AddItem("", sTitle);

			FormatEx(sTitle, sizeof(sTitle), "%T", "Gag", iClient); // чат
			Mmenu.AddItem("", sTitle);
			
			FormatEx(sTitle, sizeof(sTitle), "%T", "Silence", iClient); // силенце
			Mmenu.AddItem("", sTitle);

			FormatEx(sTitle, sizeof(sTitle), "%T", "unMute", iClient); // ун мут
			Mmenu.AddItem("", sTitle);
			
			FormatEx(sTitle, sizeof(sTitle), "%T", "unGag", iClient); // ун чат
			Mmenu.AddItem("", sTitle);

			FormatEx(sTitle, sizeof(sTitle), "%T", "unSilence", iClient); // ун силенце
			Mmenu.AddItem("", sTitle);
		}
	}
	else
		MenuTypeAdd(iClient, 0, Mmenu);
	
	Mmenu.ExitBackButton = true;
	Mmenu.Display(iClient, MENU_TIME_FOREVER);
}

void MenuTypeAdd(int iClient, int iTarget, Menu Mmenu)
{
	char sTitle[192];
	FormatEx(sTitle, sizeof(sTitle), "%T", "Mute", iClient); // мут
	Mmenu.AddItem("", sTitle);

	FormatEx(sTitle, sizeof(sTitle), "%T", "Gag", iClient); // чат
	Mmenu.AddItem("", sTitle);

	FormatEx(sTitle, sizeof(sTitle), "%T", "Silence", iClient); // силенце
	Mmenu.AddItem("", sTitle);

	FormatEx(sTitle, sizeof(sTitle), "%T", "unMute", iClient); // ун мут
	if(g_iTargetMuteType[iTarget] == TYPEMUTE || g_iTargetMuteType[iTarget] == TYPESILENCE)
		Mmenu.AddItem("", sTitle);
	else
		Mmenu.AddItem("", sTitle, ITEMDRAW_DISABLED);

	FormatEx(sTitle, sizeof(sTitle), "%T", "unGag", iClient); // ун чат
	if(g_iTargetMuteType[iTarget] == TYPEGAG || g_iTargetMuteType[iTarget] == TYPESILENCE)
		Mmenu.AddItem("", sTitle);
	else
		Mmenu.AddItem("", sTitle, ITEMDRAW_DISABLED);

	FormatEx(sTitle, sizeof(sTitle), "%T", "unSilence", iClient); // ун силенце
	if(g_iTargetMuteType[iTarget] == TYPESILENCE)
		Mmenu.AddItem("", sTitle);
	else
		Mmenu.AddItem("", sTitle, ITEMDRAW_DISABLED);
}

public int MenuHandler_MenuTypeMute(Menu Mmenu, MenuAction mAction, int iClient, int iSlot) 
{
	switch(mAction)
	{
		case MenuAction_End: delete Mmenu;
		case MenuAction_Cancel:
		{
			if (iSlot == MenuCancel_ExitBack && g_tmAdminMenu != null)
				g_tmAdminMenu.Display(iClient, TopMenuPosition_Start);
		}
		case MenuAction_Select:
		{
			switch(iSlot)
			{
				case 0:
				{
					g_iTargetType[iClient] = TYPE_MUTE;
					ShowTimeMenu(iClient);
				}
				case 1:
				{
					g_iTargetType[iClient] = TYPE_GAG;
					ShowTimeMenu(iClient);
				}
				case 2:
				{
					g_iTargetType[iClient] = TYPE_SILENCE;
					ShowTimeMenu(iClient);
				}
				case 3: 
				{
					g_iTargetType[iClient] = TYPE_UNMUTE;
					g_sTarget[iClient][TREASON] = "Нет причины";
					OnlineClientSet(iClient);
				}
				case 4:
				{
					g_iTargetType[iClient] = TYPE_UNGAG;
					g_sTarget[iClient][TREASON] = "Нет причины";
					OnlineClientSet(iClient);
				}
				case 5:
				{
					g_iTargetType[iClient] = TYPE_UNSILENCE;
					g_sTarget[iClient][TREASON] = "Нет причины";
					OnlineClientSet(iClient);
				}
			}
		#if DEBUG
			LogToFile(g_sLogFile,"Menu TypeMute: slot %i , type %d", iSlot, g_iTargetType[iClient]);
		#endif
		}
	}
}

//меню выбора времени бана
void ShowTimeMenu(int iClient)
{
	char sTitle[128],
		 sBuffer[12];

	g_mTimeMenu.SetTitle("%T:", "SelectTimeTitle", iClient);

	int iCount = g_mTimeMenu.ItemCount;
	for (int i = 0; i < iCount; i++)
	{
		g_mTimeMenu.GetItem(i, sBuffer, sizeof(sBuffer), _, sTitle, sizeof(sTitle));
		#if DEBUG
			LogToFile(g_sLogFile,"Menu time: %i , %s, %s", i, sBuffer, sTitle);
		#endif
		if(StringToInt(sBuffer) == 0)
		{
			#if DEBUG
				LogToFile(g_sLogFile,"Menu time: yes %i , %s, %s", i, sBuffer, sTitle);
			#endif
			if (GetUserFlagBits(iClient) & (ADMFLAG_UNBAN | ADMFLAG_ROOT))
			{
				g_mTimeMenu.RemoveItem(i);
				g_mTimeMenu.InsertItem(i, sBuffer, sTitle);
			}
			else
			{
				g_mTimeMenu.RemoveItem(i);
				g_mTimeMenu.InsertItem(i, sBuffer, sTitle, ITEMDRAW_DISABLED);
			}
		}
		else if (StringToInt(sBuffer) == -1)
		{
			if (g_iTargetType[iClient] == TYPE_BAN)
			{
				g_mTimeMenu.RemoveItem(i);
				g_mTimeMenu.InsertItem(i, sBuffer, sTitle, ITEMDRAW_DISABLED);
			}
			else
			{
				g_mTimeMenu.RemoveItem(i);
				g_mTimeMenu.InsertItem(i, sBuffer, sTitle);
			}
		}
	}

	g_mTimeMenu.Display(iClient, MENU_TIME_FOREVER);
}

public int MenuHandler_MenuTime(Menu Mmenu, MenuAction mAction, int iClient, int iSlot) 
{
	switch(mAction)
	{
		case MenuAction_Cancel:
		{
			if (iSlot == MenuCancel_ExitBack && g_tmAdminMenu != null)
			{
				if (g_bOnileTarget[iClient])
					ShowTargetOnline(iClient);
				else
					BdTargetOffline(iClient);
			}
		}
		case MenuAction_Select:
		{
			char sInfo[12];
			Mmenu.GetItem(iSlot, sInfo, sizeof(sInfo));
			g_iTarget[iClient][TTIME] = StringToInt(sInfo);
			#if DEBUG
				LogToFile(g_sLogFile,"Menu Time: %s", sInfo);
			#endif

			if(g_iTargetType[iClient] == TYPE_BAN)
				ShowBanReasonMenu(iClient);
			else
				ShowMuteReasonMenu(iClient);
		}
	}
}

void ShowMuteReasonMenu(int iClient)
{
	g_mReasonMMenu.SetTitle("%T:", "SelectReasonTitle", iClient);
	g_mReasonMMenu.Display(iClient, MENU_TIME_FOREVER);
}

//меню выбора причины бана
void ShowBanReasonMenu(int iClient)
{
	g_mReasonBMenu.SetTitle("%T:", "SelectReasonTitle", iClient);
	g_mReasonBMenu.Display(iClient, MENU_TIME_FOREVER);
}

public int MenuHandler_MenuBReason(Menu Mmenu, MenuAction mAction, int iClient, int iSlot) 
{
	switch(mAction)
	{
		case MenuAction_Cancel:
		{
			if (iSlot == MenuCancel_ExitBack && g_tmAdminMenu != null)
				ShowTimeMenu(iClient);
		}
		case MenuAction_Select:
		{
			char sInfo[128];
			Mmenu.GetItem(iSlot, sInfo, sizeof(sInfo));
			if(StrEqual("Hacking", sInfo))
			{
				ShowHackingMenu(iClient);
				return;
			}
			if(StrEqual("Own Reason", sInfo))
			{
				PrintToChat2(iClient, "%T", "Say reason", iClient);
				g_bSayReason[iClient] = true;
				return;
			}
			strcopy(g_sTarget[iClient][TREASON], sizeof(sInfo), sInfo);
			#if DEBUG
				LogToFile(g_sLogFile,"Menu Reason: %s", sInfo);
			#endif
			OnlineClientSet(iClient);
		}
	}
}

public int MenuHandler_MenuMReason(Menu Mmenu, MenuAction mAction, int iClient, int iSlot) 
{
	switch(mAction)
	{
		case MenuAction_Cancel:
		{
			if (iSlot == MenuCancel_ExitBack && g_tmAdminMenu != null)
				ShowTimeMenu(iClient);
		}
		case MenuAction_Select:
		{
			char sInfo[128];
			Mmenu.GetItem(iSlot, sInfo, sizeof(sInfo));
			if(StrEqual("Own Reason", sInfo))
			{
				PrintToChat2(iClient, "%T", "Say reason", iClient);
				g_bSayReason[iClient] = true;
				return;
			}
			strcopy(g_sTarget[iClient][TREASON], sizeof(sInfo), sInfo);
			#if DEBUG
				LogToFile(g_sLogFile,"Menu Reason: %s", sInfo);
			#endif
			OnlineClientSet(iClient);
		}
	}
}

void ShowHackingMenu(int iClient)
{
	g_mHackingMenu.SetTitle("%T - %s", "SelectReasonTitle", iClient, g_sTarget[iClient][TNAME]);
	g_mHackingMenu.Display(iClient, MENU_TIME_FOREVER);
}

public int MenuHandler_MenuHacking(Menu Mmenu, MenuAction mAction, int iClient, int iSlot) 
{
	switch(mAction)
	{
		case MenuAction_Cancel:
		{
			if (iSlot == MenuCancel_ExitBack && g_tmAdminMenu != null)
				ShowBanReasonMenu(iClient);
		}
		case MenuAction_Select:
		{
			char sInfo[128];
			Mmenu.GetItem(iSlot, sInfo, sizeof(sInfo));
			strcopy(g_sTarget[iClient][TREASON], sizeof(sInfo), sInfo);
			#if DEBUG
				LogToFile(g_sLogFile,"Menu Hacking: %s", sInfo);
			#endif

			OnlineClientSet(iClient);
		}
	}
}

void OnlineClientSet(int iClient)
{
	if (g_bOnileTarget[iClient])
	{
	#if DEBUG
		LogToFile(g_sLogFile,"Online client set: client %d, tip %d", iClient, g_iTarget[iClient][TTYPE]);
	#endif
		GetClientToBd(iClient, g_iTarget[iClient][TTYPE]);
	}
	else
	{
	#if DEBUG
		LogToFile(g_sLogFile,"Offline client set: client %d", iClient);
	#endif
		DoCreateDB(iClient, 0);
	}
}
//--------------------------------------------------------------------------------------------------
// репорт меню
void ReportMenu(int iClient)
{
	char sTitle[192],
		sOptions[12];
	Menu Mmenu = new Menu(MenuHandler_ReportMenu);
	Mmenu.SetTitle("%T:", "SelectPlayerTitle", iClient);
	
	for (int i = 1; i <= MaxClients; i++)
	{
		if(IsClientInGame(i) && !IsFakeClient(i) && i != iClient && GetUserAdmin(i) == INVALID_ADMIN_ID)
		{
			FormatEx(sTitle, sizeof(sTitle), "%N", i);
			FormatEx(sOptions, sizeof(sOptions), "%d", GetClientUserId(i));
			Mmenu.AddItem(sOptions, sTitle);
		}
	}
	
	if(Mmenu.ItemCount == 0)
	{
		FormatEx(sTitle, sizeof(sTitle), "%T", "no target", iClient);
		Mmenu.AddItem("", sTitle, ITEMDRAW_DISABLED);
	}
	Mmenu.ExitButton = true;
	Mmenu.Display(iClient, MENU_TIME_FOREVER);
}

public int MenuHandler_ReportMenu(Menu Mmenu, MenuAction mAction, int iClient, int iSlot) 
{
	switch(mAction)
	{
		case MenuAction_End: delete Mmenu;
		case MenuAction_Select:
		{
			char sInfo[12];
			Mmenu.GetItem(iSlot, sInfo, sizeof(sInfo));
			
			g_iTargetReport[iClient] = StringToInt(sInfo);
			PrintToChat2(iClient, "%T", "Say reason", iClient);
			g_bSayReasonReport[iClient] = true;
		}
	}
}