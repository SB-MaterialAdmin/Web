<?php

namespace SourceBans\Utils;

use SteamID;

/**
 * Класс обёртка, заменяет старые функции, которые конвертируют SteamID на библиотеку xPaw'a.
 * @package SourceBans\Utils
 */
class Steam
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var SteamID
     */
    protected $instance;

    /**
     * Steam constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->instance = new SteamID($this->id);
    }

    /**
     * @return false|string
     */
    public function steamId2ToCommunity()
    {
        try
        {
            return $this->instance->ConvertToUInt64();
        }
        catch (\InvalidArgumentException $e)
        {

            return false;
        }
    }

    /**
     * @return false|string
     */
    public function communityIdToSteamId2()
    {
        try
        {
            return $this->instance->RenderSteam2();
        }
        catch (\InvalidArgumentException $e)
        {
            return false;
        }
    }
}