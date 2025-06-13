<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Accounts</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="container my-5">

   <h2 class="mb-4">Admin Accounts</h2>

   <div class="row g-4">

      <!-- Add New Admin Card -->
      <div class="col-md-6 col-lg-4">
         <div class="card h-100 text-center">
            <div class="card-body d-flex flex-column justify-content-center">
               <p class="card-text mb-3">Add new admin</p>
               <a href="register_admin.php" class="btn btn-outline-primary">Register Admin</a>
            </div>
         </div>
      </div>

      <!-- Admin Account Cards -->
      <?php
         $select_accounts = $conn->prepare("SELECT * FROM `admins`");
         $select_accounts->execute();
         if($select_accounts->rowCount() > 0){
            while($admin = $select_accounts->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card h-100">
            <div class="card-body">
               <p><strong>Admin ID:</strong> <?= $admin['id']; ?></p>
               <p><strong>Admin Name:</strong> <?= htmlspecialchars($admin['name']); ?></p>

               <div class="d-flex justify-content-between mt-4">
                  <a href="admin_accounts.php?delete=<?= $admin['id']; ?>" 
                     class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Delete this account?');">Delete</a>

                  <?php if($admin['id'] == $admin_id): ?>
                     <a href="update_profile.php" class="btn btn-sm btn-outline-secondary">Update</a>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
      <?php
            }
         } else {
            echo '<div class="col-12"><div class="alert alert-warning text-center">No accounts available!</div></div>';
         }
      ?>

   </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
