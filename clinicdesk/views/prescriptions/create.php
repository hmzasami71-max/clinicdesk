<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحرير وصفة طبية جديدة</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f6f9; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 800px; margin: 0 auto; }
        .header-bar { background: #007bff; color: #fff; padding: 15px 20px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header-bar h2 { margin: 0; font-size: 20px; }
        .btn { padding: 8px 16px; border-radius: 5px; text-decoration: none; font-size: 14px; font-weight: bold; cursor: pointer; border: none; display: inline-block; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-primary { background-color: #007bff; color: white; width: 100%; font-size: 16px; padding: 12px; }
        .card { background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 25px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: #495057; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 5px; font-size: 15px; resize: vertical; }
        .form-control:focus { border-color: #80bdff; outline: 0; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); }
        .alert { padding: 12px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .help-text { font-size: 12px; color: #6c757d; margin-top: 5px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-bar">
        <h2>📝 تحرير وصفة طبية (روشتة) للموعد رقم #<?php echo $appointmentId; ?></h2>
        <a href="index.php?page=appointments&action=index" class="btn btn-secondary">إلغاء والعودة</a>
    </div>

    <div class="card">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
                <br><small><a href="index.php?page=appointments&action=index" style="color: #155724; text-decoration: underline;">اضغط هنا للعودة لمتابعة المواعيد</a></small>
            </div>
        <?php endif; ?>

        <!-- 🟢 تفعيل enctype="multipart/form-data" لتمكين رفع المرفقات الطبية بأمان -->
        <form action="index.php?page=prescriptions&action=create&appt_id=<?php echo $appointmentId; ?>" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label for="diagnosis">التشخيص الطبّي للحالة:</label>
                <textarea name="diagnosis" id="diagnosis" rows="3" class="form-control" placeholder="اكتب هنا تشخيصك الدقيق لشكوى المريض..." required></textarea>
            </div>

            <div class="form-group">
                <label for="medications">الأدوية المقررة والجرعات:</label>
                <textarea name="medications" id="medications" rows="5" class="form-control" placeholder="مثال:&#10;1. Panadol 500mg - قرص كل 8 ساعات" required></textarea>
            </div>

            <div class="form-group">
                <label for="notes">ملاحظات أو توصيات إضافية (اختياري):</label>
                <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="توصيات الراحة، مراجعة الاستشارة، تحاليل مطلوبة..."></textarea>
            </div>

            <!-- 🟢 حقل رفع المرفقات الطبية الجديد -->
            <div class="form-group border rounded p-3 bg-light">
                <label for="medical_file">📁 إرفاق ملف طبي مساند (اختياري):</label>
                <input type="file" name="medical_file" id="medical_file" class="form-control" accept=".pdf,.png,.jpg,.jpeg">
                <div class="help-text">الصيغ المسموحة: PDF, PNG, JPG, JPEG فقط. الحد الأقصى للحجم: 3 ميغابايت.</div>
            </div>

            <button type="submit" class="btn btn-primary">حفظ واعتماد الوصفة الطبية والمرفق 💾</button>
        </form>
    </div>
</div>

</body>
</html>
