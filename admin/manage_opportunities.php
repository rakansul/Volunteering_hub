<?php 
// admin/manage_opportunities.php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';
include_once '../includes/header.php';

// fetch opportunities with category + organization
$stmt = $pdo->query("
  SELECT o.id, o.title, o.event_date, o.location, 
         c.name_ar AS category, 
         org.org_name
  FROM opportunities o
  LEFT JOIN categories c ON o.category_id = c.id
  LEFT JOIN organizations org ON o.org_id = org.id
  ORDER BY o.event_date DESC
");
$opps = $stmt->fetchAll();
?>

<section class="page-header">
    <div class="container">
        <h2>إدارة الفرص</h2>
    </div>
</section>

<section class="admin-section">
    <div class="container">

        <!-- Flash messages -->
        <?php $flash = flash_get(); if ($flash): ?>
            <div class="registration-message <?= e($flash['type']) ?>">
                <?= e($flash['msg']) ?>
            </div>
        <?php endif; ?>

        <p>
            <a href="add_opportunity.php" class="btn btn-primary">
                إضافة فرصة جديدة
            </a>
        </p>

        <table class="table" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>التاريخ</th>
                    <th>الموقع</th>
                    <th>الفئة</th>
                    <th>المنظمة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($opps as $o): ?>
                <tr>
                    <td><?= e($o['title']) ?></td>
                    <td><?= e($o['event_date']) ?></td>
                    <td><?= e($o['location']) ?></td>
                    <td><?= e($o['category'] ?? '-') ?></td>
                    <td><?= e($o['org_name'] ?? '-') ?></td>

                    <td>
                        <a class="btn btn-secondary" 
                           href="edit_opportunity.php?id=<?= (int)$o['id'] ?>">
                           تعديل
                        </a>

                        <form style="display:inline;" 
                              method="post" 
                              action="delete_opportunity.php"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الفرصة؟');">
                            
                            <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
                            <button type="submit" class="btn btn-danger">
                                حذف
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($opps)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;">
                        لا توجد فرص حالياً.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>
</section>

<?php include_once '../includes/footer.php'; ?>
