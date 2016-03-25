<?php
/**
 * Sail Micro-Framework
 *
 * @link        https://github.com/FunnyItsElmo/Sail
 * @author      Julian Spravil <julian.spr@t-online.de> https://github.com/FunnyItsElmo
 * @copyright   Copyright (c) 2016 Julian Spravil
 * @license     https://github.com/FunnyItsElmo/Sail/blob/master/LICENSE
 */
namespace Sail\Exceptions;

class NoSuchRouteException extends \Exception
{

    protected $route;

    public function __construct ($route)
    {
        parent::__construct('The route ' . $route . ' does not exist');
        $this->route = $route;
    }

    public function getRoute ()
    {
        return $this->route;
    }
}