<?php
require_once 'includes/header.php'; 
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: profile.php');
    exit;
}

$reg_id = (int)($_POST['registration_id'] ?? 0);
$uid = current_user_id();

// Verify registration belongs to the current user
$check = $pdo->prepare("SELECT * FROM registrations WHERE id=? AND user_id=? LIMIT 1");
$check->execute([$reg_id, $uid]);

if (!$check->fetch()) {
    flash_set('error', 'طلب غير صالح.');
    header('Location: profile.php');
    exit;
}

// Delete registration
$del = $pdo->prepare("DELETE FROM registrations WHERE id=? AND user_id=?");
$del->execute([$reg_id, $uid]);

flash_set('success', 'تم إلغاء التسجيل بنجاح.');
header('Location: profile.php');
exit;
