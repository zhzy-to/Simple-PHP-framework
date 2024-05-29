<?php

namespace App\Middleware;

use App\Tools\Log;

/**
 * AppMiddleware.
 */
class AppMiddleware implements MiddlewareInterface
{

    public function handle($request, \Closure $next)
    {
        // $request->query->set('','');

        // TODO:: Pre handle
        // Log::write('Pre-content');
        $response = $next($request);

        // TODO:: Post operation
        // Log::write('Post-operation response:'. $response);

        return $response;
    }
}