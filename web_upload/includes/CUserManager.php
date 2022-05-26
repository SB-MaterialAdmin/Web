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

class CUserManager
{
  var $aid = -1;
  var $admins = array();
  
  /**
   * Class constructor
   *
   * @param $aid the current user's aid
   * @param $password the current user's password
   * @return noreturn.
   */
  function __construct($aid, $password)
  {
    if ($aid != -1)
    {
      $data = $this->GetUserArray($aid);
      if ($password != $data['password']) return;
    }
    $this->aid = $aid;
  }
  
  
  /**
   * Gets all user details from the database, saves them into
   * the admin array 'cache', and then returns the array
   *
   * @param $aid the ID of admin to get info for.
   * @return array.
   */
  function GetUserArray($aid=-2)
  {
    if($aid == -2)
      $aid = $this->aid;  
    // Invalid aid
    if($aid < 0 || empty($aid))
      return 0;
    
    $aid = (int)$aid;
    // We already got the data from the DB, and its saved in the manager
    if(isset($this->admins[$aid]) && !empty($this->admins[$aid]))
      return $this->admins[$aid];
    // Not in the manager, so we need to get them from DB
    $res = $GLOBALS['db']->GetRow("SELECT adm.user user, adm.authid authid, adm.password password, adm.gid gid, adm.email email, adm.validate validate, adm.extraflags extraflags, 
                     adm.immunity admimmunity,sg.immunity sgimmunity, adm.srv_password srv_password, adm.srv_group srv_group, adm.srv_flags srv_flags,sg.flags sgflags,
                     wg.flags wgflags, wg.name wgname, adm.lastvisit lastvisit, adm.expired expired, adm.skype skype, adm.comment comment, adm.vk vk
                     FROM " . DB_PREFIX . "_admins AS adm
                     LEFT JOIN " . DB_PREFIX . "_groups AS wg ON adm.gid = wg.gid
                     LEFT JOIN " . DB_PREFIX . "_srvgroups AS sg ON adm.srv_group = sg.name
                     WHERE adm.aid = $aid");
    
    if(!$res)  
      return 0;  // ohnoes some type of db error
    
    return $this->setupUser($aid, $res);
  }

  
  /**
   * Will check to see if an admin has any of the flags given
   *
   * @param $flags The flags to check for.
   * @param $aid The user to check flags for.
   * @return boolean.
   */
  function HasAccess($flags, $aid=-2)
  {
    if($aid == -2)
      $aid = $this->aid;
      
    if(empty($flags) || $aid <= 0)
      return false;
    
    $aid = (int)$aid;
    if(is_numeric($flags))
    {
      if(!isset($this->admins[$aid]))
        $this->GetUserArray($aid);
      return ($this->admins[$aid]['extraflags'] & $flags) != 0 ? true : false;
    }
    else 
    {
      if(!isset($this->admins[$aid]))
        $this->GetUserArray($aid);
      for($i=0;$i<strlen($this->admins[$aid]['srv_flags']);$i++)
      {
        for($a=0;$a<strlen($flags);$a++)
        {
          if(strstr($this->admins[$aid]['srv_flags'][$i], $flags[$a]))
            return true;
        }
      }
    }
  }
  
  
  /**
   * Gets a 'property' from the user array eg. 'authid'
   *
   * @param $aid the ID of admin to get info for.
   * @return mixed.
   */
  function GetProperty($name, $aid=-2)
  {
    if($aid == -2)
      $aid = $this->aid;
    if(empty($name) || $aid < 0)
      return false;
    $aid = (int)$aid;  
    if(!isset($this->admins[$aid]))
      $this->GetUserArray($aid);
    
    return $this->admins[$aid][$name];
  }
  

  /**
   * Will test the user's login stuff to check if they havnt changed their 
   * cookies or something along those lines.
   *
   * @param $password The admins password.
   * @param $aid the admins aid
   * @return boolean.
   */
  function CheckLogin($password, $aid)
  {
    $aid = (int)$aid;

    if(empty($password))
      return false;
    if(!isset($this->admins[$aid]))
      $this->GetUserArray($aid);
      
    if($password == $this->admins[$aid]['password'])
    {
      $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET `lastvisit` = UNIX_TIMESTAMP() WHERE `aid` = '$aid'");
      return true;
    }
    else 
      return false;
  }
  
  
  function login($aid, $password) {
    if($this->CheckLogin($this->encrypt_password($password), $aid)) {
      $_SESSION['admin_id'] = $aid;
      return true;
    }
  return false;
  }
  
  
  
  /**
   * Encrypts a password.
   *
   * @param $password password to encrypt.
   * @return string.
   */
  function encrypt_password($password, $salt=SB_SALT)
  {
    return sha1(sha1($salt . $password));
  }
  
  function is_logged_in()
  {
    if($this->aid != -1)
      return true;
    else 
      return false;
  }
  
  
  function is_admin($aid=-2)
  {
    if($aid == -2)
      $aid = $this->aid;
    
    if($this->HasAccess(ALL_WEB, $aid))
      return true;
    else   
      return false;
  }
  
  
  function GetAid()
  {
    return $this->aid;
  }
  
  
  function GetAllAdmins()
  {
    $res = $GLOBALS['db']->GetAll("SELECT adm.aid aid, adm.user user, adm.authid authid, adm.password password, adm.gid gid, adm.email email, adm.validate validate, adm.extraflags extraflags, 
                     adm.immunity admimmunity,sg.immunity sgimmunity, adm.srv_password srv_password, adm.srv_group srv_group, adm.srv_flags srv_flags,sg.flags sgflags,
                     wg.flags wgflags, wg.name wgname, adm.lastvisit lastvisit, adm.expired expired, adm.skype skype, adm.comment comment, adm.vk vk
                     FROM " . DB_PREFIX . "_admins AS adm
                     LEFT JOIN " . DB_PREFIX . "_groups AS wg ON adm.gid = wg.gid
                     LEFT JOIN " . DB_PREFIX . "_srvgroups AS sg ON adm.srv_group = sg.name");
    foreach($res AS $admin)
      $this->setupUser($admin['aid'], $admin);
    return $this->admins;
  }
  
  
  function GetAdmin($aid=-2)
  {
    if($aid == -2)
      $aid = $this->aid;
    if($aid < 0)
      return false;  
      
    $aid = (int)$aid;
    
    if(!isset($this->admins[$aid]))
      $this->GetUserArray($aid);
    return $this->admins[$aid];
  }
  
  
  function AddAdmin($name, $steam, $password, $email, $web_group, $web_flags, $srv_group, $srv_flags, $immunity, $srv_password, $period, $skype, $comment, $vk)
  {    
    $add_admin = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_admins(user, authid, password, gid, email, extraflags, immunity, srv_group, srv_flags, srv_password, expired, skype, comment, vk)
                       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $GLOBALS['db']->Execute($add_admin,array($name, $steam, $this->encrypt_password($password), $web_group, $email, $web_flags, $immunity, $srv_group, $srv_flags, $srv_password, $period, $skype, $comment, $vk));
    return ($add_admin) ? (int)$GLOBALS['db']->Insert_ID() : -1;
  }

  function setupUser($aid, $res)
  {
    $user = array();
    //$user['user'] = stripslashes($res[0]);
    $user['aid'] = $aid; //immediately obvious
    $user['user'] = $res['user'];
    $user['authid'] = $res['authid'];
    $user['password'] = $res['password'];
    $user['gid'] = $res['gid'];
    $user['email'] = $res['email'];
    $user['validate'] = $res['validate'];
    $user['extraflags'] = (intval($res['extraflags']) | intval($res['wgflags']));

    if(intval($res['admimmunity']) > intval($res['sgimmunity']))
      $user['srv_immunity'] = intval($res['admimmunity']);
    else
      $user['srv_immunity'] = intval($res['sgimmunity']);

    $user['srv_password'] = $res['srv_password'];
    $user['srv_groups'] = $res['srv_group'];
    $user['srv_flags'] = $res['srv_flags'] . $res['sgflags'];
    $user['group_name'] = $res['wgname'];
    $user['lastvisit'] = $res['lastvisit'];
    $user['expired'] = $res['expired'];
    $user['skype'] = $res['skype'];
    $user['comment'] = $res['comment'];
    $user['vk'] = $res['vk'];
    $this->admins[$aid] = $user;

    return $user;
  }
}
