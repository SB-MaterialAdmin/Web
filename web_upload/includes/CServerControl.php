<?php
/**************************************************************************
 * Эта программа является частью SourceBans ++.
 *
 * Все права защищены © 2014-2016 Sarabveer Singh <me@sarabveer.me>
 *
 * SourceBans++ распространяется под лицензией
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
 *  * SourceBans 1.4.11
 *    Copyright © 2007-2014 SourceBans Team - Part of GameConnect
 *    Выпущено под лицензией CC BY-NC-SA 3.0
 *    Страница: <http://www.sourcebans.net/> - <http://www.gameconnect.net/>
 *
 *  * SourceBans TF2 Theme v1.0
 *    Copyright © 2014 IceMan
 *    Страница: <https://forums.alliedmods.net/showthread.php?t=252533>
 *
 ***************************************************************************/

if (!defined('IN_SB')) {echo("You should not be here. Only follow links!");die();}

/**
 * Класс-костыль, который не будет "выплёвывать"
 * исключения при фэйлах, а просто вернёт FALSE.
 * Не трогать.
 **/
require INCLUDES_PATH . '/SourceQuery/bootstrap.php';
use xPaw\SourceQuery\SourceQuery;

/**
 * Константы для "кеша"
 */
define('A2S_INFO',    0);
define('A2S_RULES',   1);
define('A2S_PLAYERS', 2);

class CServerControl {
    private $sq;    /**< Source Query object */

    private $cs;    /**< Cache State (enabled or disabled) */
    private $cl;    /**< Cache file loaded? */
    private $cc;    /**< Cache data */
    private $cu;    /**< File on storage is required to update? */

    private $gip;   /**< IP with port */

    public function __construct($state = true) {
        $this->sq = new SourceQuery();
        $this->SetCacheRunState($state);
    }

    public function __destruct() {
        $this->SaveCacheFile();
    }
    
    public function Connect($ip, $port = 27015) {
        try {
            $this->SaveCacheFile();
            $this->gip = '';

            $this->sq->Disconnect();
        } catch (Exception $e) {}
        
        // Connect
        try {
            $this->sq->Connect($ip, $port, 2, SourceQuery::SOURCE);
            $this->gip = "{$ip}_{$port}";

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /* RCON */
    public function AuthRcon($password) {
        try {
            $this->sq->SetRconPassword($password);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function SendCommand($cmd) {
        try {
            return $this->sq->Rcon($cmd);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * THESE 3 METHODS ARE DEPRECATED
     * Use GetData()
     *
     * Queries
     */
    public function GetInfo()   { return $this->GetData(A2S_INFO);    }
    public function GetRules()  { return $this->GetData(A2S_RULES);   }
    public function GetPlayers(){ return $this->GetData(A2S_PLAYERS); }

    public function GetData($query_type) {
        $response = null;
        if (!$this->LookupCacheEntry($query_type, $response)) {
            try {
                $response = $this->execute($query_type);
            } catch (Exception $e) {
                return false;
            }
        }

        $this->SetCacheEntry($query_type, $response);
        return $response;
    }

    /**
     * Caching
     */
    public function SetCacheRunState($state = true) {
        $this->cs = $state;
    }

    private function LookupCacheEntry($query_type, &$response) {
        if (!$this->cs)
            return false;

        if (!$this->cl)
            $this->LoadCacheFile();

        if (!isset($this->cc[$query_type]))
            return false;

        if ($this->cc[$query_type]['time'] > time())
            return false;

        $response = $this->cc[$query_type]['data'];
        return true;
    }

    private function SetCacheEntry($query_type, $response) {
        if (!$this->cs)
            return;

        if (!$this->cl)
            $this->LoadCacheFile();

        $this->cc[$query_type] = [
            'time'  => time() + intval($GLOBALS['config']['gamecache.entry_lifetime']),
            'data'  => $response
        ];
        $this->cu = true;
    }

    private function LoadCacheFile() {
        if (!$this->cs)
            return;

        $this->cl = true;
        $this->cu = false;
        $this->cc = [];

        $path = $this->GetCachePath();
        if (!file_exists($path))
            return;

        $this->cc = unserialize(file_get_contents($path));
    }

    private function SaveCacheFile() {
        if (!$this->cs || !$this->cl)
            return;

        $this->cl = false;
        if (!$this->cu)
            return;

        $data = serialize($this->cc);
        $path = $this->GetCachePath();
        file_put_contents($path, $data);
    }

    private function GetCachePath() {
        return USER_DATA . 'gc/' . md5($this->gip) . ".cache";
    }

    // executing game server queries
    private function execute($query_type) {
        switch ($query_type) {
            case A2S_INFO:      return $this->sq->GetInfo();
            case A2S_RULES:     return $this->sq->GetRules();
            case A2S_PLAYERS:   return $this->sq->GetPlayers();
        }
    }
}
