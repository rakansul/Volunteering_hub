<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$userId = $user['id'];

$first = trim($_POST['first_name'] ?? '');
$last = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

$errors = [];

// Basic validation
if ($first === '') $errors[] = "الاسم الأول مطلوب";
if ($last === '') $errors[] = "الاسم الأخير مطلوب";
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = "البريد الإلكتروني غير صالح";

// password change (optional)
$updatePassword = false;

if ($password !== '') {
    if (strlen($password) < 8)
        $errors[] = "كلمة المرور يجب أن تكون 8 أحرف على الأقل";

    if ($password !== $confirm)
        $errors[] = "كلمتا المرور غير متطابقتين";

    $updatePassword = true;
}

// Check email uniqueness (except current email)
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
$stmt->execute([$email, $userId]);

if ($stmt->fetch()) {
    $errors[] = "البريد الإلكتروني مستخدم من قبل مستخدم آخر";
}

if ($errors) {
    flash_set("error", implode(" - ", $errors));
    header("Location: edit_profile.php");
    exit;
}

// Update database
if ($updatePassword) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET first_name=?, last_name=?, email=?, password_hash=? WHERE id=?";
    $params = [$first, $last, $email, $hash, $userId];
} else {
    $sql = "UPDATE users SET first_name=?, last_name=?, email=? WHERE id=?";
    $params = [$first, $last, $email, $userId];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Update session
$_SESSION['user']['first_name'] = $first;
$_SESSION['user']['last_name'] = $last;
$_SESSION['user']['email'] = $email;

flash_set("success", "تم تحديث الحساب بنجاح");
header("Location: profile.php");
exit;
