<?php
require_once 'includes/header.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Ensure user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

// Get user data from session
$user = $_SESSION['user'] ?? null;
?>


<div class="profile-page">
    <section class="profile-section">
        <div class="container">
            <?php
            // Flash messages
            $flash = flash_get();
            if ($flash): ?>
                <div class="registration-message <?= e($flash['type']) ?>">
                    <?= e($flash['msg']) ?>
                </div>
            <?php endif; ?>

            <!-- Profile Card -->
            <div class="profile-card">
                <h3>معلومات حسابك</h3>
                <div class="profile-info">
                    <div class="info-item">
                        <strong>الاسم الأول:</strong>
                        <span><?= e($user['first_name']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>الاسم الأخير:</strong>
                        <span><?= e($user['last_name']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>البريد الإلكتروني:</strong>
                        <span><?= e($user['email']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>دور المستخدم:</strong>
                        <span><?= e($user['role']) ?></span>
                    </div>
                    
                </div>
                <a href="edit_profile.php" class="btn btn-primary">تعديل الحساب</a>
            </div>

            <!-- Registered Opportunities -->
            <div class="registered-opportunities">
                <h3>الفرص المسجلة</h3>
                <?php
                // Example: fetch user opportunities from DB
                $opportunities = get_user_opportunities($user['id']); // You need to implement this function
                if (!empty($opportunities)): ?>
                    <div class="opportunities-list-profile">
                        <?php foreach ($opportunities as $opp): ?>
                            <div class="opportunity-item">
                                <div class="item-content">
                                    <h4><?= e($opp['title']) ?></h4>
                                    <div class="item-meta">
                                        <span>تاريخ التسجيل: <?= e($opp['registered_at']) ?></span>
                                        <span>الفئة: <?= e($opp['category']) ?></span>
                                    </div>
                                </div>
                                <a href="opportunity_detail.php?id=<?= e($opp['id']) ?>" class="btn btn-secondary">عرض التفاصيل</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-results-message">لم يتم تسجيل أي فرص بعد.</p>
                <?php endif; ?>
            </div>
        </div> <!-- /.container -->
    </section>
</div> <!-- /.profile-page -->

<?php require_once 'includes/footer.php'; ?>
