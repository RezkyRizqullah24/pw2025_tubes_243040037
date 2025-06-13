<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">


</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
   <div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
      <form action="" method="post">
         <h3 class="text-center mb-4">Login Now</h3>

         <?php
         if (isset($message)) {
            foreach ($message as $msg) {
               echo '<div class="alert alert-danger">' . $msg . '</div>';
            }
         }
         ?>

         <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" required placeholder="Enter your email" maxlength="50"
                   class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>

         <div class="mb-3">
            <label for="pass" class="form-label">Password</label>
            <input type="password" name="pass" id="pass" required placeholder="Enter your password" maxlength="20"
                   class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>

         <div class="d-grid mb-3">
            <input type="submit" value="Login Now" class="btn btn-primary" name="submit">
         </div>

         <p class="text-center">Don't have an account?</p>
         <div class="d-grid">
            <a href="user_register.php" class="btn btn-outline-secondary">Register Now</a>
         </div>
      </form>
   </div>
</div>

<?php include 'components/footer.php'; ?>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>