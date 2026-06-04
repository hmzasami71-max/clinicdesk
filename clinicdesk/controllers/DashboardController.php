<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/AppointmentModel.php';

class DashboardController {

    public function __construct() {
        Auth::requireRole('admin', 'doctor', 'patient');
    }

    public function index() {
        $user = Auth::currentUser();
        $db = Database::getInstance();
        $appointmentModel = new AppointmentModel();
        
        $stats = [
            'total_appointments' => 0, 'total_doctors' => 0,
            'total_patients' => 0, 'pending_appts' => 0
        ];
        
        $doctorAppointments = [];
        $patientAppointments = []; //  مصفوفة لتخزين مواعيد المريض الحالي

        // 1. إذا كان المستخدم مديراً
        if ($user['role'] === 'admin') {
            $res1 = $db->query("SELECT COUNT(*) AS count FROM appointments");
            if ($res1) { $stats['total_appointments'] = $res1->fetch_assoc()['count']; }

            $res2 = $db->query("SELECT COUNT(*) AS count FROM doctors");
            if ($res2) { $stats['total_doctors'] = $res2->fetch_assoc()['count']; }

            $res3 = $db->query("SELECT COUNT(*) AS count FROM users WHERE role = 'patient'");
            if ($res3) { $stats['total_patients'] = $res3->fetch_assoc()['count']; }

            $res4 = $db->query("SELECT COUNT(*) AS count FROM appointments WHERE status = 'pending'");
            if ($res4) { $stats['pending_appts'] = $res4->fetch_assoc()['count']; }
        }
        
        // إذا كان المستخدم طبيباً
        if ($user['role'] === 'doctor') {
            $doctorAppointments = $appointmentModel->getDoctorAppointments($user['id']);
        }

        //  إذا كان المستخدم مريضاً، جلب سجل زياراته وروشتاته
        if ($user['role'] === 'patient') {
            $patientAppointments = $appointmentModel->getPatientAppointments($user['id']);
        }
        
        // التوجيه التلقائي لواجهة العرض بناءً على دور المستخدم
        switch ($user['role']) {
            case 'admin':
                require_once __DIR__ . '/../views/dashboard/admin.php';
                break;
                
            case 'doctor':
                require_once __DIR__ . '/../views/dashboard/doctor.php';
                break;
                
            case 'patient':
                require_once __DIR__ . '/../views/dashboard/patient.php';
                break;
                
            default:
                Auth::logout();
                break;
        }
    }
}
