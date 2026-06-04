<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الطبيب - ClinicDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; }
        .navbar-custom { background-color: #0d6efd; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link { color: white !important; font-weight: bold; }
        .main-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: white; padding: 25px; }
    </style>
</head>
<body>

<!-- شريط الملاحة الخاص بالطبيب -->
<nav class="navbar navbar-expand-lg navbar-custom mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php?page=dashboard">🏥 ClinicDesk - بوابة الطبيب</a>
    <div class="d-flex">
        <a href="index.php?page=auth&action=logout" class="btn btn-danger btn-sm font-weight-bold">تسجيل الخروج</a>
    </div>
  </div>
</nav>

<div class="container">
    <!-- هيدر الترحيب -->
    <div class="p-4 mb-4 bg-white rounded-3 shadow-sm border">
        <h1 class="display-6 text-primary font-weight-bold">أهلاً بك يا دكتور: <?php echo htmlspecialchars($user['name']); ?> 🩺</h1>
        <p class="fs-6 text-muted mb-0">تظهر أدناه قائمة المواعيد الطبية المجدولة لعيادتك الشخصية وإجراءات الفحص والتشخيص الفوري.</p>
    </div>

    <!-- جدول مواعيد الطبيب -->
    <div class="main-card">
        <h4 class="text-dark mb-3">🗓️ جدول مواعيدي الطبية القادمة والسابقة</h4>
        <div class="table-responsive">
            <table class="table table-hover text-right align-middle">
                <thead class="table-light">
                    <tr>
                        <th>رقم الموعد</th>
                        <th>اسم المريض</th>
                        <th>رقم الهاتف</th>
                        <th>التاريخ</th>
                        <th>الوقت</th>
                        <th>الحالة</th>
                        <th>سبب الاستشارة</th>
                        <th style="text-align: center;">الإجراء الطبي</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($doctorAppointments)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5 font-italic">لا توجد مواعيد مجدولة باسمك في قاعدة البيانات حالياً.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($doctorAppointments as $appt): ?>
                            <tr>
                                <td>#<?php echo $appt['id']; ?></td>
                                <td><b><?php echo htmlspecialchars($appt['patient_name']); ?></b></td>
                                <td><?php echo htmlspecialchars($appt['patient_phone'] ?? '---'); ?></td>
                                <td><?php echo htmlspecialchars($appt['appt_date']); ?></td>
                                <td><?php echo date('h:i A', strtotime($appt['appt_time'])); ?></td>
                                <td>
                                    <?php 
                                    $badge = 'bg-warning text-dark'; $text = 'قيد الانتظار';
                                    if ($appt['status'] === 'confirmed') { $badge = 'bg-success'; $text = 'مؤكد'; }
                                    elseif ($appt['status'] === 'completed') { $badge = 'bg-info'; $text = 'مكتمل'; }
                                    elseif ($appt['status'] === 'cancelled') { $badge = 'bg-danger'; $text = 'ملغي'; }
                                    ?>
                                    <span class="badge <?php echo $badge; ?> py-2 px-2"><?php echo $text; ?></span>
                                </td>
                                <td><small class="text-muted"><?php echo htmlspecialchars($appt['reason'] ?? 'فحص عام'); ?></small></td>
                                <td style="text-align: center;">
                                    <?php if ($appt['status'] !== 'completed' && $appt['status'] !== 'cancelled'): ?>
                                        <a href="index.php?page=prescriptions&action=create&appt_id=<?php echo $appt['id']; ?>" class="btn btn-primary btn-sm">✍️ كتابة روشتة</a>
                                    <?php elseif ($appt['status'] === 'completed'): ?>
                                        <a href="index.php?page=prescriptions&action=view&id=<?php echo $appt['id']; ?>" class="btn btn-success btn-sm">👁️ عرض الروشتة</a>
                                    <?php else: ?>
                                        <span class="text-muted small">لا توجد إجراءات</span>
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

<footer class="text-center py-4 text-muted mt-5">
    <p class="small">&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?> - بوابة الطبيب المعتمدة.</p>
</footer>

<script src="https://jsdelivr.net"></script>
</body>
</html>
