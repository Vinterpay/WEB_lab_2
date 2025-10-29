<?php
class Student {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function createTable() {
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
        
        return $this->pdo->exec($sql);
    }
    
    public function addStudent($full_name, $email, $phone, $faculty, $course, $group_name, $birth_date, $address) {
        $sql = "INSERT INTO students (full_name, email, phone, faculty, course, group_name, birth_date, address) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$full_name, $email, $phone, $faculty, $course, $group_name, $birth_date, $address]);
    }
    
    public function getAllStudents() {
        $sql = "SELECT * FROM students ORDER BY registration_date DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getStudentById($id) {
        $sql = "SELECT * FROM students WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateStudentStatus($id, $status) {
        $sql = "UPDATE students SET status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $id]);
    }
    
    public function deleteStudent($id) {
        $sql = "DELETE FROM students WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function getStats() {
        $stats = [];
        
        // Общее количество студентов
        $sql = "SELECT COUNT(*) as total FROM students";
        $stmt = $this->pdo->query($sql);
        $stats['total'] = $stmt->fetch()['total'];
        
        // Активные студенты
        $sql = "SELECT COUNT(*) as active FROM students WHERE status = 'active'";
        $stmt = $this->pdo->query($sql);
        $stats['active'] = $stmt->fetch()['active'];
        
        // Студенты по факультетам
        $sql = "SELECT faculty, COUNT(*) as count FROM students GROUP BY faculty";
        $stmt = $this->pdo->query($sql);
        $stats['by_faculty'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }
}
?>
