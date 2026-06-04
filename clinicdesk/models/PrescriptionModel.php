<?php
require_once __DIR__ . '/BaseModel.php';

class PrescriptionModel extends BaseModel {

    /**
     * حفظ وصفة طبية جديدة مع دعم إضافة مسار المرفق الطبي (file_path)
     */
    public function createPrescription(int $appointmentId, string $diagnosis, string $medications, ?string $notes = null, ?string $filePath = null): bool {
        $sql = "INSERT INTO prescriptions (appointment_id, diagnosis, medications, notes, file_path) VALUES (?, ?, ?, ?, ?)";
        return $this->execute($sql, "issss", [$appointmentId, $diagnosis, $medications, $notes, $filePath]);
    }

    /**
     * جلب تفاصيل وصفة معينة مع بيانات الطبيب والمريض ورابط الملف المرفق
     */
    public function getPrescriptionDetails(int $prescriptionId): ?array {
        $sql = "SELECT 
                    pr.id AS prescription_id, pr.diagnosis, pr.medications, pr.notes, pr.file_path, pr.created_at,
                    a.appt_date,
                    p.name AS patient_name, p.phone AS patient_phone,
                    d_user.name AS doctor_name, s.name AS specialization_name
                FROM prescriptions pr
                JOIN appointments a ON pr.appointment_id = a.id
                JOIN users p ON a.patient_id = p.id
                JOIN doctors d ON a.doctor_id = d.id
                JOIN users d_user ON d.user_id = d_user.id
                JOIN specializations s ON d.specialization_id = s.id
                WHERE pr.id = ? LIMIT 1";
                
        $result = $this->execute($sql, "i", [$prescriptionId]);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}

