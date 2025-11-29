<?php
require_once __DIR__ . '/db.php';

function e($s) {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); 
}

function flash_set($type, $msg) {
    $_SESSION['flash'] = ['type'=>$type,'msg'=>$msg];
}

function flash_get() {
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function get_user_opportunities($user_id) {
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT 
            o.id,
            o.title,
            c.name_ar AS category,
            r.registered_at
        FROM registrations r
        JOIN opportunities o ON r.opp_id = o.id
        JOIN categories c ON o.category_id = c.id
        WHERE r.user_id = ?
        ORDER BY r.registered_at DESC
    ");

    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
	


