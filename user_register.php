<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $email = $_POST['email'];
   $pass = sha1($_POST['pass']);
   $cpass = sha1($_POST['cpass']);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'Email already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'Confirm password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'Registered successfully, login now please!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="container py-5">
   <div class="row justify-content-center">
      <div class="col-md-6">
         <div class="card shadow">
            <div class="card-body">
               <h3 class="card-title text-center mb-4">Register Now</h3>

               <?php
               if(isset($message)){
                  foreach($message as $msg){
                     echo '<div class="alert alert-info alert-dismissible fade show" role="alert">'
                           . htmlspecialchars($msg) .
                           '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                           </div>';
                  }
               }
               ?>

               <form action="" method="post">
                  <div class="mb-3">
                     <label for="name" class="form-label">Username</label>
                     <input type="text" name="name" id="name" required maxlength="20" placeholder="Enter your username" class="form-control">
                  </div>
                  <div class="mb-3">
                     <label for="email" class="form-label">Email</label>
                     <input type="email" name="email" id="email" required maxlength="50" placeholder="Enter your email" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <div class="mb-3">
                     <label for="pass" class="form-label">Password</label>
                     <input type="password" name="pass" id="pass" required maxlength="20" placeholder="Enter your password" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <div class="mb-3">
                     <label for="cpass" class="form-label">Confirm Password</label>
                     <input type="password" name="cpass" id="cpass" required maxlength="20" placeholder="Confirm your password" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <div class="d-grid">
                     <input type="submit" name="submit" value="Register Now" class="btn btn-primary">
                  </div>
               </form>

               <p class="mt-3 text-center">Already have an account? <a href="user_login.php">Login now</a></p>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
