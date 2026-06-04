<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأطباء - ClinicDesk</title>
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
            <li class="nav-item"><a class="nav-link active" href="index.php?page=doctors&action=index">إدارة الأطباء</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?page=appointments&action=index">سجل المواعيد</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- بطاقة العرض الرئيسية -->
    <div class="main-card">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h3 class="text-primary mb-1">🩺 إدارة الأطباء المسجلين</h3>
                <p class="text-muted small mb-0">يمكنك هنا استعراض بيانات الأطباء وتخصصاتهم أو إضافة أطباء جدد للنظام.</p>
            </div>
            <a href="index.php?page=doctors&action=create" class="btn btn-success font-weight-bold shadow-sm">+ إضافة طبيب جديد</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover text-right align-middle">
                <thead class="table-light">
                    <tr>
                        <th>رقم الحساب</th>
                        <th>اسم الطبيب</th>
                        <th>التخصص الطبي</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>سعر الكشفية</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($doctors)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5 font-italic">لا يوجد أطباء مسجلين في النظام حالياً.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($doctors as $doc): ?>
                            <tr>
                                <td>#<?php echo $doc['doctor_id']; ?></td>
                                <td><b>د. <?php echo htmlspecialchars($doc['name']); ?></b></td>
                                <td><span class="badge bg-primary py-2 px-3"><?php echo htmlspecialchars($doc['specialization_name']); ?></span></td>
                                <td><?php echo htmlspecialchars($doc['email']); ?></td>
                                <td><?php echo htmlspecialchars($doc['phone'] ?? '---'); ?></td>
                                <td><b class="text-success"><?php echo number_format($doc['consultation_fee'], 2); ?> $</b></td>
                                <td>
                                    <?php if ((int)$doc['is_active'] === 1): ?>
                                        <span class="badge bg-success">نشط</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">محظور</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
