<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - <?php echo APP_NAME; ?></title>
    <!-- تضمين مكتبة Bootstrap لتنسيق سريع وجذاب -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; border: none; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card-header { background-color: #007bff; color: white; border-radius: 10px 10px 0 0 !important; text-align: center; font-weight: bold; }
    </style>
</head>
<body>

<div class="card login-card">
    <div class="card-header py-3">
        <h3><?php echo APP_NAME; ?></h3>
        <span class="small">بوابة تسجيل الدخول للنظام الطبية</span>
    </div>
    <div class="card-body p-4">
        
        <!-- عرض رسائل الأخطاء إن وجدت بشكل منسق -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center small py-2"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="index.php?page=auth&action=login" method="POST">
            
            <!-- زرع توكن حماية الـ CSRF السري داخل حقل مخفي في الفورم -->
            <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="example@clinic.local" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="******" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block mt-4">تسجيل الدخول</button>
        </form>

    </div>
</div>

</body>
</html>
