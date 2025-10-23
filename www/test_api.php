<?php
require_once 'ApiClient.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== Тестирование API для студенческого портала ===\n\n";

// Проверяем SSL
echo "SSL информация:\n";
echo "HTTPS: " . (isset($_SERVER['HTTPS']) ? '✅ ' . $_SERVER['HTTPS'] : '❌ off') . "\n";
echo "SSL_CIPHER: " . ($_SERVER['SSL_CIPHER'] ?? '❌ none') . "\n";
echo "SERVER_PORT: " . ($_SERVER['SERVER_PORT'] ?? 'unknown') . "\n\n";

// Системная информация
echo "=== Системная информация ===\n";
echo "SERVER_SOFTWARE: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . "\n";
echo "PHP_VERSION: " . PHP_VERSION . "\n";
echo "allow_url_fopen: " . (ini_get('allow_url_fopen') ? '✅ On' : '❌ Off') . "\n\n";

$api = new ApiClient();

// Тестируем API endpoints
$endpoints = [
    'JSONPlaceholder' => 'https://jsonplaceholder.typicode.com/posts/1',
    'Spaceflight News' => 'https://api.spaceflightnewsapi.net/v4/articles/?limit=1'
];

foreach ($endpoints as $name => $url) {
    echo "=== Тестирование: $name ===\n";
    echo "URL: $url\n";
    
    $result = $api->request($url);
    
    if (isset($result['error'])) {
        echo "❌ ОШИБКА: {$result['error']}\n";
    } else {
        echo "✅ УСПЕХ: Получен ответ\n";
        if (isset($result['title'])) {
            echo "Заголовок: " . substr($result['title'], 0, 50) . "...\n";
        } else if (isset($result['results'][0]['title'])) {
            echo "Первый заголовок: " . substr($result['results'][0]['title'], 0, 50) . "...\n";
        }
    }
    echo "\n";
}
?>