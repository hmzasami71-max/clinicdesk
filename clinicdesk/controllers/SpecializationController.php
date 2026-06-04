<?php
require_once __DIR__ . '/../models/SpecializationModel.php';
require_once __DIR__ . '/../core/Auth.php';

class SpecializationController {
    private $specModel;

    public function __construct() {
        // تأمين المسار: المدير فقط من يدير التخصصات الطبية للعيادة
        Auth::requireRole('admin');
        $this->specModel = new SpecializationModel();
    }

    // عرض قائمة التخصصات وفورم الإضافة في نفس الصفحة لتسهيل الاستخدام
    public function index() {
        $error = ""; $success = "";

        // معالجة طلب الإضافة POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_spec'])) {
            $name = htmlspecialchars(trim($_POST['name']));
            if (empty($name)) {
                $error = "يرجى كتابة اسم التخصص أولاً.";
            } else {
                $isAdded = $this->specModel->add($name);
                if ($isAdded) { $success = "🎉 تم إضافة التخصص الطبي الجديد بنجاح!"; }
                else { $error = "❌ اسم التخصص موجود بالفعل أو حدث خطأ داخلي."; }
            }
        }

        // معالجة طلب الحذف GET
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            try {
                $isDeleted = $this->specModel->delete($id);
                if ($isDeleted) { header("Location: index.php?page=specializations"); exit(); }
            } catch (Exception $e) {
                $error = "❌ لا يمكن حذف هذا التخصص لأنه مرتبط بأطباء مسجلين به حالياً!";
            }
        }

        // جلب البيانات المحدثة للعرض
        $specializations = $this->specModel->getAll();
        $user = Auth::currentUser();
        require_once __DIR__ . '/../views/specializations/index.php';
    }
}
