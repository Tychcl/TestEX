<?php

namespace Core;

class Response
{
    public $status = 200;
    public $headers = [];
    public $body;
    
    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->body;
    }
} 
?>