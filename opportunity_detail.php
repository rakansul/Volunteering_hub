<?php 
require_once 'includes/db.php';
include 'includes/header.php';

// Get opportunity ID
$id = (int)($_GET['id'] ?? 0);

// Load opportunity details
$stmt = $pdo->prepare("
    SELECT 
        o.*, 
        c.name_ar AS category, 
        org.org_name 
    FROM opportunities o
    LEFT JOIN categories c ON o.category_id = c.id
    LEFT JOIN organizations org ON o.org_id = org.id
    WHERE o.id = ? 
    LIMIT 1
");

$stmt->execute([$id]);
$opp = $stmt->fetch();

if (!$opp) {
    http_response_code(404);
    echo "<p>الفرصة غير موجودة</p>";
    include 'includes/footer.php';
    exit;
}

// Check if user is registered
$is_registered = false;
$registration_id = null;

if (is_logged_in()) {
    $uid = current_user_id();

    $check = $pdo->prepare("SELECT id FROM registrations WHERE user_id=? AND opp_id=? LIMIT 1");
    $check->execute([$uid, $opp['id']]);

    if ($row = $check->fetch()) {
        $is_registered = true;
        $registration_id = $row['id'];
    }
}
?>

<section class="opportunity-detail-section">  
    <div class="container">    

        <a href="opportunities.php" class="back-link">← العودة إلى الفرص</a>

        <div class="opportunity-detail-card">

            <!-- Header -->
            <div class="detail-header">
                <h1><?= e($opp['title']) ?></h1>
                <div class="detail-meta-top">
                    <span class="meta-item"><?= e($opp['event_date']) ?></span>
                    <span class="meta-item"><?= e($opp['location']) ?></span>
                    <span class="meta-item category-badge"><?= e($opp['category']) ?></span>
                </div>
            </div>

            <!-- Main Content -->
            <div class="detail-content">

                <!-- Description -->
                <div class="detail-section">
                    <h2>الوصف</h2>
                    <p class="detail-text"><?= nl2br(e($opp['description'])) ?></p>
                </div>

                <!-- Info Grid -->
                <div class="detail-info-grid">

                    <div class="detail-info-item">
                        <h3>التاريخ والوقت</h3>
                        <p>
                            <?= e($opp['event_date']) ?>
                            <?= $opp['start_time'] ? ' ' . e($opp['start_time']) : '' ?>
                        </p>
                    </div>

                    <div class="detail-info-item">
                        <h3>الموقع</h3>
                        <p><?= e($opp['location_detail'] ?: $opp['location']) ?></p>
                    </div>

                    <div class="detail-info-item">
                        <h3>المنظم</h3>
                        <p><?= e($opp['org_name'] ?: '-') ?></p>
                    </div>

                    <div class="detail-info-item">
                        <h3>المتطلبات</h3>
                        <p><?= nl2br(e($opp['requirements'] ?: '-')) ?></p>
                    </div>

                </div>

                <!-- Registration Section -->
                <div class="registration-section">

                    <?php if (is_logged_in()): ?>

                        <?php if ($is_registered): ?>
                            
                            <p class="already-registered-text">✔ أنت مسجل في هذه الفرصة.</p>

                            <form action="cancel_registration.php" method="post"
                                  onsubmit="return confirm('هل أنت متأكد من إلغاء التسجيل؟');">
                                <input type="hidden" name="registration_id" value="<?= (int)$registration_id ?>">
                                <button class="btn btn-danger btn-large">إلغاء التسجيل</button>
                            </form>

                        <?php else: ?>

                            <form action="register_for_event.php" method="post">
                                <input type="hidden" name="opportunity_id" value="<?= (int)$opp['id'] ?>">
                                <button id="register-btn" type="submit" class="btn btn-primary btn-large">
                                    التسجيل في هذه الفرصة
                                </button>
                            </form>

                        <?php endif; ?>

                    <?php else: ?>

                        <p>للتسجيل، يرجى <a href="login.php">تسجيل الدخول</a> أولاً.</p>

                    <?php endif; ?>

                </div>

            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
