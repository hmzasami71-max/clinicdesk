<?php

class Auth {
    // تسجيل بيانات المستخدم في الجلسة وتجديد المعرف لحمايته من هجمات الاختطاف
    public static function login(array $user) {
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'role'  => $user['role']
        ];
        // توليد معرف جلسة جديد كلياً لحماية المستخدم 
        session_regenerate_id(true);
    }

    // تسجيل الخروج وتدمير الجلسة بالكامل
    public static function logout() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        // إعادة التوجيه الفورية لصفحة تسجيل الدخول
        header("Location: " . BASE_URL . "index.php?page=auth&action=login");
        exit();
    }

    // التحقق هل المستخدم سجل دخوله أم لا
    public static function check(): bool {
        return isset($_SESSION['user']);
    }

    // جلب بيانات المستخدم الحالي المفرزة بالجلسة
    public static function currentUser(): ?array {
        return $_SESSION['user'] ?? null;
    }

    // جلب دور/رتبة المستخدم الحالي
    public static function role(): string {
        return $_SESSION['user']['role'] ?? '';
    }

    /**
     * جدار الحماية البرمجي الأساسي يستدعى في أعلى كل صفحة متحكم)
     * لمنع الوصول غير المصرح به وإرجاع صفحة الخطأ 403 أو التوجيه لصفحة اللوجن
     */
    public static function requireRole(string ...$roles) {
        // إذا لم يكن مسجلاً، وجهه لصفحة تسجيل الدخول
        if (!self::check()) {
            header("Location: " . BASE_URL . "index.php?page=auth&action=login");
            exit();
        }

        // إذا كانت رتبته خارج رتب المسموح لهم بالدخول، يتم حظره وإرساله لـ 403
        if (!in_array(self::role(), $roles)) {
            header("Location: " . BASE_URL . "index.php?page=errors&action=403");
            exit();
        }
    }
}
