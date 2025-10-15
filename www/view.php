<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Все записи</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9f6ff;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .container {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.12);
      width: 90%;
      max-width: 800px;
    }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    th { background-color: #ecf0f1; }
    a { display: inline-block; margin-top: 20px; color: #9b59b6; text-decoration: none; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Все сохранённые регистрации</h2>

    <?php
    $file = __DIR__ . '/data.txt';
    if (file_exists($file) && filesize($file) > 0) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!empty($lines)) {
            echo '<table><thead><tr>
                    <th>Имя</th><th>Возраст</th><th>Факультет</th><th>Правила</th><th>Форма обучения</th>
                  </tr></thead><tbody>';
            foreach ($lines as $line) {
                $parts = explode(';', $line);
                if (count($parts) === 5) {
                    echo '<tr>';
                    foreach ($parts as $part) {
                        echo '<td>' . htmlspecialchars($part) . '</td>';
                    }
                    echo '</tr>';
                }
            }
            echo '</tbody></table>';
        }
    } else {
        echo "<p>Нет данных.</p>";
    }
    ?>

    <a href="index.php">← На главную</a>
  </div>
</body>
</html>
