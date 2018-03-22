<?php
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
//   SourceBans 1.4.11
//   Copyright (C) 2007-2015 SourceBans Team - Part of GameConnect
//   Licensed under GNU GPL version 3, or later.
//   Page: <http://www.sourcebans.net/> - <https://github.com/GameConnect/sourcebansv1>
//
// *************************************************************************

/**
* Extended substr function. If it finds mbstring extension it will use, else 
* it will use old substr() function
*
* @param string $string String that need to be fixed
* @param integer $start Start extracting from
* @param integer $length Extract number of characters
* @return string
*/

if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();}


function substr_utf($string, $start = 0, $length = null) {
$start = (integer) $start >= 0 ? (integer) $start : 0;
if(is_null($length)) 
	$length = strlen_utf($string) - $start;
    return substr($string, $start, $length); 
} 

/**
* Equivalent to htmlspecialchars(), but allows &#[0-9]+ (for unicode)
* This function was taken from punBB codebase <http://www.punbb.org/>
*
* @param string $str
* @return string
*/
function clean($str) {
	$str = preg_replace('/&(?!#[0-9]+;)/s', '&amp;', $str);
	$str = str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $str);
	return $str;
}

/**
* Check if selected email has valid email format
*
* @param string $user_email Email address
* @return boolean
*/
function is_valid_email($user_email) {
	$chars = EMAIL_FORMAT;
	if(strstr($user_email, '@') && strstr($user_email, '.')) {
		return (boolean) preg_match($chars, $user_email);
	}else{
		return false;
	}
}

/**
 * Returns the full location that the website is running in
 *
 * @return string location of SourceBans
 */
function GetLocation()
{
	return substr($_SERVER['SCRIPT_FILENAME'], 0, strlen($base)-strlen("index.php"));
}

/**
 * Displays the header of SourceBans
 *
 * @return noreturn
 */
function BuildPageHeader()
{
	include TEMPLATES_PATH . "/header.php";
}

/**
 * Displays the sub-nav menu of SourceBans
 *
 * @return noreturn
 */
function BuildSubMenu()
{
	include TEMPLATES_PATH . "/submenu.php";
}

/**
 * Displays the content header
 *
 * @return noreturn
 */
function BuildContHeader()
{
	if(!isset($_GET['s']))
	{
		$page = "<b>".(isset($GLOBALS['pagetitle'])?$GLOBALS['pagetitle']:'')."</b>";
	}
	include TEMPLATES_PATH . "/content.header.php";
}


/**
 * Adds a tab to the page
 *
 * @param string $title The title of the tab
 * @param string $utl The link of the tab
 * @param boolean $active Is the tab active?
 * @return noreturn
 */

function AddTab($title, $url, $desc, $active=false)
{
	global $tabs;
	$tab_arr = array(	);
	$tab_arr[0] = "Dashboard";
	$tab_arr[1] = "Ban List";
	$tab_arr[2] = "Servers";
	$tab_arr[3] = "Submit a ban";
	$tab_arr[4] = "Protest a ban";
	$tabs = array();
	$tabs['title'] = $title;
	$tabs['url'] = $url;
	$tabs['desc'] = $desc;
	if(!isset($_GET['p']) && $title == $tab_arr[isset($GLOBALS['config'])?intval($GLOBALS['config']['config.defaultpage']):0])
	{
		$tabs['active'] = true;
		$GLOBALS['pagetitle'] = $title;
	}
	else 
	{
		if(isset($_GET['p']) && substr($url, 3) == $_GET['p'])
		{
			$tabs['active'] = true;
			$GLOBALS['pagetitle'] = $title;
		}
		else
		{
				$tabs['active'] = false;
		}
	}	
	include TEMPLATES_PATH . "/tab.php";
}

/**
 * Displays the pagetabs
 *
 * @return noreturn
 */
function BuildPageTabs()
{
	AddTab("<i class='zmdi zmdi-globe'></i> SourceBans", "http://www.sourcebans.net", "");
	AddTab("<i class='zmdi zmdi-flower-alt'></i>SourceMod", "http://www.sourcemod.net", "");
}

/**
 * Creates an anchor tag, and adds tooltip code if needed
 *
 * @param string $title The title of the tooltip/text to link
 * @param string $url The link
 * @param string $tooltip The tooltip message
 * @param string $target The new links target
 * @return noreturn
 */
function CreateLink($title, $url, $tooltip="", $target="_self", $wide=false)
{
	if($wide)
		$class = "perm";
	else 
		$class = "tip";
	if(strlen($tooltip) == 0)
	{
		echo '<a href="' . $url . '" target="' . $target . '">' . $title .' </a>';
	}else{
		echo '<a href="' . $url . '" class="' . $class .'" title="' .  $title . ' :: ' .  $tooltip . '" target="' . $target . '">' . $title .' </a>';
	}
}

/**
 * Creates an anchor tag, and adds tooltip code if needed
 *
 * @param string $title The title of the tooltip/text to link
 * @param string $url The link
 * @param string $tooltip The tooltip message
 * @param string $target The new links target
 * @return URL
 */
function CreateLinkR($title, $url, $tooltip="", $target="_self", $wide=false, $onclick)
{
	if($wide)
		$class = "perm";
	else 
		$class = "tip";
	if(strlen($tooltip) == 0)
	{
		return '<a href="' . $url . '" onclick="' . $onclick . '" target="' . $target . '">' . $title .' </a>';
	}else{
		return '<a href="' . $url . '" class="' . $class .'" title="' .  $title . ' :: ' .  $tooltip . '" target="' . $target . '">' . $title .' </a>';
	}
}

function HelpIcon($title, $text)
{
	//return '<img border="0" align="absbottom" src="images/admin/help.png" class="tip" title="' .  $title . ' :: ' .  $text . '">&nbsp;&nbsp;';
	return '<img border="0" align="absbottom" src="../images/help.png" style="float:left;" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="' .  $text . '" title="" data-original-title="' .  $title. '">&nbsp;&nbsp;';
}

/**
 * Allows the title of the page to change wherever the code is being executed from
 *
 * @param string $title The new title
 * @return noreturn
 */
function RewritePageTitle($title)
{
	$GLOBALS['TitleRewrite'] = $title;
}

/**
 * Build sub-menu
 *
 * @param array $el The array of elements for the menu
 * @return noreturn
 */
function SubMenu($el)
{
	$output = "";
	foreach($el AS $e)
	{
		$output .= "<a class=\"nav_link\" href=\"" . $e['url'] . "\">" . $e['title']. "</a>";
	}
	$GLOBALS['NavRewrite'] = $output;
}

function PrintArray($array)
{
	echo "<pre>";
		print_r($array);
	echo "</pre>";
}

function NextGid()
{
	$gid = $GLOBALS['db']->GetRow("SELECT MAX(gid) AS next_gid FROM `" . DB_PREFIX . "_groups`");
	return ($gid['next_gid']+1);
}
function NextSGid()
{
	$gid = $GLOBALS['db']->GetRow("SELECT MAX(id) AS next_id FROM `" . DB_PREFIX . "_srvgroups`");
	return ($gid['next_id']+1);
}
function NextSid()
{
	$sid = $GLOBALS['db']->GetRow("SELECT MAX(sid) AS next_sid FROM `" . DB_PREFIX . "_servers`");
	return ($sid['next_sid']+1);
}
function NextAid()
{
	$aid = $GLOBALS['db']->GetRow("SELECT MAX(aid) AS next_aid FROM `" . DB_PREFIX . "_admins`");
	return ($aid['next_aid']+1);
}

function trunc($text, $len, $byword=true) 
{
	if(strlen($text) <= $len)
		return $text;
    $text = $text." ";
    $text = substr($text,0,$len);
    if($byword)
    	$text = substr($text,0,strrpos($text,' '));
    $text = $text."...";
    return $text;
}

function CreateRedBox($title, $content)
{
	$text = '<div class="alert alert-danger" id="msg-red-debug" role="alert"><h4>' . $title .'</h4><span class="p-l-10">' . $content . '</span></div>';
	echo $text;
}

function CreateGreenBox($title, $content)
{
	$text = '<div class="alert alert-success" id="msg-green-dbg" role="alert"><h4>' . $title .'</h4><span class="p-l-10">' . $content . '</span></div>';
	echo $text;
}

function RedirectJS($url)
{
	echo '<script>window.location = "' . $url .'";</script>';
}

function RemoveCode($text)
{
	return addslashes(htmlspecialchars(strip_tags($text)));
}

function PageDie()
{
	include TEMPLATES_PATH.'/footer.php';
	die();
}

function getRequestProtocol() {
    if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']))
        return $_SERVER['HTTP_X_FORWARDED_PROTO'];
    else 
        return !empty($_SERVER['HTTPS']) ? "https" : "http";
}

function TryAutodetectURL() {
    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        $uri = explode("/install", $_SERVER['HTTP_REFERER']);
        return $uri[0];
    }
    $proto  = getRequestProtocol();
    $domain = $_SERVER['SERVER_NAME'];
    $uri    = explode("/install", $_SERVER['REQUEST_URI']);
    
    return sprintf("%s://%s%s", $proto, $domain, $uri[0]);
}
?>
