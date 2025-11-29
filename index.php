<?php
require_once 'includes/db.php';
include 'includes/header.php';

// fetch featured opportunities (latest 6)
$stmt = $pdo->prepare("SELECT o.*, c.name_ar AS category, org.org_name FROM opportunities o
  LEFT JOIN categories c ON o.category_id = c.id
  LEFT JOIN organizations org ON o.org_id = org.id
  ORDER BY o.created_at DESC LIMIT 6");
$stmt->execute(); $opps = $stmt->fetchAll();
?>

<section class="hero">
  <div class="hero-content">
    <h2>اصنع فرقاً في مجتمعك</h2>
    <p>انضم لآلاف المتطوعين الذين يحدثون تأثيراً إيجابياً. ابحث عن فرص تتناسب مع اهتماماتك وجدولك الزمني.</p>
    <a href="opportunities.php" class="btn btn-primary">تصفح الفرص</a>
  </div>
</section>

<section class="featured-opportunities">
    <div class="container">
        <h2>فرص مميزة</h2>
        <div class="opportunities-grid">
            <?php foreach ($opps as $opp): ?>
            <article class="opportunity-card">
                <div class="card-content">
                    <h3><?= e($opp['title']) ?></h3>
                    <div class="card-meta">
                        <span class="date"><?= e(date('d F Y', strtotime($opp['event_date']))) ?></span>
                        <span class="location"><?= e($opp['location']) ?></span>
                    </div>
                    <p><?= e(mb_strimwidth($opp['description'], 0, 120, '...')) ?></p>
                    <a href="opportunity_detail.php?id=<?= (int)$opp['id'] ?>" class="btn btn-secondary">اعرف المزيد</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
