<?php
class UserInfo {
    public static function getInfo(): array {
        $server = $_SERVER;
        
        return [
            'ip' => $server['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $server['HTTP_USER_AGENT'] ?? 'unknown',
            'time' => date('Y-m-d H:i:s'),
            'server_protocol' => $server['SERVER_PROTOCOL'] ?? 'unknown',
            'request_method' => $server['REQUEST_METHOD'] ?? 'unknown',
            'ssl_protocol' => $server['HTTPS'] ?? 'off',
            'ssl_cipher' => $server['SSL_CIPHER'] ?? 'none',
            'server_software' => $server['SERVER_SOFTWARE'] ?? 'unknown',
            'php_version' => PHP_VERSION,
            'script_name' => $server['SCRIPT_NAME'] ?? 'unknown'
        ];
    }
}