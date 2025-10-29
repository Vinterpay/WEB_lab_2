<?php
class Student {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            group_name VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        return $this->pdo->exec($sql);
    }
    public function addStudent($name, $email, $group_name) {
        $sql = "INSERT INTO students (name, email, group_name) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $email, $group_name]);
    }
    public function getAllStudents() {
        $sql = "SELECT * FROM students ORDER BY created_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
