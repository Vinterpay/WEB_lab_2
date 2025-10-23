<?php
class ApiClient {
    private $timeout = 10;

    public function request(string $url): array {
        // Проверяем доступность хоста перед запросом
        $host = parse_url($url, PHP_URL_HOST);
        if (!$this->isHostReachable($host)) {
            return ['error' => "DNS resolution failed for: $host"];
        }
        
        try {
            // Используем file_get_contents с контекстом
            $context = stream_context_create([
                'http' => [
                    'timeout' => $this->timeout,
                    'user_agent' => 'WEB_lab_2/1.0',
                    'header' => "Accept: application/json\r\n"
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                $lastError = error_get_last();
                return ['error' => 'Failed to fetch URL: ' . ($lastError['message'] ?? 'Unknown error')];
            }
            
            $data = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['error' => 'Invalid JSON: ' . json_last_error_msg()];
            }
            
            return $data ?: ['error' => 'Empty response'];
            
        } catch (\Exception $e) {
            return ['error' => 'Exception: ' . $e->getMessage()];
        }
    }
    
    private function isHostReachable($host): bool {
        if (empty($host)) return false;
        
        // Пробуем несколько методов проверки доступности хоста
        $ip = @gethostbyname($host);
        if ($ip !== $host) return true;
        
        // Альтернативная проверка через fsockopen
        return @fsockopen($host, 80, $errno, $errstr, 5) !== false;
    }
}