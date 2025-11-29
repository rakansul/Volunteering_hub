<?php
// admin/add_opportunity.php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_admin();
include_once '../includes/header.php';

// fetch categories and organizations for selects
$cats = $pdo->query("SELECT * FROM categories ORDER BY name_ar")->fetchAll();
$orgs = $pdo->query("SELECT id, org_name FROM organizations ORDER BY org_name")->fetchAll();
?>

<section class="page-header">  
    <div class="container">
        <h2>إضافة فرصة جديدة</h2>
    </div>
</section>

<section class="admin-section">  
    <div class="container">    
        <form action="create_opportunity_action.php" method="post">     
            <div class="form-group">     
                <label>عنوان الفرصة</label>       
                <input name="title" required>    
            </div>      
            <div class="form-group">    
            <label>الفئة</label>     
            <select name="category_id">     
                <option value="">-- اختر فئة --</option>     
                <?php foreach ($cats as $c): ?>
                <option value="<?= (int)$c['id'] ?>"><?= e($c['name_ar']) ?></option>
                <?php endforeach; ?>
            </select>
            </div>

            <div class="form-group">
                <label>المنظمة</label>       
                <select name="org_id" required>    
                    <option value="">-- اختر منظمة --</option>       
                    <?php foreach ($orgs as $o): ?>
                    <option value="<?= (int)$o['id'] ?>"><?= e($o['org_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
      
            <div class="form-group">    
                <label>الموقع (مختصر)</label> 
                <input name="location" required> 
            </div>    
            <div class="form-group">      
                <label>تفاصيل الموقع</label>      
                <input name="location_detail">  
            </div>
      
            <div class="form-group">        
                <label>تاريخ الحدث</label>        
                <input type="date" name="event_date" required>
            </div>
      
            <div class="form-group">
                <label>وقت البداية</label>
                <input type="time" name="start_time">
            </div>      
            
            <div class="form-group">
                <label>وقت النهاية</label>
                <input type="time" name="end_time">
            </div>      
            <div class="form-group">     
                <label>عدد المقاعد (اختياري)</label>        
                <input type="number" name="seats" min="0">    
            </div>
      
            <div class="form-group">
                <label>متطلبات (اختياري)</label>
                <textarea name="requirements"></textarea>
            </div>

            <div class="form-group">
                <label>الوصف الكامل</label>
                <textarea name="description" required></textarea>
            </div>

            <button class="btn btn-primary" type="submit">إنشاء الفرصة</button>
            <a class="btn" href="manage_opportunities.php">إلغاء</a>
        </form>
    </div>
</section>

<?php include_once '../includes/footer.php'; ?>
