<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة طبيب جديد - ClinicDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; padding: 20px; }
        .main-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: white; padding: 30px; max-width: 800px; margin: 0 auto; }
    </style>
</head>
<body>

<div class="container">
    <div class="main-card mt-4">
        <div class="border-bottom pb-3 mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-primary mb-1">➕ تسجيل حساب طبيب جديد</h3>
                <p class="text-muted small mb-0">يرجى إدخال البيانات الشخصية والمهنية للطبيب لإنشاء حسابه تلقائياً.</p>
            </div>
            <a href="index.php?page=doctors&action=index" class="btn btn-outline-secondary btn-sm">🔙 إلغاء والعودة</a>
        </div>

        <!-- رسائل الخطأ والنجاح -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success text-center">
                <?php echo $success; ?>
                <br><small><a href="index.php?page=doctors&action=index" class="alert-link">اضغط هنا للعودة لقائمة الأطباء</a></small>
            </div>
        <?php endif; ?>

        <form action="index.php?page=doctors&action=create" method="POST">
            <div class="row">
                <!-- اسم الطبيب -->
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label font-weight-bold">اسم الطبيب بالكامل:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="مثال: د. أحمد كمال" required>
                </div>

                <!-- البريد الإلكتروني -->
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني (لتسجيل الدخول):</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="doctor@clinic.local" required>
                </div>
            </div>

            <div class="row">
                <!-- كلمة المرور -->
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">كلمة المرور المؤقتة:</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="******" required>
                </div>

                <!-- رقم الهاتف -->
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">رقم الهاتف أو الجوال:</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="059xxxxxxx">
                </div>
            </div>

            <div class="row">
                <!-- اختيار التخصص -->
                <div class="col-md-6 mb-3">
                    <label for="specialization_id" class="form-label">التخصص الطبي المعتمد:</label>
                    <select name="specialization_id" id="specialization_id" class="form-select" required>
                        <option value="">-- اختر التخصص --</option>
                        <?php foreach ($specializations as $spec): ?>
                            <option value="<?php echo $spec['id']; ?>"><?php echo htmlspecialchars($spec['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- سعر الكشفية -->
                <div class="col-md-6 mb-4">
                    <label for="consultation_fee" class="form-label">رسوم كشف العيادة ($):</label>
                    <input type="number" step="0.01" name="consultation_fee" id="consultation_fee" class="form-control" value="0.00" required>
                </div>
            </div>

            <!-- زر التثبيت -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg font-weight-bold shadow-sm">إنشاء الحساب وتفعيل الطبيب فوراً 🚀</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
