<?php

namespace Sail;

class Node {
    public $value;
    public $next = array();
    public $callable = array();
    public $middleware = array();

    public function __construct($value) {
        $this->value = $value;
    }

    public function getNext($key) {
        return isset($this->next[$key]) ? $this->next[$key] : null;
    }

    public function putNext($key, $node)  {
        $this->next[$key] = $node;
    }

    public function getCallable($key) {
        return isset($this->callable[$key]) ? $this->callable[$key] : null;
    }

    public function putCallable($key, $value) {
        $this->callable[$key] = $value;
    }

    public function merge($node) {
        $this->next = array_merge($this->next, $node->next);
        $this->callable = array_merge($this->callable, $node->callable);
        $this->middleware = array_merge($this->middleware, $node->middleware);
    }
    
    public function setMiddleware($middleware) {
        $this->middleware = $middleware;
        
        foreach ($this->next as $next) {
            $next->setMiddleware($middleware);
        }
    }
    
    public function getMiddleware() {
        return $this->middleware;
    }

}