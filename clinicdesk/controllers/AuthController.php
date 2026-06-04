<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // عرض صفحة تسجيل الدخول ومعالجة البيانات المرسلة منها
    public function login() {
        // إذا كان المستخدم مسجلاً دخوله بالفعل، يتم توجيهه تلقائياً للوحة التحكم
        if (Auth::check()) {
            header("Location: " . BASE_URL . "index.php?page=dashboard");
            exit();
        }

        $error = "";

        // معالجة البيانات فقط إذا كان الطلب من نوع POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            //   لحماية النموذج من التزوير
            if (!isset($_POST['csrf_token']) || !CSRF::validateToken($_POST['csrf_token'])) {
                die("CSRF token validation failed. Unauthorized request.");
            }

            // تنظيف وتطهير البيانات المستلمة (XSS Protection)
            $email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);

            if (empty($email) || empty($password)) {
                $error = "يرجى ملء جميع الحقول المطلوبة.";
            } else {
                //  البحث عن المستخدم في قاعدة البيانات عبر البريد الإلكتروني
                $user = $this->userModel->findByEmail($email);

                //  التحقق من وجود الحساب، وتطابق كلمة المرور المشفرة وحالة الحساب
                if ($user && password_verify($password, $user['password'])) {
                    if ((int)$user['is_active'] === 1) {
                        // تسجيل الدخول بنجاح وتوجيهه للوحة التحكم
                        Auth::login($user);
                        header("Location: " . BASE_URL . "index.php?page=dashboard");
                        exit();
                    } else {
                        $error = "هذا الحساب معطل حالياً، يرجى مراجعة الإدارة.";
                    }
                } else {
                    $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
                }
            }
        }

        // استدعاء ملف العرض لصفحة اللوجن وتمرير متغير الخطأ إن وجد
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // معالجة عملية تسجيل الخروج وتدمير الجلسة الآمن
    public function logout() {
        Auth::logout();
    }
}
