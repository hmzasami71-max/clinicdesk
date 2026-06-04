<?php
require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../core/Auth.php';

class DoctorController {
    private $doctorModel;

    public function __construct() {
        //  المدير فقط هو من يستطيع إدارة الحسابات والأطباء
        Auth::requireRole('admin');
        $this->doctorModel = new DoctorModel();
    }

    // 1. عرض قائمة الأطباء المسجلين
    public function index() {
        $doctors = $this->doctorModel->getAllDoctors();
        $user = Auth::currentUser();
        require_once __DIR__ . '/../views/doctors/index.php';
    }

    // 2. معالجة نموذج إضافة طبيب جديد
    public function create() {
        $user = Auth::currentUser();
        $error = "";
        $success = "";
        $db = Database::getInstance();

        // جلب التخصصات لعرضها في القائمة المنسدلة بالفورم
        $specResult = $db->query("SELECT id, name FROM specializations");
        $specializations = [];
        if ($specResult) {
            while ($row = $specResult->fetch_assoc()) { $specializations[] = $row; }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name']);
            $email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
            $phone    = trim($_POST['phone']);
            $spec_id  = (int)$_POST['specialization_id'];
            $fee      = (float)$_POST['consultation_fee'];

            if (empty($name) || empty($email) || empty($password) || empty($spec_id)) {
                $error = "يرجى ملء جميع الحقول الإلزامية الخاصة بحساب الطبيب.";
            } else {
                // التأكد من عدم تكرار البريد الإلكتروني في النظام
                $emailCheck = $db->query("SELECT id FROM users WHERE email = ? LIMIT 1", "s", [$email]);
                if ($emailCheck && $emailCheck->num_rows > 0) {
                    $error = "❌ البريد الإلكتروني مستخدم بالفعل لطبيب أو مستخدم آخر.";
                } else {
                    // تنفيذ الإضافة عبر الموديل
                    $isAdded = $this->doctorModel->addDoctor($name, $email, $password, $phone, $spec_id, $fee);
                    if ($isAdded) {
                        $success = "🎉 تم إنشاء حساب الطبيب وربطه بالتخصص بنجاح!";
                    } else {
                        $error = "❌ حدث خطأ غير متوقع أثناء حفظ البيانات.";
                    }
                }
            }
        }

        require_once __DIR__ . '/../views/doctors/create.php';
    }
}
