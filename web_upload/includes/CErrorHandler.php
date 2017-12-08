<?php 
if(!defined("IN_SB")){echo "You should not be here. Only follow links!";die();}

class CErrorHandler {
    private static $fatalcodes = array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR);
    private static $m_bIsAlreadyInit = false;

    public static function Init() {
        if (self::$m_bIsAlreadyInit) {
            return;
        }

        set_error_handler([self, 'BasicErrorCatcher']);
        register_shutdown_function([self, 'FatalErrorCatcher']);
        self::StartOutputBuffer();
        self::$m_bIsAlreadyInit = true;
    }
    
    private static function CloseOutputBuffer($bRender) {
        if ($bRender)
            ob_end_flush();
        else
            ob_end_clean();
    }
    
    private static function StartOutputBuffer() {
        ob_start();
    }
    
    private static function DrawErrorMessage($message, $function = null, $title = "Ошибка системы") {
        global $theme;
        self::CloseOutputBuffer(false);
        
        $theme->assign('title', $title);
        $theme->assign('message', $message);
        if ($function)
            $theme->assign('pfunction', str_replace("\n", "<br />", $function));
        else
            $theme->assign('pfunction', false);
        $theme->assign('SB_ADDRESS', SB_WP_URL);
        $theme->display('page_error.tpl');
    }
    
    public static function BasicErrorCatcher($errno, $errstr, $errfile, $errline) {
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
                self::DrawErrorMessage($msg, $log->parent_function, $title);
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
    
    public static function FatalErrorCatcher() {
        $error = error_get_last();
        if ($error === NULL || $error['type'] !== E_ERROR) {
            self::CloseOutputBuffer(true);
            return;
        }
        
        if (in_array($error['type'], self::$fatalcodes)) {
            self::CloseOutputBuffer(false);
            self::DrawErrorMessage("Произошла фатальная ошибка PHP\n" . $error['message'] . "\n\n" . $error['file'] . "::" . $error['line'], null, "Критическая ошибка PHP");
        }
    }
}
?>
