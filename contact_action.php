<?php
require 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$errors = [];

if (!$name)
    $errors[] = 'الاسم مطلوب';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = 'البريد الإلكتروني غير صالح';
if (!$subject)
    $errors[] = 'الموضوع مطلوب';
if (!$message)
    $errors[] = 'الرسالة مطلوبة';
if ($errors) {
    flash_set('error', implode(' - ', $errors));
    header('Location: contact.php');
    exit;
}

$to = "your-email@example.com"; // placeholder email add your email later
$email_subject = "رسالة جديدة من الموقع: $subject";

$email_body = 
"الاسم: $name\n".
"البريد: $email\n".
"الموضوع: $subject\n\n".
"الرسالة:\n$message";

$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$mail_sent = mail($to, $email_subject, $email_body, $headers);

if ($mail_sent) {
    flash_set('success', 'تم إرسال رسالتك بنجاح.');
} else {
    flash_set('error', 'حدث خطأ أثناء إرسال الرسالة. الرجاء المحاولة مرة أخرى.');
}

header('Location: contact.php');
exit;
