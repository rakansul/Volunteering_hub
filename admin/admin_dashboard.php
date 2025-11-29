<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_admin();
include_once '../includes/header.php';
?>

<section class="page-header">
    <div class="container">    
        <h2>لوحة تحكم المشرف</h2>
        <p>مرحباً بك في لوحة إدارة المنصة. يمكنك إدارة جميع أقسام الموقع من هنا.</p>
    </div>
</section>

<section class="admin-section">
    <div class="container">
        <div class="admin-grid">

            <div class="admin-card">
                <h3>إدارة الفرص</h3>
                <p>عرض، إضافة، تعديل، أو حذف الفرص التطوعية.</p>

                <a href="manage_opportunities.php" class="btn btn-primary btn-block">
                    إدارة جميع الفرص
                </a>

                <a href="add_opportunity.php" class="btn btn-secondary btn-block" style="margin-top:8px;">
                    إضافة فرصة جديدة
                </a>
            </div>
                      
        </div>
    </div>
</section>

<?php include_once '../includes/footer.php'; ?>
