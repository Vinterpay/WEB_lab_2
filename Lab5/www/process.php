<?php
include ''Student.php'';
try {
    $pdo = new PDO(''mysql:host=db;dbname=student_db'', ''student_user'', ''student_pass'');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $student = new Student($pdo);
    $student->createTable();
    if ($_POST) {
        $student->addStudent($_POST[''name''], $_POST[''email''], $_POST[''group_name'']);
        echo "<h2>Студент добавлен!</h2>";
    }
    $students = $student->getAllStudents();
    echo "<h2>Все студенты:</h2>";
    if (count($students) > 0) {
        echo "<table class=''students-table''>";
        echo "<tr><th>ID</th><th>Имя</th><th>Email</th><th>Группа</th><th>Дата</th></tr>";
        foreach ($students as $s) {
            echo "<tr>";
            echo "<td>{$s[''id'']}</td>";
            echo "<td>{$s[''name'']}</td>";
            echo "<td>{$s[''email'']}</td>";
            echo "<td>{$s[''group_name'']}</td>";
            echo "<td>{$s[''created_at'']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    echo ''<div class="nav-links">'';
    echo ''<a href="form.html" class="btn">Добавить еще</a>'';
    echo ''<a href="index.php" class="btn">На главную</a>'';
    echo ''</div>'';
} catch(PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>
