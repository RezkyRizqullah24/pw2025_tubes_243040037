<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
   exit;
}

if(isset($_POST['send'])){
   $name = $_POST['name'];
   $email = $_POST['email'];
   $number = $_POST['number'];
   $msg = $_POST['msg'];

   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'Message already sent!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);
      $message[] = 'Message sent successfully!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="py-5">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-md-8">
            <div class="card shadow">
               <div class="card-body">
                  <h3 class="card-title text-center mb-4">Get in Touch</h3>
                  
                  <?php
                  if (!empty($message)) {
                     foreach ($message as $msg) {
                        echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
                     }
                  }
                  ?>

                  <form action="" method="post">
                     <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" name="name" id="name" class="form-control" required maxlength="20" placeholder="Enter your name">
                     </div>

                     <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" name="email" id="email" class="form-control" required maxlength="50" placeholder="Enter your email">
                     </div>

                     <div class="mb-3">
                        <label for="number" class="form-label">Phone Number</label>
                        <input type="number" name="number" id="number" class="form-control" required min="0" max="9999999999" placeholder="Enter your phone number" onkeypress="if(this.value.length == 10) return false;">
                     </div>

                     <div class="mb-3">
                        <label for="msg" class="form-label">Message</label>
                        <textarea name="msg" id="msg" class="form-control" rows="5" placeholder="Enter your message"></textarea>
                     </div>

                     <div class="d-grid">
                        <input type="submit" name="send" value="Send Message" class="btn btn-primary">
                     </div>
                  </form>

               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
