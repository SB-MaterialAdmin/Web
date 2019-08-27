<?php

/**
 * This file is a part of slight and fast framework.
 * This framework uses a third-party components. Full list you can find in our example app (run example.index.php for start).
 */

namespace SB\Entity;

use Framework\Entity\AbstractEntity;

/**
 * FIELDS
 * @property string $id
 * @property array $data
 */
class Server extends AbstractEntity
{
    protected static $table = 'sb_servers';

    public static function fields()
    {
        return [
            'id'    => [
                
            ]
        ];
    }
}