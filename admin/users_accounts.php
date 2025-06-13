<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_user->execute([$delete_id]);
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_orders->execute([$delete_id]);
   $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
   $delete_messages->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:users_accounts.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="container my-5">
   <h2 class="mb-4">User Accounts</h2>

   <div class="row g-4">
      <?php
         $select_accounts = $conn->prepare("SELECT * FROM `users`");
         $select_accounts->execute();
         if($select_accounts->rowCount() > 0){
            while($user = $select_accounts->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card h-100">
            <div class="card-body">
               <p><strong>User ID:</strong> <?= $user['id']; ?></p>
               <p><strong>Username:</strong> <?= htmlspecialchars($user['name']); ?></p>
               <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
               <a href="users_accounts.php?delete=<?= $user['id']; ?>" 
                  class="btn btn-sm btn-outline-danger mt-3" 
                  onclick="return confirm('Delete this account? All related user data will also be deleted!');">
                  Delete
               </a>
            </div>
         </div>
      </div>
      <?php
            }
         } else {
            echo '<div class="col-12"><div class="alert alert-warning text-center">No user accounts available!</div></div>';
         }
      ?>
   </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
