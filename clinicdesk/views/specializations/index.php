<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة التخصصات الطبية - ClinicDesk</title>
    <!-- استدعاء البوتستراب 5 الموجه للعربية RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; padding: 20px; }
        .main-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: white; padding: 25px; }
        .navbar-custom { background-color: #212529; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px; margin-bottom: 20px; }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link { color: white !important; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <!-- شريط الملاحة الخاص بالمدير -->
    <nav class="navbar navbar-expand-lg navbar-custom p-3">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php?page=dashboard">🏥 ClinicDesk</a>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard">الرئيسية</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=doctors&action=index">إدارة الأطباء</a></li>
            <li class="nav-item"><a class="nav-link active" href="index.php?page=specializations">إدارة التخصصات</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=appointments&action=index">سجل المواعيد</a></li>
          </ul>
          <div class="d-flex">
            <a href="index.php?page=auth&action=logout" class="btn btn-danger btn-sm font-weight-bold">تسجيل الخروج</a>
          </div>
        </div>
      </div>
    </nav>

    <!-- عرض التنبيهات ورسائل الخطأ والنجاح -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center shadow-sm mb-3"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success text-center shadow-sm mb-3"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- أولاً: نموذج إضافة تخصص جديد (العمود الأيمن) -->
        <div class="col-md-4">
            <div class="main-card">
                <h4 class="text-primary mb-3">➕ إضافة تخصص جديد</h4>
                <form action="index.php?page=specializations" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label font-weight-bold">اسم التخصص الطبي (بالإلكترونية أو العربية):</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="مثال: Dentistry, Orthopedics" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="add_spec" class="btn btn-primary font-weight-bold shadow-sm">تثبيت وإضافة التخصص 🚀</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ثانياً: جدول استعراض وحذف التخصصات (العمود الأيسر) -->
        <div class="col-md-8">
            <div class="main-card">
                <h4 class="text-dark mb-3">📋 قائمة التخصصات الطبية المتوفرة بالنظام</h4>
                <div class="table-responsive">
                    <table class="table table-hover text-right align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>رقم التخصص</th>
                                <th>اسم التخصص</th>
                                <th style="text-align: center;">التحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($specializations)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4 font-italic">لا توجد تخصصات مسجلة حالياً.</td>
                                } ?>
                            <?php else: ?>
                                <?php foreach ($specializations as $spec): ?>
                                    <tr>
                                        <td>#<?php echo $spec['id']; ?></td>
                                        <td><b><?php echo htmlspecialchars($spec['name']); ?></b></td>
                                        <td style="text-align: center;">
                                            <!-- رابط الحذف مضاف ومحمي بقيود RESTRICT داتابيز -->
                                            <a href="index.php?page=specializations&action=delete&id=<?php echo $spec['id']; ?>" 
                                               class="btn btn-outline-danger btn-sm font-weight-bold" 
                                               onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا التخصص؟ لن يتم الحذف إذا كان هناك أطباء مرتبطين به.');">
                                               🗑️ حذف التخصص
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
