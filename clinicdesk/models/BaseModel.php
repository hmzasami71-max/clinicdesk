<?php
require_once __DIR__ . '/../core/Database.php';

abstract class BaseModel {
    protected $db;

    public function __construct() {
        // جلب المثيل الوحيد لقاعدة البيانات المعتمد على نمط الـ Singleton
        $this->db = Database::getInstance();
    }

    /**
     * تمرير الاستعلامات وتنفيذها مركزياً لتسهيل إدارة الأخطاء في مكان واحد
     */
    protected function execute(string $sql, string $types = "", array $params = []) {
        return $this->db->query($sql, $types, $params);
    }
}
