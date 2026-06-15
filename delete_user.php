<?php
session_start();
require_once __DIR__ . '/database.php';

// 1. حماية: ممنوع حدا يدخل هون إلا الأدمن
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// 2. الاتصال بالداتا بيس
if (isset($_GET['id'])) { // تأكد إن فيه ID مبعوت بالرابط
    try {
        $conn = getDatabaseConnection();

        // 3. عملية الحذف
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        
        // ربط الـ ID وتنفيذ الحذف
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();

        // 4. ارجع لصفحة الأدمن
        header("Location: admin.php");
        exit();

    } catch(PDOException $e) {
        echo "Error deleting record: " . $e->getMessage();
    }
} else {
    // لو حدا حاول يفتح الصفحة بدون ID
    header("Location: admin.php");
}
?>
