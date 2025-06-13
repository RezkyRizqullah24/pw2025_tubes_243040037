<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit;
}

if (isset($_POST['submit'])) {
   $name = filter_var($_POST['name']);
   $pass = filter_var(sha1($_POST['pass']));
   $cpass = filter_var(sha1($_POST['cpass']));

   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
   $select_admin->execute([$name]);

   $message = [];

   if ($select_admin->rowCount() > 0) {
      $message[] = 'Username already exists!';
   } elseif ($pass !== $cpass) {
      $message[] = 'Confirm password does not match!';
   } else {
      $insert_admin = $conn->prepare("INSERT INTO `admins` (name, password) VALUES (?, ?)");
      $insert_admin->execute([$name, $cpass]);
      $message[] = 'New admin registered successfully!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Register Admin</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
   <div class="card shadow" style="width: 100%; max-width: 500px;">
      <div class="card-header bg-primary text-white">
         <h4 class="mb-0">Register New Admin</h4>
      </div>
      <div class="card-body">

         <?php if (!empty($message)): ?>
            <?php foreach ($message as $msg): ?>
               <div class="alert alert-info"><?= htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
         <?php endif; ?>

         <form action="" method="post" novalidate>
            <div class="mb-3">
               <label class="form-label">Username</label>
               <input type="text" name="name" required maxlength="20" class="form-control" placeholder="Enter your username" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="mb-3">
               <label class="form-label">Password</label>
               <input type="password" name="pass" required maxlength="20" class="form-control" placeholder="Enter your password" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="mb-3">
               <label class="form-label">Confirm Password</label>
               <input type="password" name="cpass" required maxlength="20" class="form-control" placeholder="Confirm your password" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="d-grid">
               <button type="submit" name="submit" class="btn btn-primary">Register Now</button>
            </div>
         </form>
      </div>
   </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
