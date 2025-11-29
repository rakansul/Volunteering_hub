<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}   
function is_logged_in(): bool {
  return !empty($_SESSION['user']);
}
function current_user(): ?array {
  return $_SESSION['user'] ?? null;
}
function current_user_id(): ?int {
  return isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
}
function require_login(): void {
  if (!is_logged_in()) {
    header('Location: login.php');
    exit;
  }
}
function require_admin(): void {
  if (!is_logged_in() || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    exit('غير مصرح');
  }
}
function require_organization(): void {
  if (!is_logged_in() || ($_SESSION['user']['role'] ?? '') !== 'organization') {
    header('HTTP/1.1 403 Forbidden');
    exit('غير مصرح');
  }
}
