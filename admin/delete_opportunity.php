<?php
// admin/delete_opportunity.php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_opportunities.php'); 
    exit; 
}

$id = (int)($_POST['id'] ?? 0);
if ($id<=0) {
    flash_set('error','معرّف غير صالح');
    header('Location: manage_opportunities.php'); 
    exit;
}

// delete opportunity (registrations cascade by FK)
$stmt = $pdo->prepare('DELETE FROM opportunities WHERE id = ?');
$stmt->execute([$id]);

flash_set('success','تم حذف الفرصة');
header('Location: manage_opportunities.php');
exit;
