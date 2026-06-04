<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>وصفة طبية رقم #<?php echo $prescription['prescription_id']; ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 40px; color: #333; }
        .prescription-box { max-width: 800px; margin: 0 auto; background: #fff; border: 2px solid #007bff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); position: relative; }
        .clinic-header { border-bottom: 3px double #007bff; padding-bottom: 15px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .clinic-title { font-size: 26px; color: #007bff; font-weight: bold; margin: 0; }
        .clinic-info { text-align: left; font-size: 14px; color: #666; }
        .patient-info-bar { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 12px; margin-bottom: 25px; display: flex; justify-content: space-between; font-size: 15px; }
        .rx-title { font-size: 32px; color: #007bff; font-weight: bold; margin: 10px 0; font-style: italic; }
        .section-title { font-size: 18px; font-weight: bold; color: #212529; border-bottom: 1px solid #dee2e6; padding-bottom: 5px; margin-top: 20px; }
        .content-text { font-size: 16px; padding: 10px 0; line-height: 1.8; white-space: pre-line; }
        .footer-signature { margin-top: 25px; border-top: 1px dashed #ccc; padding-top: 15px; text-align: left; font-weight: bold; }
        
        /* شريط التحكم العلوي للأزرار */
        .actions-bar { max-width: 800px; margin: 0 auto 20px auto; display: flex; justify-content: space-between; }
        .btn { padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 14px; cursor: pointer; border: none; display: inline-block; }
        .btn-print { background-color: #28a745; color: white; }
        .btn-back { background-color: #6c757d; color: white; }
        
        /* تنسيق قسم المرفق الطبي المستدعى */
        .attachment-box { background-color: #e8f4fd; border: 1px solid #b8daff; border-radius: 6px; padding: 15px; margin-top: 25px; display: flex; justify-content: space-between; align-items: center; }
        .btn-download { background-color: #007bff; color: white; padding: 6px 12px; font-size: 13px; border-radius: 4px; text-decoration: none; }
        .btn-download:hover { background-color: #0056b3; }

        @media print {
            body { background: white; padding: 0; margin: 0; }
            .actions-bar { display: none !important; }
            .attachment-box { display: none !important; } /* إخفاء صندوق التحميل أثناء الطباعة الورقية */
            .prescription-box { border: none; box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>

<div class="actions-bar">
    <a href="index.php?page=appointments&action=index" class="btn btn-back">🔙 عودة لجدول المواعيد</a>
    <button onclick="window.print();" class="btn btn-print">🖨️ اضغط لطباعة الروشتة فوراً</button>
</div>

<div class="prescription-box">
    <div class="clinic-header">
        <div>
            <h1 class="clinic-title">🏥 <?php echo APP_NAME; ?></h1>
            <small>د. <?php echo htmlspecialchars($prescription['doctor_name']); ?> - <?php echo htmlspecialchars($prescription['specialization_name']); ?></small>
        </div>
        <div class="clinic-info">
            <b>تاريخ التحرير:</b> <?php echo date('Y-m-d', strtotime($prescription['created_at'])); ?><br>
            <b>رقم الروشتة:</b> #<?php echo $prescription['prescription_id']; ?>
        </div>
    </div>

    <div class="patient-info-bar">
        <div><b>اسم المريض:</b> <?php echo htmlspecialchars($prescription['patient_name']); ?></div>
        <div><b>تاريخ الكشف:</b> <?php echo htmlspecialchars($prescription['appt_date']); ?></div>
        <div><b>الهاتف:</b> <?php echo htmlspecialchars($prescription['patient_phone'] ?? '---'); ?></div>
    </div>

    <div class="rx-title">Rₓ</div>

    <div class="section-title">🔎 التشخيص الطبي (Diagnosis):</div>
    <div class="content-text"><?php echo htmlspecialchars($prescription['diagnosis']); ?></div>

    <div class="section-title">💊 العلاج والأدوية المقررة (Medications):</div>
    <div class="content-text" style="color: #004085; font-weight: 500;"><?php echo htmlspecialchars($prescription['medications']); ?></div>

    <?php if (!empty($prescription['notes'])): ?>
        <div class="section-title">📌 إرشادات وتوصيات الطبيب:</div>
        <div class="content-text" style="font-style: italic; color: #555;"><?php echo htmlspecialchars($prescription['notes']); ?></div>
    <?php endif; ?>

    <!-- 🟢 قسم فحص وعرض المرفق الطبي المرفوع ديناميكياً -->
    <?php if (!empty($prescription['file_path'])): ?>
        <div class="attachment-box">
            <div>
                <span style="font-weight: bold; color: #004085;">📁 يتوفر ملف طبي مرفق مع هذه الوصفة</span>
                <br><small class="text-muted">صور أشعة، تحاليل مخبرية أو تقارير إضافية بصيغة رقمية.</small>
            </div>
            <a href="<?php echo BASE_URL . 'public/uploads/' . $prescription['file_path']; ?>" target="_blank" class="btn-download">👁️ فتح ومعاينة المرفق</a>
        </div>
    <?php endif; ?>

    <div class="footer-signature">
        توقيع الطبيب: د. <?php echo htmlspecialchars($prescription['doctor_name']); ?>
        <br><small style="font-weight: normal; color: #777;">توقيع إلكتروني معتمد بالنظام</small>
    </div>
</div>

</body>
</html>
