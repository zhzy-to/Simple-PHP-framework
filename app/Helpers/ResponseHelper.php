<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;

/**
 * ResponseHelper.
 */
class ResponseHelper
{
    public static function getResponse($content, $statusCode = 200, $headers = [])
    {
        // $headers = ['Content-Type' => 'application/json'];

        return (new Response($content, $statusCode, $headers));
    }

}