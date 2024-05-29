<?php

/**
 * initialization .
 * Set up exception class registration .
 * @return void
 */
function bootstrap()
{
    // todo:: Initialize configuration

    require_once BASE_PATH . '/bootstrap/database.php';

    set_exception_handler(static function ($exception) {
        (new \App\Exceptions\Handler())->register($exception);
    });
}


/**
 * Capture requests and responses .
 * @return void
 */
function run()
{
    bootstrap();

    $method = $_SERVER['REQUEST_METHOD'];

    $router = require_once BASE_PATH . '/routes.php';

    $request = \request();
    $path = $request->getPathInfo();

    // request handle
    $r = static function ($request) use($router, $method, $path) {
        $args['request'] = $request;
        return $router->dispatch(strtoupper($method), $path, $args);
    };

    // http middleware
    $middlewareStack = require_once __DIR__ . '/middleware.php';

    // init callable
    $initial = static function ($r) {
        return static function ($request) use ($r) {
            return $r($request);
        };
    };

    // array_reduce (array , callback, init)
    $res = array_reduce(
        array_reverse($middlewareStack),
        static function ($carry, $item) {
            return static function ($request) use ($carry, $item) {
                $middleware = (new $item);
                if ($middleware instanceof \App\Middleware\MiddlewareInterface) {
                    return $middleware->handle($request, $carry);
                }

                return $carry($request);
            };
        },
        $initial($r)
    );

    $response = $res($request);

    if ($response instanceof \Symfony\Component\HttpFoundation\Response) {
        $response->send();
    }

    echo $response;
    exit();
}