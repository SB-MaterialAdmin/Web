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

define('SB_PAYMENTS_ADMIN', (1<<0));
define('SB_PAYMENTS_UNBAN', (1<<1));
define('SB_PAYMENTS_DONE',  (1<<2));

class CFreeKassa {
    private $fkIPs = array('136.243.38.147', '136.243.38.149', '136.243.38.150',
                           '136.243.38.151', '136.243.38.189');

    private function getIP() {
        return $_SERVER[isset($_SERVER['HTTP_X_REAL_IP'])?'HTTP_X_REAL_IP':'REMOTE_ADDR'];
    }

    private function addPayment($type, $data) {
        $GLOBALS['db']->Execute(sprintf("INSERT INTO `%s_billing_payments` (`type`, `data`) VALUES (%d, %s)", DB_PREFIX, (int) $type, $GLOBALS['db']->qstr($data)));
        return $GLOBALS['db']->Insert_ID();
    }

    public function ValidatePayment($sign, $oid) {
        return ($sign == $this->GenerateNotifySign($oid)
                &&
                in_array($this->getIP(), $this->fkIPs));
    }

    public function GenerateClientSign($order_id) {
        return md5(sprintf("%s:%d:%s:%d", $GLOBALS['config']['billing.shop_id'], (int) $this->GetPaymentById($order_id)->summ, $GLOBALS['config']['billing.secret_word.shop'], $order_id));
    }

    public function GenerateNotifySign($order_id) {
        return md5(sprintf("%s:%d:%s:%d", $GLOBALS['config']['billing.shop_id'], (int) $this->GetPaymentById($order_id)->summ, $GLOBALS['config']['billing.secret_word.notify'], $order_id));
    }

    public function AddTariff($name, $price, $expired, $catdesc, $fulldesc, $webflags, $serverflags, $immunity, $servers) {
        $query = sprintf("INSERT INTO `%s_billing_tariffs` (`name`, `price`, `expired`, `catdesc`, `fulldesc`, `webflags`, `serverflags`, `immunity`, `servers`) VALUES (%s, %d, %d, %s, %s, %s, %s, %d, %s)", DB_PREFIX, $GLOBALS['db']->qstr($name), $price, $expired, $GLOBALS['db']->qstr($catdesc), $GLOBALS['db']->qstr($fulldesc), $GLOBALS['db']->qstr($webflags), $GLOBALS['db']->qstr($serverflags), $immunity, $GLOBALS['db']->qstr($servers));
        $GLOBALS['db']->Execute($query);
        return $GLOBALS['db']->Insert_ID();
    }

    public function AddAdminPayment($name, $authid, $tariff, $vk = "", $skype = "") {
        $tariff = $this->GetTariffById($tariff);
        if (!$tariff)
            return false;
        $tariff = $tariff['id'];
        $pdata  = json_encode(array("name" => $name, "authid" => $authid, "tariff" => $tariff, "vk" => $vk, "skype" => $skype));
        return $this->addPayment(SB_PAYMENTS_ADMIN, $pdata);
    }

    public function AddUnbanPayment($authid) {
        $ban = $GLOBALS['db']->GetRow(sprintf("SELECT `bid` FROM `%s_bans` WHERE `authid` = %s AND `length` > 0 AND RemovedOn = NULL", DB_PREFIX, $GLOBALS['db']->qstr($authid)));
        if (count($ban) > 0)
            return $this->addPayment(SB_PAYMENTS_UNBAN, json_encode(array("id" => $ban['bid'])));
        else
            return false;
    }

    public function GetPaymentById($id) {
        $row = $GLOBALS['db']->GetRow(sprintf("SELECT * FROM `%s_billing_payments` WHERE `id` = %d", DB_PREFIX, (int) $id));
        if (count($row)) {
            $data = json_decode($row['data'], true);
            $data['id'] = $id;
            $data['status'] = $row['status'];
            return new CDataArray($data);
        } else
            return false;
    }
    
    public function GetTariffById($id) {
        $row = $GLOBALS['db']->GetRow(sprintf("SELECT * FROM `%s_billing_tariffs` WHERE `id` = %d", DB_PREFIX, (int) $id));
        if (count($row)) {
            $data = json_decode($row['data'], true);
            $data['id'] = $id;
            $data['status'] = $row['status'];
            return new CDataArray($data);
        } else
            return false;
    }

    public function AddLogEntry($result, $title, $description) {
        return new CSystemLog($result==true?"m":"w", sprintf("FreeKassa - %s", $title), $description);
    }

    public function GetTariffs($onlyEnabled = true) {
        $datas = $GLOBALS['db']->GetAll(sprintf("SELECT * FROM `%s_billing_tariffs`%s", DB_PREFIX, $onlyEnabled?" WHERE `enabled` = 1":""));
        $payments = array();
        foreach ($datas as $tariff)
            $payments[$tariff['id']] = new CDataArray($tariff);
        return $payments;
    }
}

class CDataArray {
    private $instance_data;
    public function __construct($arr) { $this->instance_data = $arr; }
    public function __get($param) { return isset($this->instance_data[$param])?$this->instance_data[$param]:NULL; }
    public function __set($param, $value) {} // Nothing.
    public function getAllData() { return $this->instance_data; }
}

/**
 * Шпора на тему INTEGER-флагов
 * SB_PAYMENTS_ADMIN: 1
 * SB_PAYMENTS_UNBAN: 2
 * SB_PAYMENTS_DONE:  4
 */
