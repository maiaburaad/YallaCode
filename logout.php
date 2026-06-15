<?php
session_start();     //يعني لازم افتح السيشن عشان اسكرها
session_unset();    // بحذف كل المتغيرات
session_destroy();  //دمرت الجلسة
header("Location: login.html"); // رجعنا لصفحة الدخول
exit();
?>