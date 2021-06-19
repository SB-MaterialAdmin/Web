<?php

namespace SourceBans\Auth;

abstract class AbstractAuth
{
    /**
     * @param $site - Your domain url (SB_WP_URL) const
     * @return mixed
     */
    abstract public function handle($site);

    public function securityLock()
    {
        if (!defined('IN_SB'))
        {
            exit("You should not be here. Only follow links!");
        }
    }
}