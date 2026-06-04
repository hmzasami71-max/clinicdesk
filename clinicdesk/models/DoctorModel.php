<?php
require_once __DIR__ . '/BaseModel.php';

class DoctorModel extends BaseModel {

    /**
     * جلب جميع الأطباء مع تخصصاتهم وبيانات حساباتهم الأساسية
     */
    public function getAllDoctors(): array {
        $sql = "SELECT 
                    d.id AS doctor_id, 
                    u.id AS user_id,
                    u.name, 
                    u.email, 
                    u.phone, 
                    u.is_active,
                    s.name AS specialization_name, 
                    d.consultation_fee
                FROM doctors d
                JOIN users u ON d.user_id = u.id
                JOIN specializations s ON d.specialization_id = s.id
                ORDER BY u.name ASC";
                
        $result = $this->execute($sql);
        $doctors = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $doctors[] = $row;
            }
        }
        return $doctors;
    }

    /**
     * إضافة طبيب جديد (تتطلب إدخال في جدول المستخدمين أولاً ثم جدول الأطباء)
     */
    public function addDoctor(string $name, string $email, string $password, string $phone, int $specId, float $fee): bool {
        // تشفير كلمة مرور الطبيب الجديد بأمان
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        
        // 1. إدخال الحساب في جدول المستخدمين برتبة 'doctor'
        $sqlUser = "INSERT INTO users (name, email, password, role, phone) VALUES (?, ?, ?, 'doctor', ?)";
        $userInserted = $this->execute($sqlUser, "ssss", [$name, $email, $hashedPassword, $phone]);
        
        if (!$userInserted) {
            return false;
        }

        // جلب معرف المستخدم (user_id) الذي تم إنشاؤه للتو
        $userId = $this->db->lastInsertId();

        // 2. إدخال تفاصيل الطبيب في جدول الأطباء وربطه بالـ user_id
        $sqlDoctor = "INSERT INTO doctors (user_id, specialization_id, consultation_fee) VALUES (?, ?, ?)";
        return $this->execute($sqlDoctor, "iid", [$userId, $specId, $fee]);
    }
}
