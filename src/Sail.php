<?php
namespace Sail;

class Sail extends Tree
{

    /**
     *
     * {@inheritDoc}
     *
     * @see \Sail\Tree::build()
     */
    public function build ()
    {}

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
}