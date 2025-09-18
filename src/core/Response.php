<?php

namespace Core;

class Response {
    public $status = 200;
    public $body;
    public $headers = [];
    public $cookies = [];
    
    public function __construct() {
        $this->setSecurityHeaders();
    }
    
    private function setSecurityHeaders() {
        $this->headers[] = 'X-Content-Type-Options: nosniff';
        $this->headers[] = 'X-Frame-Options: DENY';
        $this->headers[] = 'X-XSS-Protection: 1; mode=block';
        
        $csp = [
            "default-src 'self'",
            "script-src 'self'",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data:",
            "connect-src 'self'",
            "frame-ancestors 'none'"
        ];
        $this->headers[] = 'Content-Security-Policy: ' . implode('; ', $csp);
    }

    public function setCook($name, $value, $expire = 0, $path = '', $domain = '', $secure = true, $httponly = true, $samesite = 'Strict') {
        $this->cookies[] = [
            'name' => $name,
            'value' => $value,
            'expire' => $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite
        ];
    }
    
    public function send() {
        http_response_code($this->status);
        
        foreach ($this->headers as $header) {
            header($header);
        }
        
        foreach ($this->cookies as $cookie) {
            setcookie(
                $cookie['name'],
                $cookie['value'],
                [
                    'expires' => $cookie['expire'],
                    'path' => $cookie['path'],
                    'domain' => $cookie['domain'],
                    'secure' => $cookie['secure'],
                    'httponly' => $cookie['httponly'],
                    'samesite' => $cookie['samesite']
                ]
            );
        }

        if (is_array($this->body)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($this->body, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
        } else {
            echo htmlspecialchars($this->body, ENT_QUOTES, 'UTF-8');
        }
    }
}