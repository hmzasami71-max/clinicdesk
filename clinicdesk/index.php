<?php
// تضمين ملف الإعدادات العامة لبدء الجلسات والتهيئة الأمنية
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Auth.php';

// قراءة المسار المطلوب (الصفحة وتحديد القيم الافتراضية
$page   = $_GET['page']   ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

// التوجيه البرمجي بناءً على قيمة المتغير $page
switch ($page) {
    
    // مسار نظام المصادقة
    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();
        if ($action === 'login') {
            $controller->login();
        } elseif ($action === 'logout') {
            $controller->logout();
        } else {
            goto_404();
        }
        break;

    // مسار لوحات التحكم الرئيسية للأدوار الثلاثة
    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;

    // مسار إدارة المواعيد الطبيّة
    case 'appointments':
        require_once __DIR__ . '/controllers/AppointmentController.php';
        $controller = new AppointmentController();
        if ($action === 'index') { $controller->index(); }
        elseif ($action === 'book') { $controller->book(); }
        else { goto_404(); }
        break;

    // مسار نظام إدارة وعرض الروشتات
    case 'prescriptions':
        require_once __DIR__ . '/controllers/PrescriptionController.php';
        $controller = new PrescriptionController();
        if ($action === 'create') { 
            $controller->create(); 
        } elseif ($action === 'view') { 
            $controller->view();
        } else { 
            goto_404(); 
        }
        break;

    // مسار نظام إدارة الأطباء من قِبل مدير النظام
    case 'doctors':
        require_once __DIR__ . '/controllers/DoctorController.php';
        $controller = new DoctorController();
        if ($action === 'index') { 
            $controller->index(); 
        } elseif ($action === 'create') { 
            $controller->create(); 
        } else { 
            goto_404(); 
        }
        break;

    //  مسار نظام إدارة التخصصات الطبية المضاف حديثاً للمدير
    case 'specializations':
        require_once __DIR__ . '/controllers/SpecializationController.php';
        $controller = new SpecializationController();
        $controller->index(); 
        break;

    // مسارات معالجة الأخطاء في النظام
    case 'errors':
        if ($action === '403') {
            require_once __DIR__ . '/views/errors/403.php';
        } else {
            goto_404();
        }
        break;

    // أي مسار غير معرف يتم تحويله تلقائياً لصفحة غير موجود 404
    default:
        goto_404();
        break;
}

// دالة مساعدة لعرض صفحة الخطأ 404 عند طلب مسار وهمي
function goto_404() {
    http_response_code(404);
    require_once __DIR__ . '/views/errors/404.php';
    exit();
}
