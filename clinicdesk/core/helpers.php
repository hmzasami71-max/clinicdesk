<?php
// تأمين الملف من الاستدعاء المباشر
if (!defined('APP_NAME')) exit();

/**
 * دالة آمنة لرفع الملفات إلى السيرفر مع فحص الامتدادات والحجم
 */
function uploadMedicalFile(array $file, string $targetDir, int $maxSize, array $allowedExtensions): ?string {
    // التحقق من عدم وجود أخطاء في الرفع من المتصفح
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    //  التحقق من حجم الملف
    if ($file['size'] > $maxSize) {
        return 'size_error';
    }

    // استخراج وفحص امتداد الملف الفعلي
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowedExtensions)) {
        return 'extension_error';
    }

    // توليد اسم عشوائي فريد ومفرز لمنع الهجمات وتداخل الأسماء
    $newFileName = bin2hex(random_bytes(16)) . '.' . $fileExt;
    $destination = $targetDir . $newFileName;

    // نقل الملف للمجلد النهائي بنجاح
    if (move_uploaded_files_local($file['tmp_name'], $destination)) {
        return $newFileName;
    }

    return null;
}

// دالة مساعدة لعملية النقل
function move_uploaded_files_local($tmp, $dest) {
    return move_uploaded_file($tmp, $dest);
}
