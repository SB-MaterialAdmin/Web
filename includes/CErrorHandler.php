<?php 
if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();}

class CErrorHandler {
    private $fatalcodes = array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR);

    public function __construct() {
        set_error_handler([$this, 'BasicErrorCatcher']);
        register_shutdown_function([$this, 'FatalErrorCatcher']);
        
        $this->StartOutputBuffer();
    }
    
    private function CloseOutputBuffer($bRender) {
        if ($bRender)
            ob_end_flush();
        else
            ob_end_clean();
    }
    
    private function StartOutputBuffer() {
        ob_start();
    }
    
    private function DrawErrorMessage($message, $function = null, $title = "Ошибка системы") {
        global $theme;
        $this->CloseOutputBuffer(false);
        
        $theme->assign('title', $title);
        $theme->assign('message', $message);
        if ($function)
            $theme->assign('pfunction', str_replace("\n", "<br />", $function));
        else
            $theme->assign('pfunction', false);
        $theme->assign('SB_ADDRESS', SB_WP_URL);
        $theme->display('page_error.tpl');
    }
    
    public function BasicErrorCatcher($errno, $errstr, $errfile, $errline) {
        /* Moved from /var/www/g-44/data/www/bans.g-44.ru/init.php */
        if(!is_object($GLOBALS['log']))
            return false;
        
        $retValue = true;
        if (function_exists('error_clear_last'))
            error_clear_last();
        
        switch ($errno) {
            case E_USER_ERROR:
                $msg = "[$errno] $errstr<br />\n";
                $msg .= "Произошла фатальная ошибка на строке $errline в файле $errfile";
                $log = new CSystemLog("e", "PHP Error", $msg);
        
                // SourceBans Fatal Error Handler //
                // include(INCLUDES_PATH.'/FatalErrorHandler.php');
                $this->DrawErrorMessage($msg, $log->parent_function, $title);
                // SourceBans Fatal Error Handler //

                $retValue = false;
                exit(1);
                break;

            case E_USER_WARNING:
                $msg = "[$errno] $errstr<br />\n";
                $msg .= "Ошибка на строке $errline в файле $errfile";
                $GLOBALS['log']->AddLogItem("w", "PHP Warning", $msg);
                break;

            case E_USER_NOTICE:
                $msg = "[$errno] $errstr<br />\n";
                $msg .= "Уведомление на строке $errline в файле $errfile";
                $GLOBALS['log']->AddLogItem("m", "PHP Notice", $msg);
                break;

            default:
                $retValue = false;
                break;
        }

        /* Don't execute PHP internal error handler */
        return $retValue;
    }
    
    public function FatalErrorCatcher() {
        $error = error_get_last();
        if ($error === NULL || $error['type'] !== E_ERROR) {
            $this->CloseOutputBuffer(true);
            return;
        }
        
        if (in_array($error['type'], $this->fatalcodes)) {
            $this->CloseOutputBuffer(false);
            $this->DrawErrorMessage("Произошла фатальная ошибка PHP\n" . $error['message'] . "\n\n" . $error['file'] . "::" . $error['line'], null, "Критическая ошибка PHP");
        }
    }
}
?>
