<?php
require_once __DIR__ . '/BaseModel.php';

class AppointmentModel extends BaseModel {

    /**
     * جلب كافة المواعيد في النظام مع دمج بيانات الطبيب والمريض والتخصص
     * (مهم جداً للوحة تحكم المدير لعرض جدول شامل)
     */
    public function getAllAppointments(): array {
        $sql = "SELECT 
                    a.id, 
                    a.appt_date, 
                    a.appt_time, 
                    a.status, 
                    a.reason,
                    p.name AS patient_name, 
                    d_user.name AS doctor_name,
                    s.name AS specialization_name
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN doctors d ON a.doctor_id = d.id
                JOIN users d_user ON d.user_id = d_user.id
                JOIN specializations s ON d.specialization_id = s.id
                ORDER BY a.appt_date DESC, a.appt_time ASC";
                
        $result = $this->execute($sql);
        $appointments = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }

    /**
     * ميزة حجز موعد جديد ومنع الحجز المزدوج داتابيز وبرمجياً
     */
    public function bookAppointment(int $patientId, int $doctorId, string $date, string $time, string $reason): bool {
        //  التحقق أولاً من عدم وجود حجز لنفس الطبيب في نفس الوقت (Double Booking Protection)
        $checkSql = "SELECT id FROM appointments WHERE doctor_id = ? AND appt_date = ? AND appt_time = ? LIMIT 1";
        $checkResult = $this->execute($checkSql, "iss", [$doctorId, $date, $time]);
        
        if ($checkResult && $checkResult->num_rows > 0) {
            return false; // الطبيب مشغول في هذا الوقت
        }

        //  إدخال الموعد الجديد إن كان الوقت متاحاً
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appt_date, appt_time, reason) VALUES (?, ?, ?, ?, ?)";
        return $this->execute($sql, "iisss", [$patientId, $doctorId, $date, $time, $reason]);
    }

    /**
     * جلب المواعيد الخاصة بطبيب محدد فقط عبر معرف المستخدم الخاص به (user_id)
     * (مهمة جداً لبوابة الطبيب وعرض جدول العمل الشخصي له)
     */
    public function getDoctorAppointments(int $userId): array {
        $sql = "SELECT 
                    a.id, 
                    a.appt_date, 
                    a.appt_time, 
                    a.status, 
                    a.reason,
                    p.name AS patient_name,
                    p.phone AS patient_phone
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN doctors d ON a.doctor_id = d.id
                WHERE d.user_id = ?
                ORDER BY a.appt_date DESC, a.appt_time ASC";
                
        $result = $this->execute($sql, "i", [$userId]);
        $appointments = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }

    /**
     *  جلب كافة المواعيد والروشتات الخاصة بمريض محدد فقط عبر معرفه (patient_id)
     * (يتم ربط جدول المواعيد بجدول الروشتات بعلاقة LEFT JOIN لعرض زر الطباعة إن وُجدت روشتة)
     */
    public function getPatientAppointments(int $patientId): array {
        $sql = "SELECT 
                    a.id AS appt_id, 
                    a.appt_date, 
                    a.appt_time, 
                    a.status, 
                    a.reason,
                    d_user.name AS doctor_name,
                    s.name AS specialization_name,
                    pr.id AS prescription_id
                FROM appointments a
                JOIN doctors d ON a.doctor_id = d.id
                JOIN users d_user ON d.user_id = d_user.id
                JOIN specializations s ON d.specialization_id = s.id
                LEFT JOIN prescriptions pr ON pr.appointment_id = a.id
                WHERE a.patient_id = ?
                ORDER BY a.appt_date DESC, a.appt_time ASC";
                
        $result = $this->execute($sql, "i", [$patientId]);
        $appointments = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }
}

