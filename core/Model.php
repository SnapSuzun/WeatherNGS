<?php

namespace core;


abstract class Model
{
    /**
     * @return string
     */
    protected static function collectionName()
    {
        return '';
    }

    public static function collection()
    {
        $name = static::collectionName();
        if (!empty($name)) {
            return \Core::$app->db->$name;
        }

        return null;
    }

    public function save($options = [])
    {
        $this::collection()->save($this, $options);
    }
}
