<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Регистрация студента — Главная</title>
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
      max-width: 600px;
    }
    .error { color: #e74c3c; background: #ffecec; padding: 12px; border-radius: 8px; margin: 15px 0; }
    .success { color: #27ae60; background: #e8f5e9; padding: 12px; border-radius: 8px; margin: 15px 0; }
    a { color: #9b59b6; text-decoration: none; margin-right: 15px; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Регистрация студента</h2>

    <div>
      <a href="form.html">Заполнить форму</a> |
      <a href="view.php">Все записи</a>
    </div>

    <?php if (!empty($_SESSION['errors'])): ?>
      <div class="error">
        <strong>Ошибки:</strong>
        <ul style="margin: 8px 0 0; padding-left: 20px;">
          <?php foreach ($_SESSION['errors'] as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['name'])): ?>
      <div class="success">
        <p><strong>Данные из сессии:</strong></p>
        <ul style="margin: 8px 0 0; padding-left: 20px;">
          <li>Имя: <?= htmlspecialchars($_SESSION['name']) ?></li>
          <li>Возраст: <?= (int)$_SESSION['age'] ?></li>
          <li>Факультет: <?= htmlspecialchars($_SESSION['faculty']) ?></li>
          <li>Согласие с правилами: <?= $_SESSION['rules'] ? 'Да' : 'Нет' ?></li>
          <li>Форма обучения: <?= htmlspecialchars($_SESSION['study_form']) ?></li>
        </ul>
      </div>
    <?php else: ?>
      <p>Данных пока нет.</p>
    <?php endif; ?>
  </div>
</body>
</html>
