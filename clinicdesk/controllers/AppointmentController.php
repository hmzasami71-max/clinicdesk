<?php
require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../core/Auth.php';

class AppointmentController {
    private $appointmentModel;

    public function __construct() {
        Auth::requireRole('admin', 'doctor', 'patient');
        $this->appointmentModel = new AppointmentModel();
    }

    // 1. عرض جدول المواعيد
    public function index() {
        $appointments = $this->appointmentModel->getAllAppointments();
        $user = Auth::currentUser();
        require_once __DIR__ . '/../views/appointments/index.php';
    }

    // 2. شاشة ومعالجة حجز موعد جديد
    public function book() {
        $user = Auth::currentUser();
        $error = "";
        $success = "";

        // جلب قائمة الأطباء من قاعدة البيانات لعرضهم في القائمة المنسدلة 
        $db = Database::getInstance();
        $doctorsResult = $db->query("SELECT d.id, u.name, s.name AS spec FROM doctors d JOIN users u ON d.user_id = u.id JOIN specializations s ON d.specialization_id = s.id WHERE u.is_active = 1");
        $doctors = [];
        if ($doctorsResult) {
            while ($row = $doctorsResult->fetch_assoc()) { $doctors[] = $row; }
        }

        // معالجة البيانات عند إرسال النموذج (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $doctor_id = (int)$_POST['doctor_id'];
            $appt_date = trim($_POST['appt_date']);
            $appt_time = trim($_POST['appt_time']);
            $reason    = htmlspecialchars(trim($_POST['reason']));
            
            // في حال كان المدير هو من يحجز، نثبت الحجز للمريض رقم 2 مؤقتاً للتجربة، وإلا نأخذ معرف المريض الحالي من الجلسة
            $patient_id = ($user['role'] === 'admin') ? 2 : $user['id'];

            if (empty($doctor_id) || empty($appt_date) || empty($appt_time)) {
                $error = "يرجى ملء جميع الحقول الإلزامية.";
            } else {
                // استدعاء دالة الحجز من الموديل
                $isBooked = $this->appointmentModel->bookAppointment($patient_id, $doctor_id, $appt_date, $appt_time, $reason);
                
                if ($isBooked) {
                    $success = "🎉 تم حجز الموعد بنجاح تام وتم جدولة الزيارة!";
                } else {
                    $error = "❌ عذراً، هذا الوقت محجوز مسبقاً لدى الطبيب. يرجى اختيار وقت آخر.";
                }
            }
        }

        // استدعاء واجهة فورم الحجز
        require_once __DIR__ . '/../views/appointments/book.php';
    }
}
