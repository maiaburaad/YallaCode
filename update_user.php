<?php
session_start();
require_once __DIR__ . '/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {   //بدي اتاكد اني جيت من كبسة السبمت
    try {
        $conn = getDatabaseConnection();

        $id = $_POST['user_id'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        
        $sql = "UPDATE users SET fullname = ?, email = ?, role = ?";
        $params = [$fullname, $email, $role];

        if (!empty($_POST['password'])) {
            $sql .= ", password = ?";
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $params[] = $hashed_password;
        }

       if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {

    $photoName = time() . '_' . basename($_FILES['photo']['name']);
    $target = "users_images/" . $photoName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        $sql .= ", photo = ?";
        $params[] = $photoName;
    } else {
        echo "Image upload failed";
        exit;
    }
}


        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        echo "<script>
                alert('User Updated Successfully!'); 
                window.location.href='admin.php';
              </script>";

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: admin.php");
}
?>
