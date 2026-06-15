<?php
session_start();
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/security.php';

// 1. حماية: ممنوع حدا يدخل هون إلا الأدمن
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// 2. الاتصال بالداتا بيس
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    $userId = filter_var($_POST['user_id'] ?? null, FILTER_VALIDATE_INT);

    if (!$userId) {
        http_response_code(400);
        exit('Invalid user ID.');
    }

    try {
        $conn = getDatabaseConnection();

        // 3. عملية الحذف
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        
        // ربط الـ ID وتنفيذ الحذف
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // 4. ارجع لصفحة الأدمن
        header("Location: admin.php");
        exit();

    } catch(PDOException $e) {
        exit('Could not delete the user.');
    }
} else {
    // لو حدا حاول يفتح الصفحة بدون ID
    header("Location: admin.php");
}
?>
