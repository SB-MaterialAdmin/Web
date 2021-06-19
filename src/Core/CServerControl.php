<?php

namespace SourceBans\Core;

use xPaw\SourceQuery\SourceQuery;

/**
 * Класс-костыль, который не будет "выплёвывать"
 * исключения при фэйлах, а просто вернёт FALSE.
 * Не трогать.
 **/
class CServerControl
{
    private $sq;

    public function __construct() {
        $this->sq = new SourceQuery();
    }

    public function Connect($ip, $port = 27015) {
        try {
            $this->sq->Disconnect();
        } catch (\Exception $e) {}

        // Connect
        try {
            $this->sq->Connect($ip, $port, 2, SourceQuery::SOURCE);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /* RCON */
    public function AuthRcon($password) {
        try {
            $this->sq->SetRconPassword($password);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function SendCommand($cmd) {
        try {
            return $this->sq->Rcon($cmd);
        } catch (\Exception $e) {
            return false;
        }
    }

    /* Queries */
    public function GetInfo() {
        try {
            return $this->sq->GetInfo();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function GetPlayers() {
        try {
            return $this->sq->GetPlayers();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function GetRules() {
        try {
            return $this->sq->GetRules();
        } catch (\Exception $e) {
            return false;
        }
    }
}