<?php include 'includes/header.php';?>
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-card">  
            <h2>إنشاء حساب جديد</h2>  
            <form action="register_action.php" method="post">    
                <div class="form-group">
                    <label>الاسم الأول</label>
                    <input name="first_name" required>
                </div>    
                <div class="form-group">
                    <label>الاسم الأخير</label>
                    <input name="last_name" required>
                </div>    
                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input name="email" type="email" required>
                </div>    
                <div class="form-group">
                    <label>كلمة المرور</label>
                    <input name="password" type="password" required>
                </div>    
                <div class="form-group">
                    <label>تأكيد كلمة المرور</label>
                    <input name="confirm_password" type="password" required>
                </div>    
                <button class="btn btn-primary btn-block" type="submit">تسجيل</button>  
            </form>
        </div>
    </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
