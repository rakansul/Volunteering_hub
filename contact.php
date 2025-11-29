<?php
include 'includes/header.php'; 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تواصل معنا - مركز المتطوعين</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <section class="page-header">
            <div class="container">
                <h2>تواصل معنا</h2>
                <p>نحن هنا للإجابة على استفساراتك ومساعدتك</p>
            </div>
        </section>

        <section class="contact-section">
            <div class="container">
                <div class="contact-content">
                    <div class="contact-card">
                        <h3>أرسل لنا رسالة</h3>
                        <form class="contact-form" method="POST" action="contact_action.php" id="contact-form">
                            
                            <div class="form-group">
                                <label for="contact-name">الاسم الكامل</label>
                                <input type="text" id="contact-name" name="name" required placeholder="أدخل اسمك الكامل">
                            </div>
                            <div class="form-group">
                                <label for="contact-email">البريد الإلكتروني</label>
                                <input type="email" id="contact-email" name="email" required placeholder="أدخل بريدك الإلكتروني">
                            </div>
                            <div class="form-group">
                                <label for="contact-subject">الموضوع</label>
                                <input type="text" id="contact-subject" name="subject" required placeholder="موضوع الرسالة">
                            </div>
                            <div class="form-group">
                                <label for="contact-message">الرسالة</label>
                                <textarea id="contact-message" name="message" rows="6" required placeholder="اكتب رسالتك هنا..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">إرسال الرسالة</button>
                            <div id="contact-message-result" class="registration-message"></div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="script.js" defer></script>
</body>
</html>
