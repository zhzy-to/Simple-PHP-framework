<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Request;

/**
 * RequestHelper.
 */
class RequestHelper
{
    protected static $req;

    public static function getGlobalRequest()
    {
        if (self::$req) {
            return self::$req;
        }

        self::$req = Request::createFromGlobals();

        return self::$req;
    }
}