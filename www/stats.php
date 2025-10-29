<?php
header('Content-Type: text/html; charset=utf-8');
include 'Student.php';

try {
    $pdo = new PDO('mysql:host=db;dbname=student_db', 'student_user', 'student_pass');
    $pdo->exec("SET NAMES 'utf8mb4'");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $student = new Student($pdo);
    $stats = $student->getStats();
    $students = $student->getAllStudents();
    
} catch(PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика - Университет</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📈 Статистика студентов</h1>
            <p>Аналитика и отчетность по студентам</p>
        </div>
        
        <div class="content">
            <?php if (isset($error)): ?>
                <div class="message error">
                    ❌ Ошибка загрузки данных: <?php echo $error; ?>
                </div>
            <?php else: ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['total']; ?></div>
                        <div class="stat-label">Всего студентов</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['active']; ?></div>
                        <div class="stat-label">Активных</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['total'] - $stats['active']; ?></div>
                        <div class="stat-label">Неактивных</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($stats['by_faculty']); ?></div>
                        <div class="stat-label">Факультетов</div>
                    </div>
                </div>
                
                <div class="card">
                    <h2>🎓 Распределение по факультетам</h2>
                    <div class="table-container">
                        <table class="students-table">
                            <thead>
                                <tr>
                                    <th>Факультет</th>
                                    <th>Количество студентов</th>
                                    <th>Процент</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['by_faculty'] as $faculty): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($faculty['faculty']); ?></strong></td>
                                        <td><?php echo $faculty['count']; ?></td>
                                        <td><?php echo round(($faculty['count'] / $stats['total']) * 100, 2); ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card">
                    <h2>📅 Последние регистрации</h2>
                    <?php if (count($students) > 0): ?>
                        <div class="table-container">
                            <table class="students-table">
                                <thead>
                                    <tr>
                                        <th>ФИО</th>
                                        <th>Факультет</th>
                                        <th>Группа</th>
                                        <th>Дата регистрации</th>
                                        <th>Статус</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $recentStudents = array_slice($students, 0, 8);
                                    foreach ($recentStudents as $stud): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($stud['full_name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($stud['faculty']); ?></td>
                                            <td><?php echo htmlspecialchars($stud['group_name']); ?></td>
                                            <td><?php echo $stud['registration_date']; ?></td>
                                            <td>
                                                <span class="status <?php echo $stud['status'] == 'active' ? 'success' : 'error'; ?>">
                                                    <?php echo $stud['status'] == 'active' ? 'Активен' : 'Неактивен'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>Нет данных для отображения.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="nav-links">
                <a href="index.php" class="btn btn-secondary">🏠 На главную</a>
                <a href="students.php" class="btn">👥 Список студентов</a>
                <a href="register.html" class="btn">➕ Новая регистрация</a>
            </div>
        </div>
    </div>
</body>
</html>
