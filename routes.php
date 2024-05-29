<?php

/*
 * The Routes.
 *
*/

use App\Helpers\Router\Router;

$router = new Router();

$router->addRoute('GET','/test', [\App\Controllers\TestController::class, 'test']);

$router->addRoute('GET', '/user/:id', function($id, $request) {

    return "User ID: " . $id;
});

$router->addRoute('GET', '/user/:name', function($name, $request) {

    return "User Name: " . $name;
});

$router->addRoute('GET', '/user', function() {
    return "Get User";
});


return $router;


//return [
//    // testing
//    '/test' => [\App\Controllers\TestController::class, 'test'],
//];