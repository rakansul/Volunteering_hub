<?php
require_once 'includes/auth.php';       // starts session + has require_login()
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: opportunities.php');
    exit;
}

require_login();

// Validate opportunity ID
$opp_id = (int)($_POST['opportunity_id'] ?? 0);
if ($opp_id <= 0) {
    flash_set('error', 'فرصة غير صحيحة');
    header('Location: opportunities.php');
    exit;
}

$uid = current_user_id();

// Check if already registered
$check = $pdo->prepare("SELECT id FROM registrations WHERE user_id = ? AND opp_id = ? LIMIT 1");
$check->execute([$uid, $opp_id]);

if ($check->fetch()) {
    flash_set('error', 'أنت مسجل بالفعل في هذه الفرصة');
    header('Location: profile.php');
    exit;
}

// Insert registration
$insert = $pdo->prepare("INSERT INTO registrations (user_id, opp_id) VALUES (?, ?)");
$insert->execute([$uid, $opp_id]);

flash_set('success', 'تم التسجيل بنجاح');
header('Location: profile.php');
exit;
