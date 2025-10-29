<?php
header(''Content-Type: text/html; charset=utf-8'');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Система студентов</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Система управления студентами</h1>
        </div>
        <div class="content">
            <div class="card">
                <h2>Статус системы</h2>
                <?php
                try {
                    $pdo = new PDO(''mysql:host=db;dbname=student_db'', ''student_user'', ''student_pass'');
                    echo ''<p style="color: green;">✅ База данных подключена</p>'';
                    $student = new Student($pdo);
                    $student->createTable();
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM students");
                    $count = $stmt->fetch()[''count''];
                    echo "<p><strong>Студентов в базе:</strong> $count</p>";
                } catch (PDOException $e) {
                    echo ''<p style="color: red;">❌ Ошибка: '' . $e->getMessage() . ''</p>'';
                }
                ?>
            </div>
            <div class="nav-links">
                <a href="form.html" class="btn">Добавить студента</a>
                <a href="list.php" class="btn">Список студентов</a>
            </div>
        </div>
    </div>
</body>
</html>
