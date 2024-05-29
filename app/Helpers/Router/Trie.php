<?php

namespace App\Helpers\Router;

class Trie
{
    private $root;

    public function __construct() {
        $this->root = new TrieNode();
    }

    /**
     * @param $method
     * @param $path
     * @param $handler
     * @return void
     */
    public function insert($method, $path, $handler) {
        $node = $this->root;
        $parts = explode('/', trim($path, '/'));

        foreach ($parts as $part) {

            // $part[0] Get the first character of a string
            if ($part !== '' && $part[0] === ':') {
                // Path parameter name   :id  param = id
                $paramName = substr($part, 1);

                if (!isset($node->children[':param'])) {
                    $node->children[':param'] = new TrieNode();
                    $node->children[':param']->paramName = $paramName;
                }
                $node = $node->children[':param'];

            } else {
                if (!isset($node->children[$part])) {
                    $node->children[$part] = new TrieNode();
                }
                $node = $node->children[$part];
            }
        }

        $node->isEndOfRoute = true;
        $node->handler[$method] = $handler;
    }

    /**
     * @param $method
     * @param $path
     * @return array
     */
    public function search($method, $path) {
        $node = $this->root;
        $parts = explode('/', trim($path, '/'));
        $params = [];

        foreach ($parts as $part) {
            if (isset($node->children[$part])) {
                $node = $node->children[$part];
            } elseif (isset($node->children[':param'])) {
                $node = $node->children[':param'];
                $params[$node->paramName] = $part;
            } else {
                return [null, array()];
            }
        }

        if ($node->isEndOfRoute && isset($node->handler[$method])) {
            return [$node->handler[$method], $params];
        }

        return [null, array()];
    }
}