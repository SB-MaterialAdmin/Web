<?php

/**
 * This file is a part of slight and fast framework.
 * This framework uses a third-party components. Full list you can find in our example app (run example.index.php for start).
 */

namespace SB\Entity;

use Framework\Entity\AbstractEntity;
use Spot\Mapper;
use Spot\Entity;

/**
 * FIELDS
 * @property integer        $id
 * @property integer        $priority
 * @property string         $ip
 * @property integer        $port
 * @property integer        $modid
 * @property integer        $enabled
 *
 * RELATIONS
 * @property \SB\Entity\Mod $mod
 */
class Server extends AbstractEntity
{
    protected static $table = 'sb_servers';

    public static function fields()
    {
        return [
            'id'        =>  ['type'     => 'integer',   'primary'   => true,    'autoincrement' => true],
            'priority'  =>  ['type'     => 'integer',   'required'  => true,    'default'       => 0],
            'ip'        =>  ['type'     => 'string',    'required'  => true],
            'port'      =>  ['type'     => 'integer',   'required'  => true,    'default'       => 27015,   'unsigned'  => true],
            'modid'     =>  ['type'     => 'integer',   'required'  => true],
            'enabled'   =>  ['type'     => 'boolean',   'required'  => true,    'default'       => true,    'unsigned'  => true],
        ];
    }

    public static function relations(Mapper $mapper, Entity $entity)
    {
        return [
            'mod'   => $mapper->belongsTo($entity, '\SB\Entity\Mod', 'modid'),
        ];
    }
}