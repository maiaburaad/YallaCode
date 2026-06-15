<?php
session_start();
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/security.php';

// اول خطوة نتصل بالداتا بيس
try {
    $conn = getDatabaseConnection();
}
catch(Throwable $e) {  // لو فشل الاتصال اطبعلي هيك
    die("Database connection failed:" . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {    // اليوزر عمل سبمت ؟
    $isAdmin = isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['role'] === 'admin';

    if ($isAdmin) {
        verifyCsrfToken();
    }

    $fullname = trim($_POST['fullname'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password_input = $_POST['password'] ?? '';
    $role = $isAdmin && in_array($_POST['role'] ?? '', ['user', 'admin'], true)
        ? $_POST['role']
        : 'user';

    if ($fullname === '' || strlen($fullname) > 100 || $email === false || strlen($password_input) < 8) {
        exit('Please provide a valid name, email, and password of at least 8 characters.');
    }

    $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

    $newPhotoName = null;

    try {
        $newPhotoName = saveProfileImage($_FILES['photo'] ?? []);
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
                if ($isAdmin) {
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

    } catch (Throwable $e) {
        if ($newPhotoName !== null) {
            $uploadedPath = __DIR__ . '/users_images/' . $newPhotoName;
            if (is_file($uploadedPath)) {
                unlink($uploadedPath);
            }
        }

        $message = $e instanceof PDOException
            ? 'The email may already be registered.'
            : ($e instanceof RuntimeException ? $e->getMessage() : 'Could not create the account.');
        exit('Registration failed: ' . escapeHtml($message));
    }
} 
else {
      // اذا حدا بحاول يفتح الملف بدون الفورم
    header("Location: createAccount.html");
}
?>
