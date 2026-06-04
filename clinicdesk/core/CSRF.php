<?php

class CSRF {
    // توليد رمز عشوائي آمن وحفظه في الـ Session
    public static function generateToken() {
        if (empty($_SESSION['csrf_token'])) {
            // توليد 32 بايت عشوائي وتحويله لنظام سداسي عشر
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // التحقق من الرمز المرسل ومقارنته بالرمز المخزن في الـ Session بأمان
    public static function validateToken(string $token): bool {
        if (!isset($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        // استخدام hash_equals لمنع هجمات التوقيت المعتمدة على المقارنة (Timing Attacks)
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}
