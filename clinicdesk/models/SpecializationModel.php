<?php
require_once __DIR__ . '/BaseModel.php';

class SpecializationModel extends BaseModel {

    // 1. جلب جميع التخصصات الطبية
    public function getAll(): array {
        $sql = "SELECT id, name FROM specializations ORDER BY name ASC";
        $result = $this->execute($sql);
        $specs = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) { $specs[] = $row; }
        }
        return $specs;
    }

    // 2. إضافة تخصص طبي جديد
    public function add(string $name): bool {
        $sql = "INSERT INTO specializations (name) VALUES (?)";
        return $this->execute($sql, "s", [$name]);
    }

    // 3. حذف تخصص طبي بواسطة الـ ID
    public function delete(int $id): bool {
        // نستخدم حماية داتابيز لأن الجدول مرتبط بالأطباء برابط RESTRICT يمنع الحذف لو هناك طبيب مسجل به
        $sql = "DELETE FROM specializations WHERE id = ?";
        return $this->execute($sql, "i", [$id]);
    }
}
