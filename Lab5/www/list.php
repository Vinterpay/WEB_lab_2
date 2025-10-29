<?php
include ''Student.php'';
try {
    $pdo = new PDO(''mysql:host=db;dbname=student_db'', ''student_user'', ''student_pass'');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $student = new Student($pdo);
    $students = $student->getAllStudents();
} catch(PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Список студентов</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Список студентов</h1>
        </div>
        <div class="content">
            <?php if (isset($error)): ?>
                <p style="color: red;">Ошибка: <?php echo $error; ?></p>
            <?php elseif (count($students) > 0): ?>
                <table class="students-table">
                    <tr><th>ID</th><th>Имя</th><th>Email</th><th>Группа</th><th>Дата</th></tr>
                    <?php foreach ($students as $s): ?>
                    <tr>
                        <td><?php echo $s[''id'']; ?></td>
                        <td><?php echo htmlspecialchars($s[''name'']); ?></td>
                        <td><?php echo htmlspecialchars($s[''email'']); ?></td>
                        <td><?php echo htmlspecialchars($s[''group_name'']); ?></td>
                        <td><?php echo $s[''created_at'']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <p><strong>Всего: <?php echo count($students); ?></strong></p>
            <?php else: ?>
                <p>Нет студентов</p>
            <?php endif; ?>
            <div class="nav-links">
                <a href="index.php" class="btn">На главную</a>
                <a href="form.html" class="btn">Добавить</a>
            </div>
        </div>
    </div>
</body>
</html>
