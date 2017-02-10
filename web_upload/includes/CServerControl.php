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

if (!defined('IN_SB')) {echo("Вы не должны быть здесь. Используйте только ссылки внутри системы!");die();}

/**
 * Класс-костыль, который не будет "выплёвывать"
 * исключения при фэйлах, а просто вернёт FALSE.
 * Не трогать.
 **/
require INCLUDES_PATH . '/SourceQuery/bootstrap.php';
use xPaw\SourceQuery\SourceQuery;

class CServerControl {
    private $sq;
    
    public function __construct() {
        $this->sq = new SourceQuery();
    }
    
    public function Connect($ip, $port = 27015) {
        try {
            $this->sq->Disconnect();
        } catch (Exception $e) {}
        
        // Connect
        try {
            $this->sq->Connect($ip, $port, 2, SourceQuery::SOURCE);
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
    
    /* Queries */
    public function GetInfo() {
        try {
            return $this->sq->GetInfo();
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function GetPlayers() {
        try {
            return $this->sq->GetPlayers();
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function GetRules() {
        try {
            return $this->sq->GetRules();
        } catch (Exception $e) {
            return false;
        }
    }
}
