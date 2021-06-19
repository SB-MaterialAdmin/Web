<?php

namespace SourceBans\Auth;

use LightOpenID;

class Steam extends AbstractAuth
{
    /**
     * @param $site
     * @return false|mixed|String|null
     * @throws \ErrorException
     */
    public function handle($site)
    {
        $this->securityLock();

        $openId = new LightOpenID($site);

        if (!$openId->mode)
        {
            $openId->identity = 'http://steamcommunity.com/openid';
            return $openId->authUrl();
        }
        else if ($openId->mode == 'cancel')
        {
            return false;
        }
        else if ($openId->mode == 'id_res' && $openId->validate())
        {
            $id = $openId->identity;
            $ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
            preg_match($ptn, $id, $matches);

            if (!empty($matches[1]))
            {
                return $matches[1];
            }
            else
                {
                return null;
            }
        }

        return false;
    }
}