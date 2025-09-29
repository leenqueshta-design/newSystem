<?php
require_once 'config.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// إحصائيات النظام
try {
    // عدد الأخبار
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM news WHERE is_deleted = 0");
    $stmt->execute();
    $news_count = $stmt->fetchColumn();
    
    // عدد الفئات
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories");
    $stmt->execute();
    $categories_count = $stmt->fetchColumn();
    
    // عدد الأخبار المحذوفة
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM news WHERE is_deleted = 1");
    $stmt->execute();
    $deleted_news_count = $stmt->fetchColumn();
    
    // آخر الأخبار
    $stmt = $pdo->prepare("
        SELECT n.*, c.name as category_name, u.name as user_name 
        FROM news n 
        LEFT JOIN categories c ON n.category_id = c.id 
        LEFT JOIN users u ON n.user_id = u.id 
        WHERE n.is_deleted = 0 
        ORDER BY n.created_at DESC 
        LIMIT 5
    ");
    $stmt->execute();
    $recent_news = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام إدارة الأخبار</title>
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
                        <li><a href="logout.php">تسجيل الخروج (<?php echo $_SESSION['user_name']; ?>)</a></li>
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
                <h2 class="page-title">لوحة التحكم</h2>
                <p>مرحباً <?php echo $_SESSION['user_name']; ?>! هذه نظرة عامة على نظام إدارة الأخبار.</p>
                
                <div class="stats">
                    <div class="stat-card">
                        <h3>عدد الأخبار</h3>
                        <div class="stat-number"><?php echo $news_count; ?></div>
                    </div>
                    <div class="stat-card" style="background: #2ecc71;">
                        <h3>عدد الفئات</h3>
                        <div class="stat-number"><?php echo $categories_count; ?></div>
                    </div>
                    <div class="stat-card" style="background: #e74c3c;">
                        <h3>أخبار محذوفة</h3>
                        <div class="stat-number"><?php echo $deleted_news_count; ?></div>
                    </div>
                </div>
                
                <h3 style="margin-top: 30px;">آخر الأخبار المضافة</h3>
                <?php if (count($recent_news) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العنوان</th>
                                <th>الفئة</th>
                                <th>تاريخ الإضافة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_news as $index => $news): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($news['title']); ?></td>
                                <td><?php echo htmlspecialchars($news['category_name']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($news['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>لا توجد أخبار مضافة بعد.</p>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>نظام إدارة الأخبار &copy; 2023</p>
        </div>
    </footer>
</body>
</html>