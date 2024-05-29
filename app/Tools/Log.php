<?php

namespace App\Tools;

/**
 * Log.
 */
class Log
{
    public static function write($info, $debug = 'info')
    {
        if (is_array($info)) {
            $info = json_encode($info);
        }

        record_logs($info,$debug);
    }
}