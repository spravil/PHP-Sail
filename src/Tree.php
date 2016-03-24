<?php
/**
 * Sail Micro-Framework
 *
 * @link        https://github.com/FunnyItsElmo/Sail
 * @author      Julian Spravil <julian.spr@t-online.de> https://github.com/FunnyItsElmo
 * @copyright   Copyright (c) 2016 Julian Spravil
 * @license     https://github.com/FunnyItsElmo/Sail/blob/master/LICENSE
 */
namespace Sail;
require 'Node.php';
require 'Request.php';
require 'Response.php';
require 'Middleware.php';

class NoSuchRouteException extends \Exception
{
}

abstract class Tree
{

    public $root;

    public function __construct ()
    {
        $this->root = new Node('');
        $this->build();
    }

    public abstract function build ();

    /**
     * Merges the node for the specified pattern
     * with the root node of the specified tree
     */
    public function tree ()
    {
        $args = func_get_args();
        $pattern = array_shift($args);
        $pattern = trim($pattern, '/');
        $tree = array_pop($args);
        
        if (! $tree instanceof self) {
            throw new \Exception('Tree expected got ' . gettype($tree));
        }
        
        if (! empty($args)) {
            $tree->root->setMiddleware($args);
        }
        
        $node = $pattern != '' ? $this->defineRoute($pattern) : $this->root;
        $node->merge($tree->root);
        
        return $node;
    }

    /**
     * Adds GET route
     */
    public function get ()
    {
        $args = func_get_args();
        $this->map($args, Request::METHOD_GET, Request::METHOD_HEAD);
    }

    /**
     * Adds PUT route
     */
    public function put ()
    {
        $args = func_get_args();
        $this->map($args, Request::METHOD_PUT);
    }

    /**
     * Adds POST route
     */
    public function post ()
    {
        $args = func_get_args();
        $this->map($args, Request::METHOD_POST);
    }

    /**
     * Adds PATCH route
     */
    public function patch ()
    {
        $args = func_get_args();
        $this->map($args, Request::METHOD_PATCH);
    }

    /**
     * Adds DELETE route
     */
    public function delete ()
    {
        $args = func_get_args();
        $this->map($args, Request::METHOD_DELETE);
    }

    /**
     * Adds OPTIONS route
     */
    public function options ()
    {
        $args = func_get_args();
        $this->map($args, Request::METHOD_OPTIONS);
    }

    /**
     * Runs the application and handles
     * the request
     */
    public function run ()
    {
        $request = new Request();
        $response = new Response();
        
        list ($node, $parameters) = $this->getNode($request->getPattern());
        
        if ($node == null) {
            throw new NoSuchRouteException(
                    'The route ' . $request->getPattern() . ' does not exist');
        }
        
        $callable = $node->getCallable($request->getRequestMethod());
        
        if ($callable == null) {
            throw new NoSuchRouteException(
                    'The route ' . $request->getPattern() . ' does not exist');
        } else 
            if (! is_callable($callable)) {
                throw new \Exception(
                        'Callable expected got ' . gettype($callable));
            }
        
        array_unshift($parameters, $response);
        array_unshift($parameters, $request);
        
        $success = true;
        foreach ($node->middleware as $middleware) {
            if (! $middleware instanceof Middleware) {
                throw new \Exception(
                        'Object is not instance of class Middleware!');
            }
            
            if (! call_user_func_array(
                    array(
                            $middleware,
                            'call'
                    ), $parameters)) {
                $success = false;
            }
        }
        
        if ($success) {
            call_user_func_array($callable, $parameters);
        }
    }

    /**
     * Defines the node and adds
     * the callable
     */
    private function map ()
    {
        $args = func_get_args();
        $funcArgs = array_shift($args);
        $pattern = array_shift($funcArgs);
        $pattern = trim($pattern, '/');
        $callable = array_pop($funcArgs);
        
        $node = $pattern != '' ? $this->defineRoute($pattern) : $this->root;
        
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
    private function defineRoute ($pattern)
    {
        return $this->retrieveRoute($pattern, 
                function  ($waypoint, $temp)
                {
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

    /**
     * Searches the node which matchs the
     * specified pattern
     *
     * @param unknown $pattern            
     */
    private function getNode ($pattern)
    {
        $parameters = array();
        $node = $this->retrieveRoute($pattern, 
                function  ($waypoint, $temp) use ( &$parameters)
                {
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
    private function retrieveRoute ($pattern, $callable)
    {
        $pattern = trim($pattern, '/');
        $route = explode('/', $pattern);
        
        $temp = $this->root;
        foreach ($route as $waypoint) {
            $temp = $callable($waypoint, $temp);
        }
        
        return $temp;
    }
}