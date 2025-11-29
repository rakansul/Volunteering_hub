
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);
}

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>مركز المتطوعين</title>
        <link rel="stylesheet" href="/style.css">
    </head>
<body>
<header>  
    <nav class="navbar">    
        <div class="nav-container">        
            <div class="logo">          
                <a href="index.php"><h1>مركز المتطوعين</h1></a>        
            </div>    
            <button class="hamburger" aria-label="تبديل القائمة" aria-expanded="false">                 
                <span></span>          
                <span></span>         
                <span></span>                   
            </button>      
            <ul class="nav-menu">       
                <li><a href="/index.php" class="<?= (basename($_SERVER['SCRIPT_NAME'])==='index.php')? 'active':'' ?>">الرئيسية</a></li>       
                <li><a href="/opportunities.php" class="<?= (basename($_SERVER['SCRIPT_NAME'])==='opportunities.php')? 'active':'' ?>">الفرص</a></li>       
                <li><a href="/about.php">من نحن</a></li>        
                <li><a href="/contact.php">تواصل معنا</a></li>        
                <?php if (!is_logged_in()): ?>          
                <li><a href="/login.php">تسجيل الدخول</a></li>          
                <li><a href="/register.php">تسجيل</a></li>        
                <?php else: ?>          
                <li><a href="/profile.php">حسابي</a></li>          
                <?php if ($_SESSION['user']['role'] === 'organization'): ?>           
                <li><a href="/admin/manage_opportunities.php">لوحة المنظمة</a></li>          
                <?php endif; ?>          
                <?php if ($_SESSION['user']['role'] === 'admin'): ?>          
                <li><a href="/admin/admin_dashboard.php">لوحة المشرف</a></li>         
                <?php endif; ?>         
                <li><a href="/logout.php">تسجيل خروج</a></li>        
                <?php endif; ?>      
            </ul>    
        </div>  
    </nav>
    </header>
    <main>
        <div class="container">
            <?php
            $f = flash_get();
            if ($f): ?>
            <div class="registration-message <?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
            <?php endif; ?>
