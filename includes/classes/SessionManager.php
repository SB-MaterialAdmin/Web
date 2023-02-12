<?php

class SessionManager
{
    private static $cookieName = '';
    private static $expires = 86400;
    private static $path = '/';
    private static $domain = null;
    private static $secureOnly = false;

    public static function setupParameters($name, $expires = 86400, $path = '/', $domain = null, $isSecure = false)
    {
        self::$cookieName = $name . '_SB';
        self::$expires = $expires;
        self::$path = $path;
        self::$domain = $domain;
        self::$secureOnly = $isSecure;

        session_name(self::$cookieName);

		if (!empty($_COOKIE[self::$cookieName])) {
			session_id($_COOKIE[self::$cookieName]);
		}

        session_id();
    }

    public static function startSession()
    {
        session_start();

        self::setCookie();

        if (self::ValidateSession())
        {
            if (!self::PreventHijacking())
            {
                $_SESSION = [];
                self::regenerateSession();

                $_SESSION = [
                    'user_agent' => hash('sha256', $_SERVER['HTTP_USER_AGENT']),
                    'expires' => time() + self::$expires
                ];
            } else if ((rand(1, 100) <= 10) && !isset($_POST['xajax']))
            {
                self::regenerateSession();
            }
        }
    }

    public static function checkSession()
    {
        if (!isset($_SESSION['user_agent']))
            return false;

        if (!self::validateSession() || !self::preventHijacking())
        {
            session_destroy();
            session_start();

            return false;
        }

        return true;
    }

    public static function closeWrite()
    {
        @session_write_close();
    }

    protected static function preventHijacking()
    {
        if (!isset($_SESSION['user_agent']))
            return false;

        if ($_SESSION['user_agent'] !== hash('sha256', $_SERVER['HTTP_USER_AGENT']))
            return false;

        return true;
    }

    protected static function regenerateSession()
    {
        $_SESSION['expires'] = time() + 10;

        session_regenerate_id(false);
        $newSession = session_id();
        self::setCookie();

        self::closeWrite();
        session_id($newSession);
        session_start();
        unset($_SESSION['expires']);
    }

    protected static function validateSession()
    {
        return (
            !isset($_SESSION['expires']) ||
            $_SESSION['expires'] >= time()
        );
    }

    /**
     * @section Session Name
     */
    public static function getSessionName($domain)
    {
        if (defined('SB_SESSION'))
        {
            $session = constant('SB_SESSION');
            if (!empty($session))
                return $session;
        }

        return substr(md5($domain ?: $_SERVER['SERVER_NAME']), 0, 8);
    }

    public static function setCookie()
    {
        setcookie(self::$cookieName, session_id(), time() + self::$expires,
            self::$path, self::$domain, self::$secureOnly, true);
    }
}