<?php
/**
 * Sail Micro-Framework
 *
 * @link        https://github.com/FunnyItsElmo/PHP-Sail.git
 * @author      Julian Spravil <julian.spr@t-online.de>
 * @copyright   Copyright (c) 2016 Julian Spravil
 * @license     https://github.com/FunnyItsElmo/Sail/blob/master/LICENSE
 */
namespace Sail;

abstract class Tree {

    public $root;

    public function __construct () {
        $this->root = new Node('');
        $this->build();
    }

    public abstract function build ();

    /**
     * Merges the node for the specified pattern
     * with the root node of the specified tree
     */
    public function tree () {
        $args = func_get_args();
        $pattern = array_shift($args);
        $tree = array_pop($args);
        
        if (! $tree instanceof self) {
            throw new \Exception('Tree expected got ' . gettype($tree));
        }
        
        if (! empty($args)) {
            $tree->root->setMiddleware($args);
        }
        
        $node = $this->defineRoute($pattern);
        $node->merge($tree->root);
        
        return $node;
    }

    /**
     * Adds GET route
     */
    public function get () {
        $args = func_get_args();
        $this->map($args, Request::METHOD_GET, Request::METHOD_HEAD);
    }

    /**
     * Adds PUT route
     */
    public function put () {
        $args = func_get_args();
        $this->map($args, Request::METHOD_PUT);
    }

    /**
     * Adds POST route
     */
    public function post () {
        $args = func_get_args();
        $this->map($args, Request::METHOD_POST);
    }

    /**
     * Adds PATCH route
     */
    public function patch () {
        $args = func_get_args();
        $this->map($args, Request::METHOD_PATCH);
    }

    /**
     * Adds DELETE route
     */
    public function delete () {
        $args = func_get_args();
        $this->map($args, Request::METHOD_DELETE);
    }

    /**
     * Adds OPTIONS route
     */
    public function options () {
        $args = func_get_args();
        $this->map($args, Request::METHOD_OPTIONS);
    }

    /**
     * Searches the node which matchs the
     * specified pattern
     *
     * @param unknown $pattern            
     */
    protected function getNode ($pattern) {
        $parameters = array();
        $node = $this->retrieveRoute($pattern, 
                function  ($waypoint, $temp) use ( &$parameters) {
                    $next = $temp->getNext($waypoint);
                    
                    if ($next == null) {
                        $next = $temp->getNext('{}');
                        if ($next == null) {
                            return null;
                        } else {
                            $parameters[] = $waypoint;
                        }
                    }
                    
                    return $next;
                });
        
        return array(
                $node,
                $parameters
        );
    }

    /**
     * Retrieves the route for the specified pattern
     *
     * @param unknown $pattern            
     * @param unknown $callable            
     */
    protected function retrieveRoute ($pattern, $callable) {
        $pattern = trim($pattern, '/');
        
        if ($pattern == '') {
            return $this->root;
        }
        
        $route = explode('/', $pattern);
        
        $temp = $this->root;
        foreach ($route as $waypoint) {
            $temp = $callable($waypoint, $temp);
        }
        
        return $temp;
    }

    /**
     * Defines the node and adds
     * the callable
     */
    private function map () {
        $args = func_get_args();
        $funcArgs = array_shift($args);
        $pattern = array_shift($funcArgs);
        $callable = array_pop($funcArgs);
        
        $node = $this->defineRoute($pattern);
        
        if (! empty($funcArgs)) {
            $node->setMiddleware($args);
        }
        
        if (! empty($args)) {
            foreach ($args as $arg) {
                $node->putCallable($arg, $callable);
            }
        }
        
        return $node;
    }

    /**
     * Defines and creates the nodes for the
     * specified pattern
     *
     * @param unknown $pattern            
     */
    private function defineRoute ($pattern) {
        return $this->retrieveRoute($pattern, 
                function  ($waypoint, $temp) {
                    $key = $waypoint;
                    if (preg_match('/{[a-zA-Z0-9]*}/', $waypoint)) {
                        $key = '{}';
                    }
                    
                    $next = $temp->getNext($key);
                    if ($next == null) {
                        $next = new Node($waypoint);
                        $temp->putNext($key, $next);
                    }
                    
                    return $next;
                });
    }
}