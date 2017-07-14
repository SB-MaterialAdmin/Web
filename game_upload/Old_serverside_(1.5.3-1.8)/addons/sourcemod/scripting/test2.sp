#pragma semicolon 1

#include <sourcemod>
#include <sourcebans>

public Plugin:myinfo = 
{
	name = "Test2",
	author = "",
	description = "",
	version = "",
	url = ""
};

public OnPluginStart()
{
	LoadTranslations("sourcebans.phrases");	
}

DisplaySomeMenu(client)
{
	new AdminId:admin = GetUserAdmin(client);
	if(admin == INVALID_ADMIN_ID)
		return;
	
	new String:szTimeStr[128];
	new expire = SourceBans_GetAdminExpire(admin);
	if(expire == -1) // not found
		szTimeStr[0] = '\0';
	else if(expire == 0)
		Format(szTimeStr, sizeof(szTimeStr), "%T", "Infinitely", client);
	else
	{
		new left = expire - GetTime();
		new days = left / (60 * 60 * 24);
		new hours = (left - (days * (60 * 60 * 24))) / (60 * 60);
		new minutes = (left - (days * (60 * 60 * 24)) - (hours * (60 * 60))) / 60;
		new len = 0;
		if(days) len += Format(szTimeStr[len], 128 - len, "%d %T", days, "Days", client);
		if(hours) len += Format(szTimeStr[len], 128 - len, "%s%d %T", days ? " " : "", hours, "Hours", client);
		if(minutes) len += Format(szTimeStr[len], 128 - len, "%s%d %T", ((days && !hours) || hours) ? " " : "", minutes, "Minutes", client);
	}
	
	if(szTimeStr[0])
		PrintToChat(client, "%t", "Admin Expire", szTimeStr);
}
