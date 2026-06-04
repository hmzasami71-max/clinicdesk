<?php
// تفعيل عرض الأخطاء     
ini_set('display_errors', 0);
error_reporting(0);

// الثوابت العامة للتطبيق
define('APP_NAME', 'ClinicDesk');
// ابحث عن هذا السطر في ملف config/config.php وقم بتحديثه 
define('BASE_URL', 'http://localhost/program/clinicdesk/');

// إعدادات تقسيم الصفحات والرفع
define('ITEMS_PER_PAGE', 10);
define('MAX_AVATAR_SIZE', 1024 * 1024); 
define('MAX_PDF_SIZE', 3 * 1024 * 1024);  

// بدء الجلسة بأمان في كافة أنحاء النظام
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
