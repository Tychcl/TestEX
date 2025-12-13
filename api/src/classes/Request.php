<?php

namespace Classes;

class Request
{
    public $method;
    public $uri;
    public $headers;
    public $body;
    public $cookie;
    public $files;
    public $params;
    
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->headers = getallheaders();
        $this->body = file_get_contents('php://input');
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->params = $_REQUEST;
    }
}
?>