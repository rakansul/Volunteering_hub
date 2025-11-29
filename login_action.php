<?php
session_start();    
require 'includes/db.php';
require 'includes/functions.php';
require 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash_set('error','بيانات غير صحيحة');
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
$stmt->execute([$email]);
$u = $stmt->fetch();

if (!$u || !password_verify($pass, $u['password_hash'])) {
    flash_set('error','بريد او كلمة المرور خاطئة');
    header('Location: login.php');
    exit;
}

$_SESSION['user'] = [
    'id' => (int)$u['id'],
    'first_name' => $u['first_name'],
    'last_name' => $u['last_name'],
    'email' => $u['email'],
    'role' => $u['role']
];

session_regenerate_id(true);
flash_set('success','تم تسجيل الدخول');

// Redirect based on role
if ($u['role'] === 'admin') {
    header('Location: admin/admin_dashboard.php');
    exit;
} elseif ($u['role'] === 'organization') {
    header('Location: admin/manage_opportunities.php');
    exit;
} else {
    header('Location: profile.php');
    exit;
}
