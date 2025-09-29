<?php
require_once 'config.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// جلب جميع الأخبار غير المحذوفة
try {
    $stmt = $pdo->prepare("
        SELECT n.*, c.name as category_name, u.name as user_name 
        FROM news n 
        LEFT JOIN categories c ON n.category_id = c.id 
        LEFT JOIN users u ON n.user_id = u.id 
        WHERE n.is_deleted = 0 
        ORDER BY n.created_at DESC
    ");
    $stmt->execute();
    $news = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الأخبار - نظام إدارة الأخبار</title>
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
                <h2 class="page-title">عرض الأخبار</h2>
                
                <?php if (count($news) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان الخبر</th>
                                <th>الفئة</th>
                                <th>المستخدم</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($news as $index => $item): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($item['title']); ?></td>
                                <td><?php echo htmlspecialchars($item['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['user_name']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($item['created_at'])); ?></td>
                                <td class="actions">
                                    <a href="editNews.php?id=<?php echo $item['id']; ?>" class="btn">تعديل</a>
                                    <a href="deleteNews.php?id=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الخبر؟')">حذف</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>لا توجد أخبار مضافة بعد. <a href="addNews.php">أضف خبر جديد</a></p>
                <?php endif; ?>
                
                <div style="margin-top: 20px;">
                    <a href="addNews.php" class="btn btn-success">إضافة خبر جديد</a>
                    <a href="dashboard.php" class="btn">العودة للوحة التحكم</a>
                </div>
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