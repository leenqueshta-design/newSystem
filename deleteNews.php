## 12. ملف delete_news.php ( (حذف خبر)
حذف خبر)
php
<?php
php
<?php
require_once 'configrequire_once 'config.php';

// التح.php';

// التحقق من تسجيلقق من تسجيل الد الدخول
if (!خول
if (!isset($_SESSION['isset($_SESSION['user_id']))user_id'])) {
 {
    header('Location    header('Location: login: login.php');
.php');
    exit    exit();
}

if (();
}

if (issetisset($_GET['($_GET['id']))id'])) {
    $news_id = $_GET['id {
    $news_id = $_GET['id'];
    
   '];
    
    try {
        // حذف منطقي (تغيير try {
        // حذف منطقي (تغيير حالة is_deleted إلى 1)
        $stmt = $pdo-> حالة is_deleted إلى 1)
        $stmt = $pdo->prepare("UPDATE news SET isprepare("UPDATE news SET is_deleted = 1 WHERE_deleted = 1 WHERE id = ?");
        $stmt-> id = ?");
        $stmt->executeexecute([$([$news_id]);
        
        $_SESSION['success']news_id]);
        
        $_SESSION['success'] = = 'تم حذ 'تم حذف الخبر بنجاحف الخبر بنجاح!!';
    }';
    } catch(PDOException $e) catch(PDOException $e) {
        $_SESSION[' {
        $_SESSIONerror'] = 'حدث خط['error'] = 'حدث خطأ أثناء حذأ أثناء حذفف الخبر: ' الخبر: ' . $e-> . $e->getMessage();
   getMessage();
    }
 }
    
    header('Location    
    header('Location: viewNews: viewNews.php.php');
    exit');
    exit();
}();
} else {
    header else {
    header('Location: view('Location: viewNews.php');
_news.php');
    exit    exit();
}
();
}
?>