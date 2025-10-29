<?php
header('Content-Type: text/html; charset=utf-8');
include 'Student.php';

try {
    $pdo = new PDO(
        'mysql:host=db;dbname=student_db',
        'student_user',
        'student_pass'
    );
    $pdo->exec("SET NAMES 'utf8mb4'");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $student = new Student($pdo);
    $student->createTable();
    
    $message = '';
    $messageType = '';
    $studentData = null;
    
    if ($_POST) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $faculty = $_POST['faculty'];
        $course = $_POST['course'];
        $group_name = $_POST['group_name'];
        $birth_date = $_POST['birth_date'];
        $address = $_POST['address'];
        
        if ($student->addStudent($full_name, $email, $phone, $faculty, $course, $group_name, $birth_date, $address)) {
            $message = '🎉 Студент успешно зарегистрирован в системе!';
            $messageType = 'success';
            $studentData = $_POST;
        } else {
            $message = '❌ Ошибка при регистрации студента. Возможно, email уже используется.';
            $messageType = 'error';
        }
    }
    
    $students = $student->getAllStudents();
    
} catch(PDOException $e) {
    $message = '❌ Ошибка базы данных: ' . $e->getMessage();
    $messageType = 'error';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результат регистрации - Университет</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Результат регистрации</h1>
        </div>
        
        <div class="content">
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($studentData && $messageType == 'success'): ?>
                <div class="card">
                    <h2>📄 Данные зарегистрированного студента</h2>
                    <div class="student-card">
                        <h3><?php echo htmlspecialchars($studentData['full_name']); ?></h3>
                        <p><strong>📧 Email:</strong> <?php echo htmlspecialchars($studentData['email']); ?></p>
                        <p><strong>📞 Телефон:</strong> <?php echo htmlspecialchars($studentData['phone']); ?></p>
                        <p><strong>🎓 Факультет:</strong> <?php echo htmlspecialchars($studentData['faculty']); ?></p>
                        <p><strong>📚 Курс:</strong> <?php echo htmlspecialchars($studentData['course']); ?></p>
                        <p><strong>👥 Группа:</strong> <?php echo htmlspecialchars($studentData['group_name']); ?></p>
                        <p><strong>🎂 Дата рождения:</strong> <?php echo $studentData['birth_date']; ?></p>
                        <?php if (!empty($studentData['address'])): ?>
                            <p><strong>🏠 Адрес:</strong> <?php echo htmlspecialchars($studentData['address']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (count($students) > 0): ?>
                <div class="card">
                    <h2>👥 Последние зарегистрированные студенты</h2>
                    <div class="table-container">
                        <table class="students-table">
                            <thead>
                                <tr>
                                    <th>ФИО</th>
                                    <th>Факультет</th>
                                    <th>Курс</th>
                                    <th>Группа</th>
                                    <th>Дата регистрации</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $recentStudents = array_slice($students, 0, 5);
                                foreach ($recentStudents as $stud): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($stud['full_name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($stud['faculty']); ?></td>
                                        <td><?php echo htmlspecialchars($stud['course']); ?></td>
                                        <td><?php echo htmlspecialchars($stud['group_name']); ?></td>
                                        <td><?php echo $stud['registration_date']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="nav-links">
                <a href="register.html" class="btn">➕ Новая регистрация</a>
                <a href="students.php" class="btn btn-secondary">👥 Все студенты</a>
                <a href="index.php" class="btn">🏠 На главную</a>
            </div>
        </div>
    </div>
</body>
</html>
