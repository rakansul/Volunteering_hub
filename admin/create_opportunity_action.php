<?php
// admin/create_opportunity_action.php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add_opportunity.php');
    exit;
}

$title = trim($_POST['title'] ?? '');
$category_id = $_POST['category_id'] ?: null;
$org_id = (int)($_POST['org_id'] ?? 0);
$location = trim($_POST['location'] ?? '');
$location_detail = trim($_POST['location_detail'] ?? '');
$event_date = $_POST['event_date'] ?? null;
$start_time = $_POST['start_time'] ?: null;
$end_time = $_POST['end_time'] ?: null;
$seats = $_POST['seats'] !== '' ? (int)$_POST['seats'] : null;
$requirements = trim($_POST['requirements'] ?? '');
$description = trim($_POST['description'] ?? '');

$errors = [];
if ($title === '') 
    $errors[] = 'العنوان مطلوب';
if (!$org_id) 
    $errors[] = 'المنظمة مطلوبة';
if (!$event_date)
    $errors[] = 'تاريخ الحدث مطلوب';
if ($description === '') 
    $errors[] = 'الوصف مطلوب';

if (!empty($errors)) {  
    flash_set('error', implode(' - ', $errors));  
    header('Location: add_opportunity.php'); exit;
}

$sql = "INSERT INTO opportunities (org_id, category_id, title, description, location, location_detail, event_date, start_time, end_time, seats, requirements)
VALUES (:org_id, :category_id, :title, :description, :location, :location_detail, :event_date, :start_time, :end_time, :seats, :requirements)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':org_id'=>$org_id,
  ':category_id'=>$category_id,
  ':title'=>$title,
  ':description'=>$description,
  ':location'=>$location,
  ':location_detail'=>$location_detail,
  ':event_date'=>$event_date,
  ':start_time'=>$start_time,
  ':end_time'=>$end_time,
  ':seats'=>$seats,
  ':requirements'=>$requirements
]);

flash_set('success','تم إنشاء الفرصة بنجاح');
header('Location: manage_opportunities.php'); exit;
