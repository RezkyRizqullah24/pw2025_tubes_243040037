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

$fetch_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$fetch_profile->execute([$user_id]);
$fetch_profile = $fetch_profile->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $user_id]);

   $empty_pass = sha1('');
   $prev_pass = $_POST['prev_pass'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   if($old_pass == $empty_pass){
      $message[] = 'Please enter your old password!';
   } elseif($old_pass != $prev_pass){
      $message[] = 'Old password is incorrect!';
   } elseif($new_pass != $cpass){
      $message[] = 'Confirm password does not match!';
   } else {
      if($new_pass != $empty_pass){
         $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_pass->execute([$cpass, $user_id]);
         $message[] = 'Password updated successfully!';
      } else {
         $message[] = 'Please enter a new password!';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="container py-5">
   <div class="row justify-content-center">
      <div class="col-md-6">
         <h3 class="text-center mb-4">Update Profile</h3>

         <?php if(!empty($message)): ?>
            <?php foreach($message as $msg): ?>
               <div class="alert alert-info alert-dismissible fade show" role="alert">
                  <?= $msg; ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>
            <?php endforeach; ?>
         <?php endif; ?>

         <form action="" method="post" class="border p-4 shadow rounded bg-light">
            <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">

            <div class="mb-3">
               <label for="name" class="form-label">Username</label>
               <input type="text" name="name" id="name" required maxlength="20" class="form-control" value="<?= $fetch_profile['name']; ?>">
            </div>

            <div class="mb-3">
               <label for="email" class="form-label">Email</label>
               <input type="email" name="email" id="email" required maxlength="50" class="form-control" value="<?= $fetch_profile['email']; ?>" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="mb-3">
               <label for="old_pass" class="form-label">Old Password</label>
               <input type="password" name="old_pass" id="old_pass" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="mb-3">
               <label for="new_pass" class="form-label">New Password</label>
               <input type="password" name="new_pass" id="new_pass" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="mb-3">
               <label for="cpass" class="form-label">Confirm New Password</label>
               <input type="password" name="cpass" id="cpass" maxlength="20" class="form-control" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="d-grid">
               <input type="submit" value="Update Now" class="btn btn-primary" name="submit">
            </div>
         </form>
      </div>
   </div>
</div>

<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
