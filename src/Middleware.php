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

interface Middleware
{

    /**
     * Here you can do stuff before the actual request
     * being processed. For example you can verify if 
     * the user is really allowed to request this route. 
     * If you return false the request stops at this 
     * point and directly executes the response.
     *
     * @param Request $request            
     * @param Response $response            
     * @param mixed $...            
     *
     * @return boolean
     */
    public function call ();
}