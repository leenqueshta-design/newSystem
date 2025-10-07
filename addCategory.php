<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    
    if (empty($name)) {
        $error = 'اسم الفئة مطلوب';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
            $stmt->execute([$name]);
            
            if ($stmt->rowCount() > 0) {
                $error = 'هذه الفئة موجودة بالفعل';
            } else {
                $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
                $stmt->execute([$name]);
                $success = 'تم إضافة الفئة بنجاح!';
            }
        } catch(PDOException $e) {
            $error = 'حدث خطأ أثناء إضافة الفئة: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة فئة - نظام إدارة الأخبار</title>
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
                <h2 class="page-title">إضافة فئة جديدة</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">اسم الفئة:</label>
                        <input type="text" id="name" name="name" class="form-control" required value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-success">إضافة الفئة</button>
                    <a href="viewCategories.php" class="btn">عرض الفئات</a>
                </form>
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
