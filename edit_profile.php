<?php
require_once 'includes/header.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Ensure logged in
if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<section class="auth-section">
    <div class="auth-container">

        <div class="auth-card">
            <h2>تعديل معلومات الحساب</h2>

            <?php
            $flash = flash_get();
            if ($flash): ?>
                <div class="registration-message <?= e($flash['type']) ?>">
                    <?= e($flash['msg']) ?>
                </div>
            <?php endif; ?>

            <form action="edit_profile_action.php" method="POST">

                <div class="form-group">
                    <label>الاسم الأول</label>
                    <input name="first_name" value="<?= e($user['first_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>الاسم الأخير</label>
                    <input name="last_name" value="<?= e($user['last_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input name="email" type="email" value="<?= e($user['email']) ?>" required>
                </div>

                <hr>

                <div class="form-group">
                    <label>كلمة المرور الجديدة (اختياري)</label>
                    <input name="password" type="password" placeholder="اتركه فارغًا إذا لا تريد تغييره">
                </div>

                <div class="form-group">
                    <label>تأكيد كلمة المرور</label>
                    <input name="confirm_password" type="password" placeholder="أعد كتابة كلمة المرور الجديدة">
                </div>

                <button class="btn btn-primary btn-block" type="submit">حفظ التغييرات</button>
            </form>
        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
