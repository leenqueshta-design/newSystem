<?php
require_once 'config.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// جلب الأخبار المحذوفة فقط
try {
    $stmt = $pdo->prepare("
        SELECT n.*, c.name as category_name, u.name as user_name 
        FROM news n 
        LEFT JOIN categories c ON n.category_id = c.id 
        LEFT JOIN users u ON n.user_id = u.id 
        WHERE n.is_deleted = 1 
        ORDER BY n.created_at DESC
    ");
    $stmt->execute();
    $deleted_news = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}

// استعادة خبر محذوف
if (isset($_GET['restore_id'])) {
    $restore_id = $_GET['restore_id'];
    
    try {
        $stmt = $pdo->prepare("UPDATE news SET is_deleted = 0 WHERE id = ?");
        $stmt->execute([$restore_id]);
        
        $_SESSION['success'] = 'تم استعادة الخبر بنجاح!';
        header('Location: viewDeletedNews.php');
        exit();
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الأخبار المحذوفة - نظام إدارة الأخبار</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">نظام إدارة الأخبار</div>
                <nav>
                    <ul>
                        <li><a href="dashboard.php">لوحة التحكم</a></li>
                        <li><a href="viewNews.php">عرض الأخبار</a></li>
                        <li><a href="addNews.php">إضافة خبر</a></li>
                        <li><a href="logout.php">تسجيل الخروج</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="main-content">
            <aside class="sidebar">
                <h3>القائمة الرئيسية</h3>
                <ul>
                    <li><a href="addCategory.php">إضافة فئة</a></li>
                    <li><a href="viewCategories.php">عرض الفئات</a></li>
                    <li><a href="addNews.php">إضافة خبر</a></li>
                    <li><a href="viewNews.php">عرض جميع الأخبار</a></li>
                    <li><a href="viewDeletedNews.php">عرض الأخبار المحذوفة</a></li>
                </ul>
            </aside>

            <main class="content">
                <h2 class="page-title">عرض الأخبار المحذوفة</h2>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                
                <?php if (count($deleted_news) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان الخبر</th>
                                <th>الفئة</th>
                                <th>المستخدم</th>
                                <th>تاريخ الحذف</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deleted_news as $index => $item): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($item['title']); ?></td>
                                <td><?php echo htmlspecialchars($item['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['user_name']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($item['created_at'])); ?></td>
                                <td class="actions">
                                    <a href="viewDeletedNews.php?restore_id=<?php echo $item['id']; ?>" class="btn btn-success" onclick="return confirm('هل أنت متأكد من استعادة هذا الخبر؟')">استعادة</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>لا توجد أخبار محذوفة.</p>
                <?php endif; ?>
                
                <div style="margin-top: 20px;">
                    <a href="viewNews.php" class="btn">عودة لقائمة الأخبار</a>
