<?php

namespace App\Helpers\Router;

class Router
{
    private $trie;

    public function __construct() {
        $this->trie = new Trie();
    }

    public function addRoute($method, $path, $handler) {
        $this->trie->insert(strtoupper($method), $path, $handler);
    }

    public function getRouteHandler($method, $path) {
        list($handler, $params) = $this->trie->search(strtoupper($method), $path);
        return array($handler, $params);
    }

    public function getTrie()
    {
        return $this->trie;
    }

    /**
     * @param $method
     * @param $path
     * @return mixed|string
     * @throws RouteNotFoundException
     */
    public function dispatch($method, $path, $args = []) {

        list($handler, $params) = $this->getRouteHandler($method, $path);

        if ($handler) {
            if (is_array($handler) && is_callable($handler)) {
                $call = new $handler[0];
                $action = $handler[1];
                $callback = [$call, $action];

                return call_user_func_array($callback, array_merge($params, $args));
            }

            if (is_callable($handler)) {
                // call_user_func
                // function ($param) => $param is array

                // call_user_func_array
                // function ($param, $args1, $args2) => $param is value

                return call_user_func_array($handler, array_merge($params, $args));
            }
        }

        throw new RouteNotFoundException('404 Not Found', 404);
    }
}