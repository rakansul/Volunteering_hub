<?php
// admin/edit_opportunity.php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_admin();
include_once '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { 
    echo '<p>معرّف غير صالح</p>'; 
    include_once '../includes/footer.php'; 
    exit; 
}

$stmt = $pdo->prepare("SELECT * FROM opportunities WHERE id=? LIMIT 1");
$stmt->execute([$id]); $opp = $stmt->fetch();
if (!$opp) { 
    echo '<p>الفرصة غير موجودة</p>'; 
    include_once '../includes/footer.php'; 
    exit; 
}

$cats = $pdo->query("SELECT * FROM categories ORDER BY name_ar")->fetchAll();
$orgs = $pdo->query("SELECT id, org_name FROM organizations ORDER BY org_name")->fetchAll();
?>

<section class="page-header">
    <div class="container">
        <h2>تعديل الفرصة</h2>
    </div>
</section>

<section class="admin-section">
    <div class="container">
        <form action="update_opportunity_action.php" method="post">
            <input type="hidden" name="id" value="<?= (int)$opp['id'] ?>">
            <div class="form-group">
                <label>عنوان الفرصة</label>
                <input name="title" value="<?= e($opp['title']) ?>" required></div>
            <div class="form-group">
                <label>الفئة</label>
                <select name="category_id">
                    <option value="">-- لا شيء --</option>
                    <?php foreach ($cats as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= ($c['id']==$opp['category_id']) ? 'selected' : '' ?>><?= e($c['name_ar']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>المنظمة</label>
                <select name="org_id" required>
                    <option value="">-- اختر منظمة --</option>
                    <?php foreach ($orgs as $o): ?>
                    <option value="<?= (int)$o['id'] ?>" <?= ($o['id']==$opp['org_id']) ? 'selected' : '' ?>><?= e($o['org_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>الموقع (مختصر)</label><input name="location" value="<?= e($opp['location']) ?>" required>
            </div>
            <div class="form-group">
                <label>تفاصيل الموقع</label><input name="location_detail" value="<?= e($opp['location_detail']) ?>"></div>
            <div class="form-group">
                <label>تاريخ الحدث</label><input type="date" name="event_date" value="<?= e($opp['event_date']) ?>" required></div>
            <div class="form-group">
                <label>وقت البداية</label><input type="time" name="start_time" value="<?= e($opp['start_time']) ?>"></div>
            <div class="form-group">
                <label>وقت النهاية</label><input type="time" name="end_time" value="<?= e($opp['end_time']) ?>"></div>
            <div class="form-group">
                <label>عدد المقاعد</label><input type="number" name="seats" min="0" value="<?= e($opp['seats']) ?>"></div>
            <div class="form-group">
                <label>المتطلبات</label><textarea name="requirements"><?= e($opp['requirements']) ?></textarea></div>
            <div class="form-group">
                <label>الوصف الكامل</label><textarea name="description" required><?= e($opp['description']) ?></textarea></div>            
            <button class="btn btn-primary" type="submit">حفظ التغييرات</button>
            <a class="btn" href="manage_opportunities.php">إلغاء</a>
        </form>
    </div>
</section>

<?php include_once '../includes/footer.php'; ?>
