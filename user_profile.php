<?php
session_start();
require_once __DIR__ . '/security.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$fullname = $_SESSION['fullname'];
$email    = $_SESSION['email'];

$photoPath = getProfileImagePath($_SESSION['photo'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | YallaCode</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
        }

        .navbar-custom {
            background-color: #152238;
            height: 80px;
            position: relative;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            height: 150px;
            position: absolute;
            top: -30px;
            left: 5%;
            z-index: 1000;
            filter: drop-shadow(0 5px 5px rgba(0,0,0,0.3));
        }

        .nav-link {
            color: #ddd !important;
            font-size: 16px;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #F05A28 !important;
        }

        .profile-wrapper {
            height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-box {
            text-align: center;
        }

        .profile-img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #F05A28;
            margin-bottom: 20px;
            background-color: white;
        }

        .profile-name {
            font-size: 26px;
            font-weight: 600;
            color: #152238;
        }

        .profile-email {
            font-size: 15px;
            color: #777;
            margin-bottom: 25px;
        }

        .btn-logout {
            background-color: #F05A28;
            color: white;
            border-radius: 50px;
            padding: 10px 40px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background-color: #d94e21;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="home.php">
            <img src="images/newlogo.png" alt="YallaCode Logo">
        </a>

        <div class="ms-auto">
            <a href="home.php" class="nav-link">Home</a>
        </div>
    </div>
</nav>

<div class="profile-wrapper">
    <div class="profile-box">

        <img src="<?php echo escapeHtml($photoPath); ?>" class="profile-img" alt="Profile">

        <div class="profile-name">
            <?php echo escapeHtml($fullname); ?>
        </div>

        <div class="profile-email">
            <?php echo escapeHtml($email); ?>
        </div>

        <a href="logout.php" class="btn btn-logout">
            Logout
        </a>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
