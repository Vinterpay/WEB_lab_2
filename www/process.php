<?php
session_start();

$name = trim($_POST['name'] ?? '');
$age = $_POST['age'] ?? '';
$faculty = $_POST['faculty'] ?? '';
$rules = isset($_POST['rules']);
$study_form = $_POST['study_form'] ?? '';

$errors = [];
if (empty($name)) {
    $errors[] = "Имя не может быть пустым";
}
if (!is_numeric($age) || $age < 16 || $age > 100) {
    $errors[] = "Возраст должен быть от 16 до 100";
}
if (empty($faculty)) {
    $errors[] = "Выберите факультет";
}
if (empty($study_form)) {
    $errors[] = "Выберите форму обучения";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

$_SESSION['name'] = $name;
$_SESSION['age'] = (int)$age;
$_SESSION['faculty'] = $faculty;
$_SESSION['rules'] = $rules;
$_SESSION['study_form'] = $study_form;

// Сохраняем в файл: имя;возраст;факультет;правила;форма
$line = implode(";", [$name, $age, $faculty, $rules ? 'Да' : 'Нет', $study_form]) . "\n";
file_put_contents(__DIR__ . '/data.txt', $line, FILE_APPEND | LOCK_EX);

header("Location: index.php");
exit();
?>
