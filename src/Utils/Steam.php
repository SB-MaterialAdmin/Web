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
     * Steam constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return false|string
     */
    public function steamId2ToCommunity()
    {
        try
        {
            $instance = new SteamID($this->id);
            return $instance->ConvertToUInt64();
        }
        catch (\InvalidArgumentException $e)
        {
            return false;
        }
    }
}