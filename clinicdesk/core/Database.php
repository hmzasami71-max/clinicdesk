<?php

class Database {
    // كائن تخزين المثيل الوحيد للكلاس
    private static $instance = null;
    // كائن الاتصال بـ mysqli
    private $conn;

    // بناية خاصة (Private Constructor) لمنع إنشاء الكلاس عبر الـ new من الخارج
    private function __construct() {
        // تضمين ملف بيانات الاتصال
        require_once __DIR__ . '/../config/database.php';

        // محاولة الاتصال بقاعدة البيانات
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // التحقق من نجاح الاتصال وإلقاء خطأ آمن عند الفشل
        if ($this->conn->connect_error) {
            throw new RuntimeException("Database connection failed. Please contact the administrator.");
        }

        $this->conn->set_charset("utf8mb4");
    }

    // الدالة الثابتة للحصول على المثيل الوحيد (Singleton Accessor)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * دالة عامة لتنفيذ الاستعلامات المجهزة (Prepared Statements) بأمان
     * 
     * @param string $sql استعلام الاسكيوال ويحتوي على علامات استفهام ? مكان المتغيرات
     * @param string $types أنواع البيانات المرسلة مثل "ssi" (s=string, i=integer)
     * @param array $params المصفوفة المحتوية على القيم الفعلية للمتغيرات
     */
    public function query(string $sql, string $types = "", array $params = []) {
        // تجهيز الاستعلام في السيرفر
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        // ربط المتغيرات بالاستعلام إن وجدت باستخدام الـ Spread Operator (...)
        if (!empty($types) && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        // تنفيذ الاستعلام
        $execute = $stmt->execute();

        // إذا كان الاستعلام من نوع SELECT، قم بإرجاع النتيجة الفعلية للبيانات
        if (stripos($sql, 'SELECT') === 0) {
            $result = $stmt->get_result();
            $stmt->close();
            return $result; // يعيد كائن mysqli_result
        }

        // للاستعلامات الأخرى (INSERT, UPDATE, DELETE) يعيد true أو false حسب النجاح
        $stmt->close();
        return $execute;
    }

    // دالة للحصول على آخر معرف (ID) تم إنشاؤه تلقائياً في قاعدة البيانات
    public function lastInsertId() {
        return $this->conn->insert_id;
    }

    // منع نسخ الكائن بأي شكل من الأشكال
    private function __clone() {}
}
