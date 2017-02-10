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

class CDonate {
    private $hooks = array();
    
    /**
     * Add tariff
     *
     * @return int
     */
    public function AddTariff($name, $price, $expired, $desc, $webflags, $serverflags, $immunity, $servers) {
        $query = sprintf("INSERT INTO `%s_billing_admintariffs` (`name`, `price`, `expired`, `desc`, `webflags`, `serverflags`, `immunity`, `servers`) VALUES (%s, %d, %d, %s, %s, %s, %d, %s)", DB_PREFIX, $GLOBALS['db']->qstr($name), $price, $expired, $GLOBALS['db']->qstr($desc), $GLOBALS['db']->qstr($webflags), $GLOBALS['db']->qstr($serverflags), $immunity, $GLOBALS['db']->qstr($servers));
        $GLOBALS['db']->Execute($query);
        return $GLOBALS['db']->Insert_ID();
    }
    
    /**
     * Add admin request payment
     *
     * @return int
     */
    public function AddPayment_Admin($name, $authid, $tariff, $vk = '', $skype = '') {
        if (!$this->IsTariffExists($tariff))
            return -1;
        
        $query = sprintf("INSERT INTO `%s_billing_adminpayments` (`name`, `authid`, `tariff`, `vk`, `skype`) VALUES (%s, %s, %d, %s, %s);", DB_PREFIX, $GLOBALS['db']->qstr($name), $GLOBALS['db']->qstr($authid), (int) $tariff, $GLOBALS['db']->qstr($vk), $GLOBALS['db']->qstr($skype));
        $GLOBALS['db']->Execute($query);
        return $GLOBALS['db']->Insert_ID();
    }
    
    /**
     * Add unban request payment
     *
     * @return int
     */
    public function AddPayment_Unban($banid) {
        // IN DEVELOPING
    }
    
    // HELPERS //
    /**
     * Get client IP
     *
     * @return string ClientIP
     */
    public static function getIP() {
        return $_SERVER[isset($_SERVER['HTTP_X_REAL_IP'])?'HTTP_X_REAL_IP':'REMOTE_ADDR'];
    }
    
    /**
     * Checks tariff on exists.
     *
     * @return bool
     */
    public static function IsTariffExists($id) {
        return $GLOBALS['db']->GetOne(sprintf("SELECT COUNT(*) FROM `%s_billing_admintariffs` WHERE `id` = %d;", DB_PREFIX, (int) $id)) == 1;
    }
    
    /**
     * Register event hook.
     *
     * @noreturn
     */
    public function registerEvent($event_name, $func) {
        $this->hooks[$event_name][] = $func;
    }
    
    /** 
     * Fires a event for donate submodules
     *
     * @noreturn
     */
    public function fireEvent($event_name, $data) {
        if (!isset($this->hooks[$event_name]))
            return;
        
        foreach ($this->hooks[$event_name] as $event_handler) {
            call_user_func_array($event_handler, $data);
        }
    }
}

// This is skeleton for custom user payment services. DO NOT EDIT THIS.
class CPaymentService {
    /**
     * Returns the name of this SourceBans Payment Service.
     *
     * @return string Service name
     */
    public function getName() {}
    
    /**
     * Returns the author name. Allowed HTML chars.
     *
     * @return string Author Name
     */
    public function getAuthor() {}
    
    /**
     * Returns the version.
     *
     * @return string Version
     */
    public function getVersion() {}
    
    /**
     * Returns the provider WebSite.
     *
     * @return string Provider site
     */
    public function getUrl() {}
    
    /**
     * Generate client sign.
     * 
     * @return string ClientSign
     */
    public function getClientSign() {}
    
    /**
     * Generate notification sign.
     *
     * @return string NotifySign
     */
    public function getNotifySign() {}
    
    /**
     * Generate URL for client redirect.
     *
     * @return string URL.
     */
    public function generatePaymentUrl() {}
}
