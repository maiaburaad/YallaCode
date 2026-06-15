<?php
session_start();
require_once __DIR__ . '/database.php';


//نحاول نتصل بالداتا بيس
try {
    $conn = getDatabaseConnection();
}
catch(Throwable $e) {   //ما قدرت اتصل بالداتا بيس
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = htmlspecialchars($_POST['email']);
    $password_input = $_POST['password'];

    try {
         //ندور عاليوزر بالايميل
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);  //بترجع مصفوفة فيها بيانات اليوزر لو لقته , فولس اذا لا 

        if ($user && password_verify($password_input, $user['password'])) { //لو لقيت اليوزر وكلمة سره صح ادخل
            
            //save data to Session
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['fullname']  = $user['fullname'];
            $_SESSION['email']     = $user['email'];
            $_SESSION['role']      = $user['role']; 
            $_SESSION['photo']     = $user['photo']; 

            if ($user['role'] == 'admin') {
                header("Location: admin.php"); 
            } else {
                header("Location: home.php"); 
            }
            exit();

        } else {
            // Login failed >> (wrong email or pass)
           echo "<script>
        alert('Invalid email or password! Please try again.'); 
        window.location.href = 'login.html';
                </script>";
        }

    } catch (PDOException $e) { //مقدرتش اوصل للتيبل مثلا
        echo "Error: " . $e->getMessage();
    }
} 
else {
    //لو حاولت اعمل اكسس عالصفحة مباشرة
    header("Location: login.html");
}








?>
