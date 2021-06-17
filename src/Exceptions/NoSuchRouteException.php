<?php
namespace Sail\Exceptions;

class NoSuchRouteException extends \Exception {

    protected $route;

    public function __construct ($route) {
        parent::__construct('The route ' . $route . ' does not exist');
        $this->route = $route;
    }

    public function getRoute () {
        return $this->route;
    }
}