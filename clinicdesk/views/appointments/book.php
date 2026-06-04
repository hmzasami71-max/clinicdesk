<?php 
$pageTitle = "حجز موعد جديد";
require_once __DIR__ . '/../partials/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card main-card p-4">
            <div class="border-bottom pb-3 mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-primary mb-1">➕ حجز موعد طبي جديد</h3>
                    <p class="text-muted small mb-0">يرجى اختيار الطبيب وتحديد التاريخ والوقت المناسبين.</p>
                </div>
                <a href="index.php?page=appointments&action=index" class="btn btn-outline-secondary btn-sm">🔙 عودة للسجل</a>
            </div>

            <!-- عرض رسائل النجاح أو الفشل الملونة -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success text-center"><?php echo $success; ?></div>
            <?php endif; ?>

            <form action="index.php?page=appointments&action=book" method="POST">
                
                <!-- 1. اختيار الطبيب والتخصص -->
                <div class="mb-3">
                    <label for="doctor_id" class="form-label font-weight-bold">اختر الطبيب المعالج والتخصص:</label>
                    <select name="doctor_id" id="doctor_id" class="form-select" required>
                        <option value="">-- اضغط هنا لاختيار الطبيب --</option>
                        <?php foreach ($doctors as $doc): ?>
                            <option value="<?php echo $doc['id']; ?>">د. <?php echo htmlspecialchars($doc['name']); ?> (<?php echo htmlspecialchars($doc['spec']); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <!-- 2. تحديد التاريخ -->
                    <div class="col-md-6 mb-3">
                        <label for="appt_date" class="form-label">تاريخ الزيارة:</label>
                        <input type="date" name="appt_date" id="appt_date" class="form-select" min="<?php echo date('Y-m-t'); ?>" required>
                    </div>

                    <!-- 3. تحديد الوقت -->
                    <div class="col-md-6 mb-3">
                        <label for="appt_time" class="form-label">توقيت الحجز:</label>
                        <input type="time" name="appt_time" id="appt_time" class="form-select" required>
                    </div>
                </div>

                <!-- 4. سبب الزيارة -->
                <div class="mb-4">
                    <label for="reason" class="form-label">سبب الزيارة أو الأعراض (اختياري):</label>
                    <textarea name="reason" id="reason" rows="3" class="form-control" placeholder="اكتب هنا باختصار سبب الاستشارة الطبية..."></textarea>
                </div>

                <!-- زر الإرسال -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg font-weight-bold shadow-sm">تأكيد وإتمام الحجز الفوري 🚀</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
