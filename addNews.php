<?php
require_once 'config.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

// جلب الفئات للقائمة المنسدلة
try {
    $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY name");
    $stmt->execute();
    $categories = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $category_id = $_POST['category_id'];
    $details = trim($_POST['details']);
    $user_id = $_SESSION['user_id'];
    
    if (empty($title) || empty($category_id) || empty($details)) {
        $error = 'جميع الحقول مطلوبة';
    } else {
        try {
            // التعامل مع رفع الصورة
            $image_name = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $file_type = $_FILES['image']['type'];
                
                if (in_array($file_type, $allowed_types)) {
                    $image_name = uniqid() . '_' . $_FILES['image']['name'];
                    $upload_path = 'uploads/' . $image_name;
                    
                    if (!is_dir('uploads')) {
                        mkdir('uploads', 0755, true);
                    }
                    
                    move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
                } else {
                    $error = 'نوع الملف غير مسموح به. المسموح: JPG, PNG, GIF';
                }
            }
            
            if (!$error) {
                $stmt = $pdo->prepare("INSERT INTO news (title, category_id, details, image, user_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $category_id, $details, $image_name, $user_id]);
                $success = 'تم إضافة الخبر بنجاح!';
                
                // مسح بيانات النموذج بعد الإضافة الناجحة
                $_POST = array();
            }
        } catch(PDOException $e) {
            $error = 'حدث خطأ أثناء إضافة الخبر: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة خبر - نظام إدارة الأخبار</title>
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
                <h2 class="page-title">إضافة خبر جديد</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">عنوان الخبر:</label>
                        <input type="text" id="title" name="title" class="form-control" required value="<?php echo isset($_POST['title']) ? $_POST['title'] : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">الفئة:</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">اختر الفئة</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="details">تفاصيل الخبر:</label>
                        <textarea id="details" name="details" class="form-control" required><?php echo isset($_POST['details']) ? $_POST['details'] : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">صورة الخبر (اختياري):</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    </div>
                    
                    <button type="submit" class="btn btn-success">إضافة الخبر</button>
                    <a href="viewNews.php" class="btn">عرض الأخبار</a>
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