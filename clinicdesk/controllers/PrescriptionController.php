<?php
require_once __DIR__ . '/../models/PrescriptionModel.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/helpers.php'; // تضمين الدوال المساعدة لرفع الملفات

class PrescriptionController {
    private $prescriptionModel;

    public function __construct() {
        Auth::requireRole('admin', 'doctor');
        $this->prescriptionModel = new PrescriptionModel();
    }

    public function create() {
        $appointmentId = isset($_GET['appt_id']) ? (int)$_GET['appt_id'] : 0;
        if ($appointmentId === 0) {
            header("Location: index.php?page=appointments&action=index");
            exit();
        }

        $error = "";
        $success = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $diagnosis   = htmlspecialchars(trim($_POST['diagnosis']));
            $medications = htmlspecialchars(trim($_POST['medications']));
            $notes       = htmlspecialchars(trim($_POST['notes']));
            $uploadedFileName = null;

            //  معالجة رفع الملف الطبي إن وجد بالطلب
            if (isset($_FILES['medical_file']) && $_FILES['medical_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $targetDir = __DIR__ . '/../public/uploads/';
                $allowedExts = ['pdf', 'png', 'jpg', 'jpeg'];
                
                // استدعاء دالة الرفع الآمنة (الحد الأقصى 3 ميجا بايت للتقارير)
                $uploadResult = uploadMedicalFile($_FILES['medical_file'], $targetDir, MAX_PDF_SIZE, $allowedExts);

                if ($uploadResult === 'size_error') {
                    $error = "❌ حجم الملف كبير جداً! الحد الأقصى المسموح به هو 3 ميغابايت.";
                } elseif ($uploadResult === 'extension_error') {
                    $error = "❌ امتداد الملف غير مسموح! يرجى رفع ملفات بصيغة (PDF, PNG, JPG) فقط.";
                } elseif ($uploadResult === null) {
                    $error = "❌ حدث خطأ داخلي أثناء رفع الملف المرفق للسيرفر.";
                } else {
                    $uploadedFileName = $uploadResult; // حفظ الاسم المشفر الجديد
                }
            }

            if (empty($error)) {
                if (empty($diagnosis) || empty($medications)) {
                    $error = "يرجى كتابة التشخيص والأدوية المقررة.";
                } else {
                    // تمرير اسم الملف المرفق للدالة لحفظه
                    $isSaved = $this->prescriptionModel->createPrescription($appointmentId, $diagnosis, $medications, $notes, $uploadedFileName);
                    if ($isSaved) {
                        $db = Database::getInstance();
                        $db->query("UPDATE appointments SET status = 'completed' WHERE id = ?", "i", [$appointmentId]);
                        $success = "🎉 تم حفظ الوصفة الطبية والمرفق بنجاح تام وتحويل حالة الموعد إلى مكتمل!";
                    } else {
                        $error = "❌ حدث خطأ أثناء حفظ البيانات في قاعدة البيانات.";
                    }
                }
            }
        }

        require_once __DIR__ . '/../views/prescriptions/create.php';
    }

    public function view() {
        $prescriptionId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($prescriptionId === 0) {
            header("Location: index.php?page=appointments&action=index");
            exit();
        }

        $prescription = $this->prescriptionModel->getPrescriptionDetails($prescriptionId);
        if (!$prescription) {
            die("عذراً، الوصفة الطبية المطلوبة غير موجودة في النظام.");
        }

        $user = Auth::currentUser();
        require_once __DIR__ . '/../views/prescriptions/view.php';
    }
}

