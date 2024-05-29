<?php

namespace App\Exceptions;

use App\Helpers\Router\RouteNotFoundException;
use App\Tools\Log;

/**
 * Exception register.
 */
class Handler
{
    public function register($e)
    {
        // Determine whether it belongs to those exceptions and intercept them

        if ($e instanceof ServerException) {
            echo 'server exception .';
            exit();
        }

        if ($e instanceof RouteNotFoundException) {

            response('404 Not Found', 404)->send();
        }

        $content = sprintf("File: %s, \nLine: %s, \nPrevious Exception: %s, \nTrace: %s, \nMessage: %s \n",
            $e->getFile(),
            $e->getLine(),
            $e->getPrevious(),
            $e->getTraceAsString(),
            $e->getMessage()
        );

        Log::write($content,'error');

        throw $e;
    }
}