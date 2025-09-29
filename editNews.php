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
   ->prepare("SELECT * FROM categories ORDER BY name");
    $stmt->execute();
    $categories = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Error: die("Error: " . $e " . $e->get->getMessage());
}

//Message());
}

// جلب جلب بيانات الخبر الحالية بيانات الخبر الحالية
if
if (isset($_ (isset($_GET['id'])) {
    $news_id = $_GET['id'])) {
    $news_id = $_GET['GET['id'];
id'];
    
    
    try {
        $stmt = $p    try {
        $stmt = $pdo->prepare("do->prepare("
            SELECT n.*, c.name as category_name
            SELECT n.*, c.name as category_name 
            FROM 
            FROM news n 
            LEFT JOIN categories c ON n news n 
            LEFT JOIN categories c ON n.category_id = c.category_id = c.id 
           .id 
            WHERE WHERE n.id = ? n.id = ? AND n AND n.is_deleted = 0
       .is_deleted = 0
        ");
        ");
        $stmt-> $stmt->execute([execute([$news_id$news_id]);
       ]);
        $news $news = = $stmt $stmt->fetch();
->fetch();
        
        
        if        if (!$news) {
            $error = 'الخبر (!$news) {
            $error = 'الخبر غير موجود أو تم حذفه';
        }
 غير موجود أو تم حذفه';
        }
    } catch(    } catch(PDOException $PDOException $ee) {
        die(") {
        die("Error: " .Error: " . $e->get $e->getMessageMessage());
    }
} else {
    $());
    }
} else {
    $error =error = 'مع 'معرف الخرف الخبر غير محبر غير محدد';
}

دد';
}

if ($_if ($_SERVER['SERVER['REQUEST_METHODREQUEST_METHOD'] == 'POST') {
   '] == 'POST') {
    $ $title = trim($_POST['title']);
    $title = trim($_POST['title']);
    $categorycategory_id =_id = $_POST['category_id'];
 $_POST['category_id'];
    $details = trim($_    $details = trim($_POST['details']);
POST['details']);
    
    if (    
    if (empty($title)empty($title) || empty($category_id) || empty($category_id) || empty($details || empty($details)) {
        $error = 'جم)) {
        $error = 'جميع الحقول ميع الحقول مطلوبة';
   طلوبة';
    } } else else {
        try {
            // {
        try {
            // التعامل مع ر التعامل مع رفع الصفع الصورة الجديدة
            $ورة الجديدة
            $imageimage_name = $_name = $news['image'];
news['image'];
            if            if (isset($_ (isset($FILES['image'])FILES['image']) && $ && $_FILESFILES['image']['error['image']['error'] == 0) {
'] == 0) {
                               $allowed_types = $allowed_types = [' ['image/jimage/jpeg', 'image/ppeg', 'image/png', 'image/gifng', 'image/gif'];
                $file'];
                $file_type = $_FILES['image']['_type = $_FILES['image']['type'];
                
                if (type'];
                
                if (in_arrayin_array($file_type, $allowed($file_type, $allowed_types)) {
                   _types)) {
                    // حذف // حذف الصورة القديمة إذا كانت الصورة القديمة إذا كانت موجودة
                    موجودة
                    if ($image_name if ($image_name && file && file_exists('uploads/'_exists('uploads/' . $image_name . $image_name)) {
)) {
                        un                        unlink('link('uploads/' .uploads/' . $ $image_name);
image_name);
                    }
                    
                    $                    }
                    
                    $image_nameimage_name = uniqid = uniqid() .() . '' . $_FILES '' . $_FILES['image']['name'];
                   ['image']['name'];
                    $ $upload_path = 'uploads/' . $image_nameupload_path = 'uploads/' . $image_name;
;
                    
                    if (!                    
                    if (!is_dir('is_dir('uploads')) {
uploads')) {
                        mkdir('uploads',                        mkdir('uploads',  0755, true0755, true);
                    }
                    
                    move);
                    }
                    
                    move_uploaded_file_uploaded_file($_FILES['image($_FILES['image']['tmp']['tmp_name'], $upload_name'], $upload_path);
                }_path);
                } else {
 else {
                    $error = 'نوع                    $error = 'نوع الملف غير مسمو الملف غير مسموح به. المسح به. المسموح: Jموح: JPG,PG, PNG, GIF';
 PNG, GIF';
                }
            }
                }
            }
            
            
            if (!$            if (!$error)error) {
                {
                $stmt = $stmt = $p $pdo->preparedo->prepare("UPDATE("UPDATE news SET title = news SET title = ?, category_id = ?, category_id = ?, details = ?, ?, details = ?, image = ? image = ? WHERE id WHERE id = ?");
 = ?");
                $                $stmt->stmt->executeexecute([$title, $([$title, $category_idcategory_id, $, $details,details, $image_name, $image_name, $news $news_id]);
_id]);
                $success = '                $success = 'تمتم تعديل الخبر تعديل الخبر بن بنجاح!جاح!';
                
                //';
                
                // تحديث تحديث بيانات الخبر بيانات الخبر المع المعروضة
روضة
                $stmt = $pdo                $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
               ->prepare("SELECT * FROM news WHERE id = ?");
                $ $stmt->executestmt->execute([$news_id]);
([$news_id]);
                               $news = $ $news = $stmtstmt->fetch();
           ->fetch();
            }
        } catch(PDOException }
        } catch(PDOException $e) $e) {
            $error = {
            $error = 'حدث 'حدث خطأ أثناء تعد خطأ أثناء تعديل الخيل الخبر:بر: ' . ' . $e-> $e->getgetMessage();
        }
    }
Message();
        }
    }
}
?>

<!DOCTYPE html>
<html lang}
?>

<!DOCTYPE html>
<html lang="ar="ar" dir" dir="rtl="rtl">
<head>
">
<head>
    <meta    <meta charset charset="UTF-="UTF-8">
   8">
    <meta name="viewport" content <meta name="viewport" content="width=device-width="width=device-width,, initial initial-scale-scale==1.0">
   1.0">
    <title <title>تعديل خبر - نظام إ>تعديل خبر - نظام إدارةدارة الأخبار</title الأخبار</title>
    <link>
    <link rel="stylesheet" href rel="stylesheet" href="="style.css">
</style.css">
</head>
head>
<body>
   <body>
    <header>
        <div class <header>
        <div class="container">
            <div="container">
            <div class="header-content class="header-content">
               ">
                <div class="logo">نظام <div class="logo">نظام إدارة الأخبار</div>
                <nav>
                    <ul إدارة الأخبار</div>
                <nav>
                    <ul>
                       >
                        <li <li><a href="><a href="dashboard.php">لوdashboard.php">لوحة التححة التحكم</aكم</a></li></li>
                       >
                        <li>< <li><a href="viewa href="viewNews.php">عرض.php">عرض الأخبار الأخبار</a</a></li></li>
>
                                               <li><a href="addNews <li><a href="addNews.php">إضافة.php">إضافة خبر</ خبر</a></li>
                        <a></li>
                        <lili><a href="logout.php">ت><a href="logout.php">تسجيل الخروجسجيل الخروج (<?php echo $_SESSION (<?php echo $_SESSION['user_name'];['user_name']; ?>)</a></li ?>)</a></li>
                   >
                    </ul </ul>
                </nav>
                </nav>
            </div>
>
            </div>
        </div        </div>
    </header>

    <div class="container>
    </header>

    <div class="container">
">
        <div        <div class="main-content">
            <aside class="sidebar">
                <h class="main-content">
            <aside class="sidebar">
                <h3>القائمة3>القائمة الرئيسية الرئيسية</h3</h3>
>
                <                <ul>
                    <li><aul>
                    <li><a href href="addCategory.php">إضافة ف="addCategory.php">إضافة فئة</a></li>
                    <li><a href="vieCategories.php">ئة</a></li>
                    <li><a href="viewCategories.php">عرض الفئات</عرض الفئات</a></a></lili>
                    <li><a>
                    <li><a href="addNews href="add_news.php">إضافة.php">إضافة خبر</a></li خبر</a></li>
                    <li>
                    <li><a href="><a href="viewNews.php">عرض جميعviewNews.php">عرض جميع الأخبار</a></li>
                    <li>< الأخبار</a></li>
                    <li><a href="view_dea href="view_deleted_leted_news.php">news.php">عرض الأخبار المحذوفة</a></li>
                </ul>
            </عرض الأخبار المحذوفة</a></li>
                </ul>
            </aside>

           aside>

            <main class="content">
                <main class="content">
                <h2 class <h2 class="page-title">="page-title">تعديل خبرتعديل خبر</h2>
                
</h2>
                
                               <?php if ($error): ?>
                    <div class="alert alert-error"><?php <?php if ($error): ?>
                    <div class="alert alert-error"> echo<?php echo $error; ?></div>
                <? $error; ?></div>
                <?phpphp endif; ?>
                
 endif; ?>
                
                <?                <?php if ($successphp if ($success): ?>
): ?>
                    <div class="alert                    <div class="alert alert-success alert-success"><?php echo"><?php echo $success; ?></ $success; ?></div>
                <?div>
                <?php endifphp endif; ?>
                
; ?>
                
                               <?php if ( <?php if (isset($news)isset($news) && $news): ?>
 && $news): ?>
                               <form method=" <form method="POSTPOST"" action="" enctype="multipart/form-data">
                    <div class="form action="" enctype="multipart/form-data">
                    <div class="form-group">
                       -group">
                        <label for="title"> <label for="title">عنوان الخبر:</عنوان الخبر:</label>
                       label>
                        <input type=" <input type="text" id="titletext" id="title"" name="title name="title" class="form-control" required value" class="form-control" required value="<?="<?php echo htmlspecialchars($news['titlephp echo htmlspecialchars($news['title']);']); ?>">
 ?>">
                    </div                    </div>
                    
                   >
                    
                    <div <div class="form-group class="form-group">
">
                        <label for="category_id                        <label for="category_id">الفئة:</label>
">الفئة:</label>
                                               < <select id="category_id"select id="category_id" name name="="category_idcategory_id" class="form" class="form-control-control" required>
                           " required>
                            <option value="">اختر <option value="">اختر الف الفئة</option>
                            <?ئة</option>
                            <?php foreach ($categories as $php foreach ($categories as $category): ?>
category): ?>
                            <option                            <option value="<? value="<?php echophp echo $category $category['id['id']; ?>" <?php echo']; ?>" <?php echo ($news[' ($news['category_id'] == $category['id']) ?category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                <? 'selected' : ''; ?>>
                                <?phpphp echo htmlspecialchars echo htmlspecialchars($category['name']);($category['name']); ?>
                            </option ?>
                            </option>
                            <?php>
                            <?php endforeach; ?>
 endforeach; ?>
                        </select>
                    </                        </select>
                    </div>
                    
                   div>
                    
                    <div class=" <div class="form-group">
                        <form-group">
                       label for="details"> <label for="details">تفاصيل الختفاصيل الخبر:</بر:</labellabel>
                        <textarea id>
                        <textarea id="="details" name="details" classdetails" name="details" class="="form-control" required><?php echoform-control" required><?php echo htmlspecial htmlspecialchars($news['details']); ?></textarea>
chars($news['details']); ?></textarea>
                    </                    </div>
                    
                   div>
                    
                    <div class=" <div class="form-groupform-group">
                       ">
                        <label for=" <label for="image">صورة الخبر الحالية:</label>
image">صورة الخبر الحالية:</label>
                        <?php if                        <?php if ($news['image ($news['image']):']): ?>
                            ?>
                            <div <div>
                                <img>
                                <img src="uploads/ src="uploads/<?<?php echo $php echo $newsnews['image']; ?>"['image']; ?>" alt="صورة alt="صورة الخبر" style=" الخبر" style="maxmax-width: -width: 200200px; margin-bottompx; margin-bottom: : 10px;">
                               10px;">
                                <br>
                            <br>
                            </div>
                        </div>
                        <?php else <?php else: ?>
: ?>
                            <                            <p>p>لا توجد صلا توجد صورة</ورة</p>
p>
                        <?                        <?php endifphp endif; ?>
; ?>
                        
                                               
                        <label for="image">تغي <label for="image">تغيير الصورة (اختياريير الصورة (اختياري):</label>
                        <input type="file" id):</label>
                        <input type="file" id="image" name="image" name="image="image" class="form" class="form-control" accept="-control" accept="imageimage//">
                    </div>
                    
                   ">
                    </div>
                    
                    <button type="submit <button type="submit" class="btn" class="btn btn-success btn-success">ح">حفظ التفظ التعديلاتعديلات</button>
</button>
                    <a href="view                    <a href="view_news.php" class_news.php" class="="btn">btn">عودة لقائمة الأخبارعودة لقائمة الأخبار</a</a>
                </>
                </form>
                <?php endifform>
                <?php endif; ?>
            </main; ?>
            </main>
>
        </        </div>
    </div>

div>
    </div>

    <footer>
    <footer>
        <div class="container        <div class="container">
            <p">
            <p>نظام إ>نظام إدارة الأخبار &دارة الأخبار &copy; 2023</p>
        </copy; 2023</p>
        </div>
    </footerdiv>
    </footer>
>
</body>
</html</body>
</html>