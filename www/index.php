<?php
session_start();

// Обработка AJAX-запроса на обновление API
if (($_GET['action'] ?? null) === 'refresh_api') {
    header('Content-Type: application/json; charset=utf-8');
    require_once 'ApiClient.php';
    $api = new ApiClient();
    
    // API endpoints для студенческой тематики
    $endpoints = [
        'https://jsonplaceholder.typicode.com/posts?_limit=3',
        'https://api.spaceflightnewsapi.net/v4/articles/?limit=3'
    ];
    
    $cacheFile = __DIR__ . '/api_cache.json';
    $cacheTtl = 300; // 5 минут
    
    // Используем кеш если он свежий
    if (file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheTtl) {
        $data = json_decode(file_get_contents($cacheFile), true);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Пробуем endpoints по очереди
    foreach ($endpoints as $url) {
        $data = $api->request($url);
        if (!isset($data['error'])) {
            // Сохраняем успешный результат
            file_put_contents($cacheFile, json_encode([
                'source' => $url,
                'data' => $data,
                'cached_at' => date('Y-m-d H:i:s')
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            
            echo json_encode(['source' => $url, 'data' => $data], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    // Если все API упали, создаем демо-данные
    $demoData = [
        'source' => 'demo',
        'data' => [
            'results' => [
                [
                    'title' => 'Демо: Важная информация для студентов',
                    'url' => '#',
                    'news_site' => 'Учебный портал'
                ],
                [
                    'title' => 'Демо: Расписание сессии 2024', 
                    'url' => '#',
                    'news_site' => 'Учебный портал'
                ]
            ]
        ],
        'cached_at' => date('Y-m-d H:i:s')
    ];
    
    file_put_contents($cacheFile, json_encode($demoData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo json_encode($demoData, JSON_UNESCAPED_UNICODE);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Регистрация студента — ЛР4</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 900px; margin: 20px auto; padding: 0 15px; background-color: #f9f7f0; }
    .section { margin: 25px 0; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h1, h2 { color: #2c3e50; }
    h1 { text-align: center; border-bottom: 3px solid #27ae60; padding-bottom: 10px; }
    button { padding: 10px 20px; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; }
    button:hover { background: #219653; }
    .error { color: #e74c3c; }
    .success { color: #27ae60; }
    .loading { color: #7f8c8d; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px; }
    .info-item { padding: 12px; background: #ecf8f2; border-radius: 6px; border-left: 4px solid #27ae60; }
    .ssl-on { color: #27ae60; font-weight: bold; }
    .ssl-off { color: #e67e22; font-weight: bold; }
    .demo-notice { background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 4px solid #ffc107; margin: 15px 0; }
    .api-info { background: #e3f2fd; padding: 12px; border-radius: 6px; margin: 15px 0; }
  </style>
</head>
<body>

<h1>🎓 Регистрация студента</h1>

<div class="section">
  <a href="form.html" style="background: #3498db; color: white; padding: 10px 15px; text-decoration: none; border-radius: 6px; display: inline-block;">📝 Заполнить форму регистрации</a>
</div>

<?php if (isset($_SESSION['form_data'])): ?>
<div class="section">
  <h2>✅ Регистрация завершена!</h2>
  <?php
  $labels = ['name' => 'Имя', 'age' => 'Возраст', 'faculty' => 'Факультет', 'study_form' => 'Форма обучения'];
  $facultyMap = ['it' => 'Информационные технологии', 'economics' => 'Экономика', 'medicine' => 'Медицина', 'law' => 'Юриспруденция'];
  $studyFormMap = ['full-time' => 'Очно', 'part-time' => 'Заочно'];
  
  foreach ($_SESSION['form_data'] as $key => $value):
      if ($key === 'rules') continue;
      $label = $labels[$key] ?? $key;
      if ($key === 'faculty') $value = $facultyMap[$value] ?? $value;
      if ($key === 'study_form') $value = $studyFormMap[$value] ?? $value;
      echo "<p><strong>{$label}:</strong> " . htmlspecialchars($value) . "</p>";
  endforeach;
  echo isset($_SESSION['form_data']['rules']) ? "<p><strong>Согласие с правилами:</strong> ✅ Да</p>" : "<p><strong>Согласие с правилами:</strong> ❌ Нет</p>";
  ?>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['user_info'])): ?>
<div class="section">
  <h2>🔧 Информация о соединении</h2>
  <div class="info-grid">
    <?php 
    $userInfo = $_SESSION['user_info'];
    $sslStatus = ($userInfo['ssl_protocol'] === 'on' || $userInfo['ssl_protocol'] === '1') ? 'ssl-on' : 'ssl-off';
    $sslText = ($userInfo['ssl_protocol'] === 'on' || $userInfo['ssl_protocol'] === '1') ? 'ВКЛЮЧЕНО' : 'ВЫКЛЮЧЕНО';
    
    $infoItems = [
        '👤 IP адрес' => $userInfo['ip'],
        '🌐 Браузер' => $userInfo['user_agent'],
        '🕐 Время запроса' => $userInfo['time'],
        '🔒 SSL протокол' => "<span class='$sslStatus'>$sslText</span>",
        '🔑 SSL шифрование' => $userInfo['ssl_cipher'],
        '📡 Протокол' => $userInfo['server_protocol'],
        '⚡ Метод' => $userInfo['request_method'],
        '🖥️ Сервер' => $userInfo['server_software'],
        '🐘 PHP версия' => $userInfo['php_version']
    ];
    
    foreach ($infoItems as $label => $value): ?>
        <div class="info-item">
            <strong><?= $label ?>:</strong><br>
            <?= $value ?>
        </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<?php if (isset($_COOKIE['last_submission'])): ?>
<div class="section">
  <h2>📅 Последняя регистрация</h2>
  <p><strong>Время отправки:</strong> <?= htmlspecialchars($_COOKIE['last_submission']) ?></p>
</div>
<?php endif; ?>

<div class="section">
  <h2>📚 Актуальная информация (API)</h2>
  
  <div class="api-info">
    <strong>🎯 Источники:</strong> Новости и образовательные материалы
  </div>
  
  <button id="refreshBtn">🔄 Обновить данные</button>
  
  <div id="apiResult">
    <?php
    $cacheFile = __DIR__ . '/api_cache.json';
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if (isset($cached['error'])) {
            echo "<p class='error'>Ошибка API: " . htmlspecialchars($cached['error']) . "</p>";
        } elseif (!empty($cached['data'])) {
            $data = $cached['data'];
            $isDemo = ($cached['source'] ?? '') === 'demo';
            
            if ($isDemo) {
                echo "<div class='demo-notice'>⚠️ Используются демо-данные</div>";
            } else {
                echo "<p class='success'>✅ Данные загружены (кеш: " . ($cached['cached_at'] ?? 'unknown') . ")</p>";
            }
            
            if (isset($data['results'])) {
                echo "<h3>📰 Последние новости:</h3><ul>";
                foreach ($data['results'] as $item) {
                    echo "<li><a href='" . htmlspecialchars($item['url']) . "' target='_blank'>" . htmlspecialchars($item['title']) . "</a>" . (isset($item['news_site']) ? " — " . htmlspecialchars($item['news_site']) : "") . "</li>";
                }
                echo "</ul>";
            } elseif (is_array($data) && isset($data[0]['title'])) {
                echo "<h3>📖 Полезные материалы:</h3><ul>";
                foreach ($data as $item) {
                    $title = $item['title'] ?? 'No title';
                    $desc = $item['body'] ?? '';
                    echo "<li><strong>" . htmlspecialchars($title) . "</strong>: " . htmlspecialchars(substr($desc, 0, 100)) . "...</li>";
                }
                echo "</ul>";
            }
        }
    } else {
        echo "<p>Данные ещё не загружены. Нажмите «Обновить».</p>";
    }
    ?>
  </div>
</div>

<script>
document.getElementById('refreshBtn').addEventListener('click', async () => {
  const btn = document.getElementById('refreshBtn');
  const resultDiv = document.getElementById('apiResult');
  
  btn.disabled = true;
  btn.textContent = 'Загрузка...';
  resultDiv.innerHTML = '<p class="loading">⏳ Загружаем данные...</p>';
  
  try {
    const res = await fetch('/?action=refresh_api');
    const data = await res.json();
    
    let html = '';
    if (data.error) {
      html = `<p class="error">❌ Ошибка: ${data.error}</p>`;
    } else if (data.data) {
      const apiData = data.data;
      const isDemo = data.source === 'demo';
      
      if (isDemo) {
        html = `<div class="demo-notice">⚠️ Используются демо-данные</div>`;
      } else {
        html = `<p class="success">✅ Данные обновлены!</p>`;
      }
      
      if (apiData.results) {
        html += '<h3>📰 Последние новости:</h3><ul>';
        apiData.results.forEach(item => {
          html += `<li><a href="${item.url}" target="_blank">${item.title}</a> — ${item.news_site || ''}</li>`;
        });
        html += '</ul>';
      } else if (Array.isArray(apiData) && apiData[0]) {
        html += '<h3>📖 Полезные материалы:</h3><ul>';
        apiData.forEach(item => {
          const title = item.title || 'No title';
          const desc = item.body || '';
          html += `<li><strong>${title}</strong>: ${desc.substring(0, 100)}...</li>`;
        });
        html += '</ul>';
      }
    } else {
      html = '<p>Неизвестный формат ответа</p>';
    }
    
    resultDiv.innerHTML = html;
  } catch (e) {
    resultDiv.innerHTML = '<p class="error">❌ Ошибка сети: ' + e.message + '</p>';
  } finally {
    btn.disabled = false;
    btn.textContent = '🔄 Обновить данные';
  }
});
</script>

</body>
</html>