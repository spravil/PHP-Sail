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

class Response {
    
    const CONTINUE = '100 Continue';

    const SWITCHING_PROTOCOLS = '101 Switching Protocols';
    
    const PROCESSING = '102 Processing';

    const OK = '200 OK';

    const CREATED = '201 Created';

    const ACCEPTED = '202 Accepted';

    const NONAUTHORITATIVE_INFORMATION = '203 Non-Authoritative Information';

    const NO_CONTENT = '204 No Content';

    const RESET_CONTENT = '205 Reset Content';

    const PARTIAL_CONTENT = '206 Partial Content';
    
    const MULTI_STATUS = '207 Multi-Status';
    
    const ALREADY_REPORTED = '208 Already Reported';
    
    const IM_USED = '226 IM Used';

    const MULTIPLE_CHOICES = '300 Multiple Choices';

    const MOVED_PERMANENTLY = '301 Moved Permanently';

    const MOVED_TEMPORARILY = '302 Found (Moved Temporarily)';

    const SEE_OTHER = '303 See Other';

    const NOT_MODIFIED = '304 Not Modified';

    const USE_PROXY = '305 Use Proxy';
    
    const TEMPORARY_REDIRECT = '307 Temporary Redirect';
    
    const PREMANENT_REDIRECT = '308 Permanent Redirect';

    const BAD_REQUEST = '400 Bad Request';

    const UNAUTHORIZED = '401 Unauthorized';

    const PAYMENT_REQUIRED = '402 Payment Required';

    const FORBIDDEN = '403 Forbidden';

    const NOT_FOUND = '404 Not Found';

    const METHOD_NOT_ALLOWED = '405 Method Not Allowed';

    const NOT_ACCEPTABLE = '406 Not Acceptable';

    const PROXY_AUTHENTICATION_REQUIRED = '407 Proxy Authentication Required';

    const REQUEST_TIMEOUT = '408 Request Time-out';

    const CONFLICT = '409 Conflict';

    const GONE = '410 Gone';

    const LENGTH_REQUIRED = '411 Length Required';

    const PRECONDITION_FAILED = '412 Precondition Failed';

    const REQUEST_ENTITY_TOO_LARGE = '413 Request Entity Too Large';

    const REQUESTURI_TOO_LARGE = '414 Request-URL Too Long';

    const UNSUPPORTED_MEDIA_TYPE = '415 Unsupported Media Type';

    const REQUESTED_RANGE_NOT_SATISFIABLE = '416 Requested range not satisfiable';

    const EXPECTATION_FAILED = '417 Expectation Failed';

    const IM_A_TEAPOT = '418 I’m a teapot';

    const INTERNAL_SERVER_ERROR = '500 Internal Server Error';

    const NOT_IMPLEMENTED = '501 Not Implemented';

    const BAD_GATEWAY = '502 Bad Gateway';

    const SERVICE_UNAVAILABLE = '503 Service Unavailable';

    const GATEWAY_TIMEOUT = '504 Gateway Time-out';

    const HTTP_VERSION_NOT_SUPPORTED = '505 HTTP Version not supported';

    public $headers = array();

    public $data = null;

    public function setHeaders ($headers) {
        $this->headers = $headers;
    }

    public function setData ($data) {
        $this->data = $data;
    }

    public function send () {
        foreach ($this->headers as $key => $value) {
            if($key != '') $key .= ': ';
            header($key . $value);
        }
        
        echo $this->data;
    }
}