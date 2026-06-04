<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الطبي للمريض - ClinicDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; }
        .navbar-custom { background-color: #198754; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link { color: white !important; font-weight: bold; }
        .main-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: white; padding: 25px; }
    </style>
</head>
<body>

<!-- شريط الملاحة المريض -->
<nav class="navbar navbar-expand-lg navbar-custom mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php?page=dashboard">🏥 ClinicDesk - بوابة المريض الرقمية</a>
    <div class="d-flex">
        <a href="index.php?page=auth&action=logout" class="btn btn-danger btn-sm font-weight-bold">تسجيل الخروج</a>
    </div>
  </div>
</nav>

<div class="container">
    <!-- هيدر الترحيب للمريض -->
    <div class="p-4 mb-4 bg-white rounded-3 shadow-sm border border-success border-opacity-25">
        <h1 class="display-6 text-success font-weight-bold">مرحباً بك يا: <?php echo htmlspecialchars($user['name']); ?> ✨</h1>
        <p class="fs-6 text-muted mb-0">يمكنك من هنا متابعة مواعيدك الطبية القادمة، وحجز مواعيد جديدة، أو استعراض وطباعة وصفاتك الطبية السابقة مباشرة بدون مراجعة الاستقبال.</p>
        <div class="mt-3">
            <a href="index.php?page=appointments&action=book" class="btn btn-success font-weight-bold shadow-sm">+ حجز استشارة طبية جديدة</a>
        </div>
    </div>

    <!-- جدول المواعيد والروشتات الخاصة بالمريض -->
    <div class="main-card">
        <h4 class="text-dark mb-3">📋 سجل زياراتي ومواعيدي الطبية</h4>
        <div class="table-responsive">
            <table class="table table-hover text-right align-middle">
                <thead class="table-light">
                    <tr>
                        <th>رقم الموعد</th>
                        <th>الطبيب المعالج</th>
                        <th>التخصص الطبي</th>
                        <th>التاريخ</th>
                        <th>الوقت</th>
                        <th>حالة الحجز</th>
                        <th>الروشتة الطبية</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($patientAppointments)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5 font-italic">ليس لديك أي مواعيد مسجلة في النظام حالياً.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($patientAppointments as $appt): ?>
                            <tr>
                                <td>#<?php echo $appt['appt_id']; ?></td>
                                <td><b>د. <?php echo htmlspecialchars($appt['doctor_name']); ?></b></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($appt['specialization_name']); ?></span></td>
                                <td><?php echo htmlspecialchars($appt['appt_date']); ?></td>
                                <td><?php echo date('h:i A', strtotime($appt['appt_time'])); ?></td>
                                <td>
                                    <?php 
                                    $badge = 'bg-warning text-dark'; $text = 'قيد الانتظار';
                                    if ($appt['status'] === 'confirmed') { $badge = 'bg-primary'; $text = 'مؤكد'; }
                                    elseif ($appt['status'] === 'completed') { $badge = 'bg-success'; $text = 'مكتمل'; }
                                    elseif ($appt['status'] === 'cancelled') { $badge = 'bg-danger'; $text = 'ملغي'; }
                                    ?>
                                    <span class="badge <?php echo $badge; ?> py-2 px-2"><?php echo $text; ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($appt['prescription_id'])): ?>
                                        <!-- إذا كُتبت روشتة، يستطيع المريض فتحها وطباعتها -->
                                        <a href="index.php?page=prescriptions&action=view&id=<?php echo $appt['prescription_id']; ?>" class="btn btn-outline-success btn-sm font-weight-bold">🖨️ طباعة الروشتة</a>
                                    <?php else: ?>
                                        <span class="text-muted small">لم تُحرر بعد</span>
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
    <p class="small">&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?> - الملف الطبي الإلكتروني الآمن.</p>
</footer>

<script src="https://jsdelivr.net"></script>
</body>
</html>
