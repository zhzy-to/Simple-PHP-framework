<?php

namespace App\Tools;

class Parameter
{
    protected static $params;
    public static function setParams($params = [])
    {
        self::$params = $params;
    }

    public static function getParams()
    {
        return self::$params;
    }
}