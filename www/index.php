<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система регистрации студентов - Университет</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Университетская система</h1>
            <p>Регистрация и управление студентами</p>
        </div>
        
        <div class="content">
            <div class="card">
                <h2>📊 Статус системы</h2>
                <p><strong>Система регистрации студентов работает корректно!</strong></p>
                <p>Версия PHP: <?php echo phpversion(); ?></p>
                
                <?php
                try {
                    $pdo = new PDO('mysql:host=db;dbname=student_db', 'student_user', 'student_pass');
                    $pdo->exec("SET NAMES 'utf8mb4'");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    echo '<div class="status success">✅ Подключение к БД успешно</div>';
                    
                    // Создаем таблицу если её нет
                    $sql = "CREATE TABLE IF NOT EXISTS students (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        full_name VARCHAR(100) NOT NULL,
                        email VARCHAR(100) NOT NULL UNIQUE,
                        phone VARCHAR(20) NOT NULL,
                        faculty VARCHAR(100) NOT NULL,
                        course VARCHAR(50) NOT NULL,
                        group_name VARCHAR(50) NOT NULL,
                        birth_date DATE NOT NULL,
                        address TEXT,
                        registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        status ENUM('active', 'inactive') DEFAULT 'active'
                    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
                    
                    $pdo->exec($sql);
                    echo '<div class="status success">✅ Таблица студентов создана</div>';
                    
                    // Получаем статистику
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM students");
                    $count = $stmt->fetch()['count'];
                    echo "<p><strong>Всего студентов в системе:</strong> $count</p>";
                    
                    $stmt = $pdo->query("SELECT COUNT(*) as active FROM students WHERE status = 'active'");
                    $active = $stmt->fetch()['active'];
                    echo "<p><strong>Активных студентов:</strong> $active</p>";
                    
                } catch (PDOException $e) {
                    echo '<div class="status error">❌ Ошибка БД: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">🎯</div>
                    <div class="stat-label">Быстрая регистрация</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">📚</div>
                    <div class="stat-label">Управление данными</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">👨‍🎓</div>
                    <div class="stat-label">Поддержка студентов</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">📊</div>
                    <div class="stat-label">Подробная статистика</div>
                </div>
            </div>
            
            <div class="nav-links">
                <a href="register.html" class="btn">➕ Зарегистрировать студента</a>
                <a href="students.php" class="btn btn-secondary">👥 Список студентов</a>
                <a href="stats.php" class="btn btn-success">📈 Статистика</a>
            </div>
        </div>
    </div>
</body>
</html>
