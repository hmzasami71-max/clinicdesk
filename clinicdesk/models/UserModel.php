<?php
require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel {

    // البحث عن مستخدم بواسطة المعرف ID
    public function findById(int $id): ?array {
        $sql = "SELECT id, name, email, role, phone, avatar, is_active, created_at FROM users WHERE id = ? LIMIT 1";
        $result = $this->execute($sql, "i", [$id]);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // البحث عن مستخدم بواسطة البريد الإلكتروني (مهم لعملية تسجيل الدخول)
    public function findByEmail(string $email): ?array {
        $sql = "SELECT id, name, email, password, role, is_active FROM users WHERE email = ? LIMIT 1";
        $result = $this->execute($sql, "s", [$email]);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // تفعيل أو تعطيل حساب المستخدم (الحظر والنشاط)
    public function toggleActive(int $id): bool {
        $sql = "UPDATE users SET is_active = NOT is_active WHERE id = ?";
        return $this->execute($sql, "i", [$id]);
    }
}
