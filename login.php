
<?php include 'includes/header.php';?>
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-card">
            <h2>تسجيل الدخول إلى حسابك</h2>  
            <form action="login_action.php" method="post">  
                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input name="email" type="email" required>
                </div>    
                <div class="form-group">
                    <label>كلمة المرور</label>
                    <input name="password" type="password" required>
                </div>    
                <button class="btn btn-primary btn-block" type="submit">تسجيل الدخول</button>  
            </form>
        </div>
    </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
