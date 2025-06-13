<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit;
}

// Ambil data profile untuk ditampilkan
$select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {


   $update_profile_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
   $update_profile_name->execute([$name, $admin_id]);

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
  

   $message = [];

   if ($old_pass == $empty_pass) {
      $message[] = 'Please enter old password!';
   } elseif ($old_pass != $prev_pass) {
      $message[] = 'Old password not matched!';
   } elseif ($new_pass != $confirm_pass) {
      $message[] = 'Confirm password not matched!';
   } else {
      if ($new_pass != $empty_pass) {
         $update_admin_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$confirm_pass, $admin_id]);
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
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Update Profile</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="container my-5" style="max-width: 600px;">
   <div class="card shadow">
      <div class="card-header bg-primary text-white">
         <h4 class="mb-0">Update Profile</h4>
      </div>
      <div class="card-body">

         <?php if (!empty($message)): ?>
            <?php foreach ($message as $msg): ?>
               <div class="alert alert-info"><?= htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
         <?php endif; ?>

         <form action="" method="post">
            <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">

            <div class="mb-3">
               <label for="name" class="form-label">Username</label>
               <input type="text" name="name" id="name" value="<?= htmlspecialchars($fetch_profile['name']); ?>" required class="form-control" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="mb-3">
               <label for="old_pass" class="form-label">Old Password</label>
               <input type="password" name="old_pass" id="old_pass" class="form-control" maxlength="20" placeholder="Enter old password" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="mb-3">
               <label for="new_pass" class="form-label">New Password</label>
               <input type="password" name="new_pass" id="new_pass" class="form-control" maxlength="20" placeholder="Enter new password" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="mb-3">
               <label for="confirm_pass" class="form-label">Confirm New Password</label>
               <input type="password" name="confirm_pass" id="confirm_pass" class="form-control" maxlength="20" placeholder="Confirm new password" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>

            <div class="d-grid">
               <button type="submit" name="submit" class="btn btn-primary">Update Now</button>
            </div>
         </form>
      </div>
   </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
