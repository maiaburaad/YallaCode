<?php
session_start();  

function getProfileImage() {
    if (isset($_SESSION['photo']) && !empty($_SESSION['photo']) && file_exists('users_images/' . $_SESSION['photo'])) {
        return 'users_images/' . $_SESSION['photo'];
    } else {
        return 'images/avatar.jpg';
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YallaCode | Home</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
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
            width: auto;
            position: absolute; 
            top: -30px; 
            left: 5%; 
            z-index: 1000; 
            filter: drop-shadow(0 5px 5px rgba(0,0,0,0.3)); 
        }

        #mainNav {
            padding-left: 140px; 
        }

        .nav-link {
            color: #ddd !important;
            margin-left: 15px;
            font-size: 16px;
            font-weight: 500;
        }
        .nav-link:hover, .nav-link.active {
            color: #F05A28 !important;
        }
        
        .user-profile-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-profile-img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #F05A28; 
        }
        
        .user-name-style {
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .navbar-toggler {
            border-color: rgba(255,255,255,0.1);
            margin-left: auto; 
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.7%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-img-top {
            height: 180px;
            object-fit: cover;
        }
        .price-tag {
            color: #F05A28;
            font-weight: bold;
        }
        h1, h3 { color: #152238; font-weight: 700; }
    </style>

</head>
<body>
    
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
      
    <a class="navbar-brand" href="home.php">
        <img src="images/newlogo.png" alt="YallaCode Logo">
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-center">
        
        <li class="nav-item">
            <a class="nav-link active" href="home.php">Home</a>
        </li>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="login.html">Login</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-outline-light ms-3" href="createAccount.html" style="border-radius: 20px; font-weight: 600; color: #F05A28; border-color: #F05A28;">Join Now</a>
            </li>

        <?php else: ?>
            
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php" style="color: #F05A28 !important;">Admin a</a>
                </li>
            <?php endif; ?>

            <li class="nav-item dropdown ms-3">
                <a class="nav-link dropdown-toggle user-profile-section" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    
                    <img src="<?php echo isset($_SESSION['photo']) ? 'users_images/' . $_SESSION['photo'] : 'images/avatar.jpg'; ?>" class="nav-profile-img" alt="User">
                    
                    <span class="user-name-style"><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User'; ?></span>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end" style="border-top: 3px solid #F05A28;">
                    <li><a class="dropdown-item" href="user_profile.php"><i class="fas fa-user-circle me-2"></i> My Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </li>

        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<section style="padding-top: 50px;">
  <div class="container my-5">
    <h1 class="mb-4">Let's start learning </h1>
    <div class="row">
      <?php for($k=0; $k<4; $k++): ?>
      <div class="col-md-3">
        <div class="card mb-4">
            <a href="#"><img src="./images/course1.jpg" class="card-img-top" alt="Course Image"/></a>
            <div class="card-body">
                <h6 class="fw-bold">Intro to Programming</h6>
                <p class="card-text text-muted small">Learn the basics of coding with YallaCode.</p>
            </div>
            <div class="card-footer bg-white border-0 d-flex justify-content-between">
                <span class="badge bg-secondary">Lecture</span>
                <span class="text-success fw-bold">Free</span>
            </div>
        </div>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<section class="bg-light py-5">
  <div class="container">
    <h1 class="mb-2">What to learn next?</h1>
    <h3 class="text-muted mb-4 fs-5">Recommended for you:</h3>
    <div class="row">
       <?php for($k=0; $k<4; $k++): ?>
      <div class="col-md-3">
        <div class="card mb-4">
            <a href="#"><img src="./images/course2.jpg" class="card-img-top" alt="Course Image"/></a>
            <div class="card-body">
                <h6 class="fw-bold">Advanced Web Dev</h6>
                <p class="card-text text-muted small">Master PHP, MySQL and more.</p>
            </div>
            <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                <span class="badge bg-primary">Course</span>
                <span class="price-tag">$9.99</span>
            </div>
        </div>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <h3 class="text-muted mb-4">Popular for Web Developers:</h3>
    <div class="row">
       <?php for($k=0; $k<4; $k++): ?>
      <div class="col-md-3">
        <div class="card mb-4">
            <a href="#"><img src="./images/course2.jpg" class="card-img-top" alt="Course Image"/></a>
            <div class="card-body">
                <h6 class="fw-bold">Full Stack Bootcamp</h6>
                <p class="card-text text-muted small">Become a full stack developer.</p>
            </div>
            <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                 <span class="badge bg-dark">Bootcamp</span>
                 <span class="price-tag">$19.99</span>
            </div>
        </div>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<footer class="text-center py-4" style="background-color: #152238; color: white;">
    <p>&copy; 2023 YallaCode. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
