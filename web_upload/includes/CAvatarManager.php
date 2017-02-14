<?php
/**************************************************************************
 * Эта программа является частью SourceBans MATERIAL Admin.
 *
 * Все права защищены © 2016-2017 Sergey Gut <webmaster@kruzefag.ru>
 *
 * SourceBans MATERIAL Admin распространяется под лицензией
 * Creative Commons Attribution-NonCommercial-ShareAlike 3.0.
 *
 * Вы должны были получить копию лицензии вместе с этой работой. Если нет,
 * см. <http://creativecommons.org/licenses/by-nc-sa/3.0/>.
 *
 * ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО
 * ГАРАНТИЙ, ЯВНЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ,
 * ГАРАНТИИ ПРИГОДНОСТИ ДЛЯ КОНКРЕТНЫХ ЦЕЛЕЙ И НЕНАРУШЕНИЯ. НИ ПРИ КАКИХ
 * ОБСТОЯТЕЛЬСТВАХ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ЗА
 * ЛЮБЫЕ ПРЕТЕНЗИИ, ИЛИ УБЫТКИ, НЕЗАВИСИМО ОТ ДЕЙСТВИЯ ДОГОВОРА,
 * ГРАЖДАНСКОГО ПРАВОНАРУШЕНИЯ ИЛИ ИНАЧЕ, ВОЗНИКАЮЩИЕ ИЗ, ИЛИ В СВЯЗИ С
 * ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ИЛИ ИНЫМИ ДЕЙСТВИЯМИ
 * ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ.
 *
 * Эта программа базируется на работе, охватываемой следующим авторским
 *                                                           правом (ами):
 *
 *  * SourceBans ++
 *    Copyright © 2014-2016 Sarabveer Singh
 *    Выпущено под лицензией CC BY-NC-SA 3.0
 *    Страница: <https://sbpp.github.io/>
 *
 ***************************************************************************/

if (!function_exists('GetCommunityIDFromSteamID2')) {
    require_once(INCLUDES_PATH . '/system-functions.php');
}

class CAvatarManager {
    private $cache = array();
    private $upd = array();
    private $del = array();
    private $DefaultAvatar;

    public function __construct($DefAvatar) {
        $this->DefaultAvatar = $DefAvatar;
        $query = $GLOBALS['db']->query(sprintf("SELECT * FROM `%s_avatars`", DB_PREFIX));

        while ($res = $query->fetch(PDO::FETCH_LAZY)) {
            $this->cache[$res->authid] = $res->url;
        }
    }

    public function __destruct() {
        $updates = count($this->upd);
        $deletes = count($this->del);

        if ($updates > 0) {
            $data = array();
            $query = sprintf("REPLACE INTO `%s_avatars` (`authid`, `url`) VALUES ", DB_PREFIX);

            for ($updateQuery = 0; $updateQuery < $updates; $updateQuery) {
                $query = sprintf("%s (?, ?)", $query);
                if ($updateQuery+1 != $updates) {
                    $query = sprintf("%s, ", $query);
                } else {
                    $query = sprintf("%s;", $query);
                }

                $sid = $this->upd[$updateQuery];
                $data[] = $sid;
                $data[] = $this->cache[$sid];
            }

            $GLOBALS['db']->prepare($query);
            $GLOBALS['db']->execute($data);
        }

        if ($deletes > 0) {
            $data = array();
            $query = sprintf("DELETE FROM `%s_avatars` WHERE ", DB_PREFIX);

            for ($deleteQuery = 0; $deleteQuery < $deletes; $deleteQuery) {
                $query = sprintf("%s`authid` = ?", $query);
                if ($deleteQuery+1 != $deletes) {
                    $query = sprintf("%s AND ", $query);
                } else {
                    $query = sprintf("%s;", $query);
                }

                $sid = $this->del[$deleteQuery];
                $data[] = $sid;
            }

            $GLOBALS['db']->prepare($query);
            $GLOBALS['db']->execute($data);
        }
    }
    
    public function getUserAvatar($authId) {
        if (empty($authId))
            return $this->DefaultAvatar;

        $communityID = GetCommunityIDFromSteamID2($authId);
        if (!isset($this->cache[$communityID]))
            $this->queryUserAvatar($authId, $communityID);

        return $this->cache[$communityID];
    }

    public function setUserAvatar($authId, $url) {
        $communityID = GetCommunityIDFromSteamID2($authId);
        $this->cache[$communityID] = $url;
        if (!in_array($communityID, $this->upd)) {
            $this->upd[] = $communityID;
        }
    }

    public function deleteUserAvatar($authId) {
        $communityID = GetCommunityIDFromSteamID2($authId);
        if (!in_array($communityID, $this->del)) {
            $this->del[] = $communityID;
        }

        unset($this->cache[$communityID]);
    }

    public function queryUserAvatar($authId, $communityID = null) {
        if (!$communityID)
            $communityID = GetCommunityIDFromSteamID2($authId);

        $AvatarFile = $this->DefaultAvatar;

        $SteamResponse = @json_decode(file_get_contents(sprintf("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=%s&steamids=%s", STEAMAPIKEY, $communityid)));
        if (isset($SteamResponse->response->players[0]->avatarfull))
            $AvatarFile = $SteamResponse->response->players[0]->avatarfull;

        $this->cache[$communityID] = $AvatarFile;
        $this->upd[] = $communityID;
    }
}
