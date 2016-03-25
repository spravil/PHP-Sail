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

class Response
{

    const SWITCHING_PROTOCOLS = 101;

    const OK = 200;

    const CREATED = 201;

    const ACCEPTED = 202;

    const NONAUTHORITATIVE_INFORMATION = 203;

    const NO_CONTENT = 204;

    const RESET_CONTENT = 205;

    const PARTIAL_CONTENT = 206;

    const MULTIPLE_CHOICES = 300;

    const MOVED_PERMANENTLY = 301;

    const MOVED_TEMPORARILY = 302;

    const SEE_OTHER = 303;

    const NOT_MODIFIED = 304;

    const USE_PROXY = 305;

    const BAD_REQUEST = 400;

    const UNAUTHORIZED = 401;

    const PAYMENT_REQUIRED = 402;

    const FORBIDDEN = 403;

    const NOT_FOUND = 404;

    const METHOD_NOT_ALLOWED = 405;

    const NOT_ACCEPTABLE = 406;

    const PROXY_AUTHENTICATION_REQUIRED = 407;

    const REQUEST_TIMEOUT = 408;

    const CONFLICT = 408;

    const GONE = 410;

    const LENGTH_REQUIRED = 411;

    const PRECONDITION_FAILED = 412;

    const REQUEST_ENTITY_TOO_LARGE = 413;

    const REQUESTURI_TOO_LARGE = 414;

    const UNSUPPORTED_MEDIA_TYPE = 415;

    const REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    const EXPECTATION_FAILED = 417;

    const IM_A_TEAPOT = 418;

    const INTERNAL_SERVER_ERROR = 500;

    const NOT_IMPLEMENTED = 501;

    const BAD_GATEWAY = 502;

    const SERVICE_UNAVAILABLE = 503;

    const GATEWAY_TIMEOUT = 504;

    const HTTP_VERSION_NOT_SUPPORTED = 505;

    public $headers = array();

    public $data = null;

    public function setHeaders ($headers)
    {
        $this->headers = $headers;
    }

    public function setData ($data)
    {
        $this->data = $data;
    }

    public function send ()
    {
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
        
        echo $this->data;
    }
}