<?php

namespace models;

use core\Model;

/**
 * @Document City
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 */
class City extends Model
{
    public static function collectionName()
    {
        return 'cities';
    }
}
