<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.08.16
 * Time: 13:04
 */

namespace models;


use core\Model;

class Forecast extends Model
{
    public static function collectionName()
    {
        return 'forecast';
    }
}
