<?php 
session_start();
require 'includes/db.php'; 
require 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: register.php'); 
    exit; 
}

$first = trim($_POST['first_name'] ?? '');
$last = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

$errors = [];

// validation
if ($first === '') $errors[] = 'الاسم الأول مطلوب';
if ($last === '')  $errors[] = 'الاسم الأخير مطلوب';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = 'بريد إلكتروني غير صالح';
if (strlen($pass) < 8)
    $errors[] = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل';
if ($pass !== $confirm)
    $errors[] = 'كلمتا المرور غير متطابقتين';

if ($errors) { 
    flash_set('error', implode(' - ', $errors)); 
    header('Location: register.php'); 
    exit; 
}

// email uniqueness
$stmt = $pdo->prepare('SELECT id FROM users WHERE email=? LIMIT 1'); 
$stmt->execute([$email]);
if ($stmt->fetch()) { 
    flash_set('error','البريد مستخدم'); 
    header('Location: register.php'); 
    exit; 
}

// insert with default role = user
$hash = password_hash($pass, PASSWORD_DEFAULT);
$ins = $pdo->prepare(
    'INSERT INTO users (first_name, last_name, email, password_hash, role)
     VALUES (?, ?, ?, ?, ?)'
);
$ins->execute([$first, $last, $email, $hash, 'user']);

// auto-login
$userId = $pdo->lastInsertId();
$_SESSION['user'] = [
    'id' => (int)$userId,
    'first_name' => $first,
    'last_name' => $last,
    'email' => $email,
    'role' => 'user'
];

session_regenerate_id(true);
flash_set('success','تم التسجيل بنجاح');
header('Location: profile.php');
exit;
