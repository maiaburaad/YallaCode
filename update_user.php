<?php
session_start();
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/security.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {   //بدي اتاكد اني جيت من كبسة السبمت
    verifyCsrfToken();

    try {
        $conn = getDatabaseConnection();

        $id = filter_var($_POST['user_id'] ?? null, FILTER_VALIDATE_INT);
        $fullname = trim($_POST['fullname'] ?? '');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $role = $_POST['role'] ?? '';

        if (!$id || $fullname === '' || strlen($fullname) > 100 || $email === false || !in_array($role, ['user', 'admin'], true)) {
            throw new RuntimeException('Invalid user data.');
        }
        
        $sql = "UPDATE users SET fullname = ?, email = ?, role = ?";
        $params = [$fullname, $email, $role];

        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 8) {
                throw new RuntimeException('The new password must be at least 8 characters.');
            }

            $sql .= ", password = ?";
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $params[] = $hashed_password;
        }

        $newPhotoName = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $sql .= ", photo = ?";
            $newPhotoName = saveProfileImage($_FILES['photo']);
            $params[] = $newPhotoName;
        }


        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        echo "<script>
                alert('User Updated Successfully!'); 
                window.location.href='admin.php';
              </script>";

    } catch(Throwable $e) {
        if (!empty($newPhotoName)) {
            $uploadedPath = __DIR__ . '/users_images/' . $newPhotoName;
            if (is_file($uploadedPath)) {
                unlink($uploadedPath);
            }
        }

        $message = $e instanceof PDOException
            ? 'Could not update the user record.'
            : $e->getMessage();
        exit('Update failed: ' . escapeHtml($message));
    }
} else {
    header("Location: admin.php");
}
?>
