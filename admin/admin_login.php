<?php
include '../components/connect.php';
session_start();

if(isset($_POST['submit'])){
   $name = $_POST['name'];
   $pass = sha1($_POST['pass']);

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   if($select_admin->rowCount() > 0){
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
   }else{
      $message[] = 'Incorrect username or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Admin Login</title>

   <!-- Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

   <!-- FontAwesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

   <style>
      body {
         background: #f8f9fa;
      }
      .login-container {
         max-width: 400px;
         margin: 100px auto;
         background: #fff;
         padding: 30px;
         border-radius: 10px;
         box-shadow: 0 0 10px rgba(0,0,0,0.1);
      }
      .login-container h3 {
         text-align: center;
         margin-bottom: 20px;
      }
      .login-container p {
         font-size: 14px;
         color: #666;
         text-align: center;
      }
      .login-container p span {
         color: #007bff;
         font-weight: bold;
      }
   </style>
</head>
<body>

<div class="container">
   <?php
      if(isset($message)){
         foreach($message as $msg){
            echo '
            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
               '.$msg.'
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            ';
         }
      }
   ?>

   <div class="login-container">
      <form method="post" action="">
         <h3>Login Now</h3>
        
         <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="name" class="form-control" required maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>

         <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="pass" class="form-control" required maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>

         <button type="submit" name="submit" class="btn btn-primary w-100">Login Now</button>
      </form>
   </div>
</div>

</body>
</html>
