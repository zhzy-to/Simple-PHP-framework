<?php

namespace App\Helpers\Router;

class TrieNode
{
    public $children;
    public $isEndOfRoute;

    public $handler;
    public $paramName;

    public function __construct() {
        $this->children = [];
        $this->isEndOfRoute = false;
        $this->handler = [];
        $this->paramName = null;
    }
}