<?php
session_start();
require_once __DIR__ . '/database.php';

// لو مش مسجل دخول أو مش أدمن، ارجع للوج ان
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// اتصلت بالداتا بيس
try {
    $conn = getDatabaseConnection();
    
    // هات كل شي من جدول users
    $stmt = $conn->query("SELECT * FROM users");
    $all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | YallaCode</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
      body{
        font-family: 'Poppins', sans-serif;
        background-color: rgb(236, 236, 236);
      }
      .navbar-placeholder {
            height: 80px;
            background-color: #152238; 
            margin-bottom: 30px; 
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 50px;
        }
      h4{ color: #F05A28; font-size: 20px; }
      
      .btn-orange{
        background-color: #F05A28;
        text-align: center;
        color: aliceblue;
        font-weight: bold;
        border-radius: 5px;
        border: none;
        font-size:medium;
        transition: 0.3s;
       }
       .btn-orange:hover {
        background-color: #c64318;
        color: aliceblue;
       }

      .modal-content {
        background-color: #1c2a48; 
        color: white; 
        border: 1px solid #F05A28; 
      }
 
      .table-container {
            background-color: #1c2a48;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      }
        
      .table { color: #ccc; }
      .table thead th { color: white; font-size: 16px; }
      .table td { border-top: 1px solid #2c3e50; vertical-align: middle !important; }
      .table-hover tbody tr:hover { background-color: #233456; }

      .close {
            color: #F05A28 !important; 
            opacity: 1 !important;     
            font-size: 30px;    
            border-color:  #F05A28;  
      }
      
      .logout-link { color: #F05A28; font-weight: bold; text-decoration: none; font-size: 16px; }
      .logout-link:hover { color: white; text-decoration: none; }
    </style>
</head>
<body>

  <div class="navbar-placeholder">
    
    <div class="logo-section">
        <img src="images/newlogo.png" alt="YallaCode Logo" style="height: 160px;"> </div>

    <div style="display: flex; align-items: center; gap: 15px;">
        
        <img src="<?php echo isset($_SESSION['photo']) ? 'users_images/' . $_SESSION['photo'] : 'images/avatar.jpg'; ?>" 
             class="img-circle" 
             style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #F05A28;">

        <span style="color: white; font-weight: 600; font-size: 16px;">
            <?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin'; ?>
        </span>

        <span style="color: #555;">|</span>

        <a href="logout.php" class="logout-link" style="display: flex; align-items: center; gap: 5px;">
             Log out <span class="glyphicon glyphicon-log-out"></span>
        </a>

    </div>

</div>

<div class="container">

    <h2 class="text-center">User Management (<?php echo count($all_users); ?> Users)</h2>

    <div class ="table-container">
        <div class = "table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th> <th class = "text-center">Photo</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class = "text-center">Actions</th>   
                    </tr>
                </thead>
                
                <tbody>
                    <?php 
                    $i = 1; // 1. بنعرف العداد وبنبلشه من واحد
                    foreach($all_users as $user): 
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        
                        <td class="text-center">
                            <img src="users_images/<?php echo $user['photo']; ?>" class="img-circle" width="40" height="40" style="object-fit:cover;">
                        </td>
                        
                        <td><?php echo $user['fullname']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['role']; ?></td>

                        <td class="text-center">
                            <button type="button" class="btn btn-success btn-sm edit-btn" 
                                    data-toggle="modal" 
                                    data-target="#editModal"
                                    data-id="<?php echo $user['id']; ?>"  
                                    data-fullname="<?php echo $user['fullname']; ?>"
                                    data-email="<?php echo $user['email']; ?>"
                                    data-role="<?php echo $user['role']; ?>"
                                    data-photo="<?php echo $user['photo']; ?>">
                                <span class="glyphicon glyphicon-pencil"></span> Edit
                            </button>

                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>

                        </td>
                    </tr>
                    <?php endforeach; ?> 
                </tbody>

            </table>
        </div>
    </div>


    <div class="text-center">
        <button type="button" class="btn btn-orange btn-lg" data-toggle="modal" data-target="#addModal" style="margin-top: 30px;">
             + Add New User
        </button>
    </div>

</div> 
<br><br>

<div id="addModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Add New User</h4>
      </div>
      <div class="modal-body">
        
        <form action="register_process.php" method="post" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" class="form-control" placeholder="Enter Name" style="background-color: #0E1C36; color: white;" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Email" style="background-color: #0E1C36; color: white;" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" style="background-color: #0E1C36; color: white;" required>
            </div>

            <div class="form-group">
         <label>Role</label>
          <select name="role" class="form-control" style="background-color: #0E1C36; color: white;">
        <option value="user">User</option>
        <option value="admin">Admin</option>
          </select>
          </div>
            
            <div class="form-group">
                <label>Profile Photo</label>
                <input type="file" name="photo" class="form-control" style="background-color: #0E1C36; color: white;" required>
            </div>

            <div class="form-group text-center"> 
                <button type="submit" class="btn btn-orange btn-lg">Save User</button>
            </div>
                      
        </form>
      </div>
    </div>
  </div>
</div>

<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Edit User</h4>
      </div>
      <div class="modal-body">
        
        <form action="update_user.php" method="post" enctype="multipart/form-data">
            
            <input type="hidden" name="user_id" id="edit_id">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" id="edit_fullname" class="form-control" required style="background-color: #0E1C36; color: white;">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="edit_email" class="form-control" required style="background-color: #0E1C36; color: white;">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" id="edit_role" class="form-control" style="background-color: #0E1C36; color: white;">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label>Password <small style="color:#ccc;">(Leave blank to keep current)</small></label>
                <input type="password" name="password" class="form-control" placeholder="New Password" style="background-color: #0E1C36; color: white;">
            </div>
            
            <div class="form-group text-center">
                <img src="" id="current_photo_preview" width="60" height="60" style="border-radius: 50%; border: 2px solid #F05A28; margin-bottom: 10px;">
                <label style="display:block;">Change Photo</label>
                <input type="file" name="photo" class="form-control" style="background-color: #0E1C36; color: white;">
            </div>

            <div class="form-group text-center"> 
                <button type="submit" class="btn btn-orange btn-lg">Update Changes</button>
            </div>
                      
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function(){
        $('.edit-btn').on('click', function(){
            //  جيب البيانات من الزر اللي انكبس
            var id = $(this).data('id');
            var fullname = $(this).data('fullname');
            var email = $(this).data('email');
            var role = $(this).data('role');
            var photo = $(this).data('photo');

            //  عبي الحقول جوا المودال
            $('#edit_id').val(id);
            $('#edit_fullname').val(fullname);
            $('#edit_email').val(email);
            $('#edit_role').val(role);
            
            //  عرض الصورة الحالية
            $('#current_photo_preview').attr('src', 'users_images/' + photo);
        });
    });
</script>

</body>
</html>
