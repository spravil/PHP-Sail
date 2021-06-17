<?php
namespace Sail;
use Sail\Exceptions\NoSuchRouteException;
use Sail\Exceptions\NoCallableException;
use Sail\Exceptions\NoMiddlewareException;

class Sail extends Tree {

    /**
     *
     * {@inheritDoc}
     *
     * @see \Sail\Tree::build()
     */
    public function build () {}

    /**
     * Runs the application and handles
     * the request
     */
    public function run () {
        $request = new Request();
        $response = new Response();
        
        list ($node, $parameters) = $this->getNode($request->getPattern());
        
        if ($node == null) {
            throw new NoSuchRouteException($request->getPattern());
        }
        
        $callable = $node->getCallable($request->getRequestMethod());
        
        if ($callable == null) {
            throw new NoSuchRouteException($request->getPattern());
        } else {
            if (! is_callable($callable)) {
                throw new NoCallableException();
            }
        }
        
        array_unshift($parameters, $response);
        array_unshift($parameters, $request);
        
        $success = true;
        foreach ($node->middleware as $middleware) {
            if (! $middleware instanceof Middleware) {
                throw new NoMiddlewareException();
            }
            
            if (! call_user_func_array(array(
                    $middleware,
                    'call'
            ), $parameters)) {
                $success = false;
            }
        }
        
        if ($success) {
            call_user_func_array($callable, $parameters);
        }
        
        $response->send();
    }
}