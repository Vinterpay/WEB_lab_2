<?php
session_start();
require_once 'UserInfo.php';

// Сохраняем данные формы
$_SESSION['form_data'] = $_POST;

// Информация о пользователе
$_SESSION['user_info'] = UserInfo::getInfo();

// Кука: последняя отправка
setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");

// Редирект на главную
header("Location: /");
exit;