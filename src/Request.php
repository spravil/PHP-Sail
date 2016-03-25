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

class Request {

    const METHOD_HEAD = 'HEAD';

    const METHOD_GET = 'GET';

    const METHOD_POST = 'POST';

    const METHOD_PUT = 'PUT';

    const METHOD_PATCH = 'PATCH';

    const METHOD_DELETE = 'DELETE';

    const METHOD_OPTIONS = 'OPTIONS';

    public $pattern;

    public function __construct () {
        $this->pattern = isset($_GET['pattern']) ? $_GET['pattern'] : '/';
    }

    public function getHeaders () {
        return getallheaders();
    }

    public function getRequestMethod () {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPattern () {
        return $this->pattern;
    }
}