<?php
require_once 'includes/db.php';
include 'includes/header.php';

$q = trim($_GET['q'] ?? '');
$cat = trim($_GET['category'] ?? '');

$sql = "SELECT o.*, c.name_ar AS category, org.org_name FROM opportunities o
        LEFT JOIN categories c ON o.category_id = c.id
        LEFT JOIN organizations org ON o.org_id = org.id WHERE 1=1";
$params=[];

if ($q!=='') {
  $sql .= " AND (o.title LIKE ? OR o.description LIKE ?)";
  $params[] = "%$q%"; $params[] = "%$q%";
}
if ($cat!=='') {
  $sql .= " AND c.slug = ?"; $params[] = $cat;
}
$sql .= " ORDER BY o.event_date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$opps = $stmt->fetchAll();
$cats = $pdo->query("SELECT * FROM categories ORDER BY name_ar")->fetchAll();
?>

<section class="page-header">
    <div class="container">
        <h2>جميع فرص التطوع</h2>
        <p>اكتشف طرقاً هادفة لرد الجميل لمجتمعك</p>
    </div>
</section>

<section class="search-filters"><div class="container">  
    <form method="GET" class="filter-container" onsubmit="return false;">
        <div class="search-bar">
            <input id="search-input" name="q" placeholder="ابحث عن فرص..." value="<?= e($q) ?>">
            <button type="submit" class="btn btn-search" onclick="location.href='opportunities.php?q='+encodeURIComponent(document.getElementById('search-input').value)">بحث</button>    
        </div>    
        <div class="filter-options">      
            <select id="category-filter" name="category" onchange="location.href='opportunities.php?category='+this.value">        
                <option value="">كل الفئات</option>        
                <?php foreach ($cats as $c): ?>          
                <option value="<?= e($c['slug']) ?>" <?= ($c['slug']==$cat)?'selected':'' ?>><?= e($c['name_ar']) ?></option>        
                <?php endforeach; ?>      
            </select>    
        </div>    
    </form>    
    </div>
</section>

<section class="opportunities-list"><div class="container"><div class="opportunities-grid">  
    <?php if (!$opps): ?><p>لا توجد فرص.</p><?php else:foreach ($opps as $opp): ?>    
    <article class="opportunity-card detailed">      
        <div class="card-content">        
            <h3><?= e($opp['title']) ?></h3>        
            <div class="card-meta">
                <span class="date"><?= e($opp['event_date']) ?></span>
                <span class="location"><?= e($opp['location']) ?></span>
                <span class="category"><?= e($opp['category']) ?></span>
            </div>        
            <p><?= e(mb_strimwidth($opp['description'], 0, 200, '...')) ?></p>        
            <a href="opportunity_detail.php?id=<?= (int)$opp['id'] ?>" class="btn btn-secondary">عرض التفاصيل</a>      
        </div>
    </article>  
    <?php endforeach; endif; ?>
    </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
