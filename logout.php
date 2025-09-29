<?php
// التحقق من وجود ملف config.php
if (!file_exists('config.php')) {
    die('خطأ: ملف config.php غير موجود في المجلد');
}

require_once 'config.php';

// التحقق من وجود جلسة نشطة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// مسح جميع بيانات الجلسة
$_SESSION = array();

// حذف كوكي الجلسة
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// إنهاء الجلسة
session_destroy();

// التوجيه إلى صفحة تسجيل الدخول
header('Location: login.php');
exit();
?>