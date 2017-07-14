/**
 * vim: set ts=4 :
 * =============================================================================
 * SourceMod Communication Plugin
 * Provides fucntionality for controlling communication on the server
 *
 * SourceMod (C)2004-2008 AlliedModders LLC.  All rights reserved.
 * =============================================================================
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, version 3.0, as published by the
 * Free Software Foundation.
 * 1
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * As a special exception, AlliedModders LLC gives you permission to link the
 * code of this program (as well as its derivative works) to "Half-Life 2," the
 * "Source Engine," the "SourcePawn JIT," and any Game MODs that run on software
 * by the Valve Corporation.  You must obey the GNU General Public License in
 * all respects for all other code used.  Additionally, AlliedModders LLC grants
 * this exception to all derivative works.  AlliedModders LLC defines further
 * exceptions, found in LICENSE.txt (as of this writing, version JULY-31-2007),
 * or <http://www.sourcemod.net/license.php>.
 *
 * Version: $Id$
 */

#include <sourcemod>

#undef REQUIRE_PLUGIN
#include <materialadmin>

#pragma semicolon 1
#pragma newdecls required

public Plugin myinfo =
{
	name = "Basic Comm Control",
	author = "AlliedModders LLC",
	description = "fake",
	version = MAVERSION,
	url = "http://www.sourcemod.net/"
};

bool g_bMuted[MAXPLAYERS+1];			// Is the player muted?
bool g_bGagged[MAXPLAYERS+1];		// Is the player gagged?


public APLRes AskPluginLoad2(Handle myself, bool late, char[] error, int err_max)
{
	CreateNative("BaseComm_IsClientGagged", Native_IsClientGagged);
	CreateNative("BaseComm_IsClientMuted",  Native_IsClientMuted);
	CreateNative("BaseComm_SetClientGag",   Native_SetClientGag);
	CreateNative("BaseComm_SetClientMute",  Native_SetClientMute);
	RegPluginLibrary("basecomm");
	
	return APLRes_Success;
}

void FireOnClientMute(int iClient, bool bState)
{
 	static Handle hForward;
	
	if(hForward == null)
		hForward = CreateGlobalForward("BaseComm_OnClientMute", ET_Ignore, Param_Cell, Param_Cell);
	
	Call_StartForward(hForward);
	Call_PushCell(iClient);
	Call_PushCell(bState);
	Call_Finish();
}
 
void FireOnClientGag(int iClient, bool bState)
{
 	static Handle hForward;
	
	if(hForward == null)
		hForward = CreateGlobalForward("BaseComm_OnClientGag", ET_Ignore, Param_Cell, Param_Cell);
	
	Call_StartForward(hForward);
	Call_PushCell(iClient);
	Call_PushCell(bState);
	Call_Finish();
}

public int Native_IsClientGagged(Handle hPlugin, int numParams)
{
	int iClient = GetNativeCell(1);
	if (iClient < 1 || iClient > MaxClients)
		return ThrowNativeError(SP_ERROR_NATIVE, "Invalid Client index %d", iClient);
	
	if (!IsClientInGame(iClient))
		return ThrowNativeError(SP_ERROR_NATIVE, "Client %d is not in game", iClient);
	
	return g_bGagged[iClient];
}

public int Native_IsClientMuted(Handle hPlugin, int numParams)
{
	int iClient = GetNativeCell(1);
	if (iClient < 1 || iClient > MaxClients)
		return ThrowNativeError(SP_ERROR_NATIVE, "Invalid Client index %d", iClient);
	
	if (!IsClientInGame(iClient))
		return ThrowNativeError(SP_ERROR_NATIVE, "Client %d is not in game", iClient);
	
	return g_bMuted[iClient];
}

public int Native_SetClientGag(Handle hPlugin, int numParams)
{
	int iClient = GetNativeCell(1);
	if (iClient < 1 || iClient > MaxClients)
		return ThrowNativeError(SP_ERROR_NATIVE, "Invalid Client index %d", iClient);
	
	if (!IsClientInGame(iClient))
		return ThrowNativeError(SP_ERROR_NATIVE, "Client %d is not in game", iClient);
	
	bool bState = GetNativeCell(2);
	
	if (bState)
	{
		if (g_bGagged[iClient])
			return false;
	}
	else
	{
		if (!g_bGagged[iClient])
			return false;
	}
	
	g_bGagged[iClient] = bState;
	FireOnClientGag(iClient, bState);
	
	return true;
}

public int Native_SetClientMute(Handle hPlugin, int numParams)
{
	int iClient = GetNativeCell(1);
	if (iClient < 1 || iClient > MaxClients)
		return ThrowNativeError(SP_ERROR_NATIVE, "Invalid Client index %d", iClient);
	
	if (!IsClientInGame(iClient))
		return ThrowNativeError(SP_ERROR_NATIVE, "Client %d is not in game", iClient);
	
	bool bState = GetNativeCell(2);
	
	if (bState)
	{
		if (g_bMuted[iClient])
			return false;
	}
	else
	{
		if (!g_bMuted[iClient])
			return false;
	}
	
	g_bMuted[iClient] = bState;
	FireOnClientMute(iClient, bState);
	
	return true;
}