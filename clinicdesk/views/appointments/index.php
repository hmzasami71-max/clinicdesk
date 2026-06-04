<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المواعيد - <?php echo APP_NAME; ?></title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f6f9; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header-bar { background: #343a40; color: #fff; padding: 15px 20px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header-bar h2 { margin: 0; font-size: 22px; }
        .btn { padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 13px; font-weight: bold; cursor: pointer; border: none; display: inline-block; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-success { background-color: #198754; color: white; }
        .btn-success:hover { background-color: #157347; text-decoration: none; }
        
        /* تنسيق زر تحرير الروشتة الأزرق الصغير */
        .btn-invoice { background-color: #0dcaf0; color: #212529; font-size: 12px; padding: 4px 8px; }
        .btn-invoice:hover { background-color: #31d2f2; text-decoration: none; }

        /* 🟢 تنسيق زر عرض وطباعة الروشتة الأخضر الصغير */
        .btn-print-rx { background-color: #198754; color: white; font-size: 11px; padding: 4px 8px; }
        .btn-print-rx:hover { background-color: #157347; color: white; text-decoration: none; }

        .card-header-flex { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; margin-bottom: 15px; }
        .card-header-flex h3 { margin: 0; }
        .card { background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 20px; }
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; text-align: right; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #dee2e6; vertical-align: middle; }
        th { background-color: #f8f9fa; color: #495057; font-weight: 600; }
        tr:hover { background-color: #f1f3f5; }
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }
        .badge-pending { background-color: #ffeeba; color: #856404; }
        .badge-confirmed { background-color: #d4edda; color: #155724; }
        .badge-completed { background-color: #cce5ff; color: #004085; }
        .badge-cancelled { background-color: #f8d7da; color: #721c24; }
        .no-data { text-align: center; padding: 30px; color: #777; font-style: italic; }
    </style>
</head>
<body>

<div class="container">
    <!-- شريط الرأس العلوي للملاحة -->
    <div class="header-bar">
        <h2>🗓️ نظام إدارة المواعيد الطبية</h2>
        <div>
            <a href="index.php?page=dashboard" class="btn btn-secondary" style="margin-left: 8px;">الرئيسية</a>
            <a href="index.php?page=auth&action=logout" class="btn btn-danger">تسجيل الخروج</a>
        </div>
    </div>

    <!-- بطاقة عرض الجدول الرئيسي -->
    <div class="card">
        <!-- شريط علوي مرن يحتوي على العنوان وزر الحجز الجديد -->
        <div class="card-header-flex">
            <h3>سجل المواعيد المحجوزة في العيادة</h3>
            <a href="index.php?page=appointments&action=book" class="btn btn-success font-weight-bold shadow-sm">+ حجز موعد جديد</a>
        </div>
        
        <p style="color: #666; font-size: 14px;">مرحباً <b><?php echo htmlspecialchars($user['name']); ?></b>، تظهر في الجدول أدناه كافة تفاصيل المواعيد وإجراءات العيادة.</p>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>رقم الموعد</th>
                        <th>اسم المريض</th>
                        <th>الطبيب المعالج</th>
                        <th>التخصص الطبّي</th>
                        <th>التاريخ</th>
                        <th>الوقت</th>
                        <th>حالة الحجز</th>
                        <th>سبب الزيارة</th>
                        <th style="text-align: center;">الإجراءات الطبية</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="9" class="no-data">لا توجد مواعيد محجوزة في قاعدة البيانات حالياً.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($appointments as $appt): ?>
                            <tr>
                                <td>#<?php echo $appt['id']; ?></td>
                                <td><b><?php echo htmlspecialchars($appt['patient_name']); ?></b></td>
                                <td>د. <?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                                <td><?php echo htmlspecialchars($appt['specialization_name']); ?></td>
                                <td><?php echo htmlspecialchars($appt['appt_date']); ?></td>
                                <td><?php echo date('h:i A', strtotime($appt['appt_time'])); ?></td>
                                <td>
                                    <?php 
                                    $statusClass = 'badge-pending';
                                    $statusText = 'قيد الانتظار';
                                    if ($appt['status'] === 'confirmed') { $statusClass = 'badge-confirmed'; $statusText = 'مؤكد'; }
                                    elseif ($appt['status'] === 'completed') { $statusClass = 'badge-completed'; $statusText = 'مكتمل'; }
                                    elseif ($appt['status'] === 'cancelled') { $statusClass = 'badge-cancelled'; $statusText = 'ملغي'; }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                </td>
                                <td><small><?php echo htmlspecialchars($appt['reason'] ?? 'فحص عام'); ?></small></td>
                                <td style="text-align: center;">
                                    <?php if ($appt['status'] !== 'completed' && $appt['status'] !== 'cancelled'): ?>
                                        <!-- زر كتابة الروشتة يظهر فقط للمواعيد النشطة -->
                                        <a href="index.php?page=prescriptions&action=create&appt_id=<?php echo $appt['id']; ?>" class="btn btn-invoice">✍️ كتابة روشتة</a>
                                    <?php elseif ($appt['status'] === 'completed'): ?>
                                        <!-- 🟢 زر عرض وطباعة الروشتة المضافة حديثاً للمواعيد المكتملة -->
                                        <a href="index.php?page=prescriptions&action=view&id=<?php echo $appt['id']; ?>" class="btn btn-print-rx">👁️ عرض وطباعة الروشتة</a>
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

</body>
</html>
