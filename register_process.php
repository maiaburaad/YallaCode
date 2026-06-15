<?php
session_start();
require_once __DIR__ . '/database.php';

// اول خطوة نتصل بالداتا بيس
try {
    $conn = getDatabaseConnection();
}
catch(Throwable $e) {  // لو فشل الاتصال اطبعلي هيك
    die("Database connection failed:" . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {    // اليوزر عمل سبمت ؟

    // التحقق من الصورة
    if (empty($_FILES['photo']['name']) || $_FILES['photo']['error'] != 0) {  
        echo "<script>
                alert('Sorry! A profile picture is required to complete the registration');
                window.history.back();
              </script>";
        exit(); 
    }

    // استقبال البيانات وتنظيفها
    $fullname = htmlspecialchars($_POST['fullname']);
    $email    = htmlspecialchars($_POST['email']);
    $password_input = $_POST['password'];



    //  استقبال الرتبة (إذا الأدمن هو اللي بضيف، رح يكون في قيمة، غير هيك بتضل user)
    $role = isset($_POST['role']) ? $_POST['role'] : 'user';

    $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

    $photoName = $_FILES['photo']['name'];  // الاسم الاصلي للصورة
    $photoTmp  = $_FILES['photo']['tmp_name'];  // مكان مؤقت
    
    // التحقق من امتداد الصورة
    $fileExt = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileExt, $allowed)) {

        $newPhotoName = '_'.time() . '_' . $photoName;   // هون بدي امنع التكرار
        $targetDir = "users_images/" . $newPhotoName;

        if (move_uploaded_file($photoTmp, $targetDir)) {
            
            try {
                //  تعديل جملة SQL لتستقبل الرتبة المتغيرة بدلاً من 'user' الثابتة
                $sql = "INSERT INTO users (fullname, email, password, photo, role) 
                        VALUES (:fullname, :email, :pass, :photo, :role)";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':fullname' => $fullname,
                    ':email'    => $email,
                    ':pass'     => $hashed_password, 
                    ':photo'    => $newPhotoName,
                    ':role'     => $role // تمرير الرتبة هنا
                ]);
                 
                // تمت عملية الدخول بنجاح
                
                //. فحص التوجيه: هل اللي أضاف اليوزر هو الأدمن؟
                if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                     echo "<script>
                        alert('New user has been added successfully!');
                        window.location.href = 'admin.php';  // ارجع للداش بورد
                     </script>";
                } 
                else {
                    // هذا مستخدم جديد بيسجل لحاله من صفحة التسجيل
                     echo "<script>
                        alert('Account created successfully! Please log in.');
                        window.location.href = 'login.html'; // روح سجل دخول
                     </script>";
                }

                exit();

            } 
            catch (PDOException $e) {
                echo "Database error (the email might already be registered): ". $e->getMessage();
            }

        }
         else
         {
            echo "<script>alert('Image upload failed! Please make sure the users_images folder exists.'); window.history.back();</script>";
        }
    } 
    else {  // الامتداد مش صحيح
         echo "<script>alert('Invalid file type! Please upload an image (JPG or PNG only).'); window.history.back();</script>";
    }

} 
else {
      // اذا حدا بحاول يفتح الملف بدون الفورم
    header("Location: createAccount.html");
}
?>
