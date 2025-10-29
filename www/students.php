<?php
header('Content-Type: text/html; charset=utf-8');
include 'Student.php';

try {
    $pdo = new PDO('mysql:host=db;dbname=student_db', 'student_user', 'student_pass');
    $pdo->exec("SET NAMES 'utf8mb4'");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $student = new Student($pdo);
    
    // Обработка изменения статуса
    if (isset($_POST['update_status'])) {
        $student->updateStudentStatus($_POST['student_id'], $_POST['new_status']);
    }
    
    // Обработка удаления
    if (isset($_POST['delete_student'])) {
        $student->deleteStudent($_POST['student_id']);
    }
    
    $students = $student->getAllStudents();
    $stats = $student->getStats();
    
} catch(PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список студентов - Университет</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 Список всех студентов</h1>
            <p>Управление студенческими данными</p>
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
                        <div class="stat-number"><?php echo count($stats['by_faculty']); ?></div>
                        <div class="stat-label">Факультетов</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">👨‍🎓</div>
                        <div class="stat-label">В системе</div>
                    </div>
                </div>
                
                <?php if (count($students) > 0): ?>
                    <div class="table-container">
                        <table class="students-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>👤 ФИО</th>
                                    <th>📧 Email</th>
                                    <th>🎓 Факультет</th>
                                    <th>📚 Курс</th>
                                    <th>👥 Группа</th>
                                    <th>📅 Дата регистрации</th>
                                    <th>📊 Статус</th>
                                    <th>⚙️ Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $stud): ?>
                                    <tr>
                                        <td><?php echo $stud['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($stud['full_name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($stud['email']); ?></td>
                                        <td><?php echo htmlspecialchars($stud['faculty']); ?></td>
                                        <td><?php echo htmlspecialchars($stud['course']); ?></td>
                                        <td><?php echo htmlspecialchars($stud['group_name']); ?></td>
                                        <td><?php echo $stud['registration_date']; ?></td>
                                        <td>
                                            <span class="status <?php echo $stud['status'] == 'active' ? 'success' : 'error'; ?>">
                                                <?php echo $stud['status'] == 'active' ? 'Активен' : 'Неактивен'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="student_id" value="<?php echo $stud['id']; ?>">
                                                <select name="new_status" onchange="this.form.submit()">
                                                    <option value="active" <?php echo $stud['status'] == 'active' ? 'selected' : ''; ?>>Активен</option>
                                                    <option value="inactive" <?php echo $stud['status'] == 'inactive' ? 'selected' : ''; ?>>Неактивен</option>
                                                </select>
                                                <input type="hidden" name="update_status" value="1">
                                            </form>
                                            <form method="POST" style="display: inline; margin-left: 5px;">
                                                <input type="hidden" name="student_id" value="<?php echo $stud['id']; ?>">
                                                <button type="submit" name="delete_student" value="1" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Удалить студента?')">🗑️</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <p><strong>Всего студентов:</strong> <?php echo count($students); ?></p>
                <?php else: ?>
                    <div class="message info">
                        ℹ️ В системе пока нет зарегистрированных студентов.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <div class="nav-links">
                <a href="index.php" class="btn btn-secondary">🏠 На главную</a>
                <a href="register.html" class="btn">➕ Новый студент</a>
                <a href="stats.php" class="btn btn-success">📈 Подробная статистика</a>
            </div>
        </div>
    </div>
</body>
</html>
