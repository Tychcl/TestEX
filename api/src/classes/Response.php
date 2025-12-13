<?php

namespace Classes;

class Response {
    public $status;
    public $body;
    public $headers = [];
    
    public function __construct($status = 200, $body = null) {
        $this->setSecurityHeaders();
        $this->status = $status;
        $this->body = $body;
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
    
    public function send() {
        http_response_code($this->status);
        
        foreach ($this->headers as $header) {
            header($header);
        }

        if (is_array($this->body)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($this->body, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
        } else {
            echo htmlspecialchars($this->body, ENT_QUOTES, 'UTF-8');
        }
    }
}