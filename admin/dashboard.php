<?php

include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit;
}

// Ambil data admin
$fetch_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
$fetch_profile->execute([$admin_id]);
$fetch_profile = $fetch_profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Dashboard Admin</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="bg-light">

<?php include '../components/admin_header.php'; ?>

<div class="container py-5">
   <h1 class="text-center mb-5">Dashboard</h1>

   <div class="row g-4">

      <!-- Welcome Box -->
      <div class="col-md-6 col-lg-4">
         <div class="card shadow-sm text-center p-4">
            <h5>Welcome!</h5>
            <p class="fw-semibold"><?= $fetch_profile['name']; ?></p>
            <a href="update_profile.php" class="btn btn-outline-primary btn-sm">Update Profile</a>
         </div>
      </div>

      <!-- Total Pendings -->
      <?php
         $total_pendings = 0;
         $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_pendings->execute(['pending']);
         while ($fetch = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
            $total_pendings += $fetch['total_price'];
         }
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card shadow-sm text-center p-4">
            <h5>$<?= $total_pendings; ?>/-</h5>
            <p>Total Pendings</p>
            <a href="placed_orders.php" class="btn btn-primary btn-sm">See Orders</a>
         </div>
      </div>

      <!-- Completed Orders -->
      <?php
         $total_completes = 0;
         $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
         $select_completes->execute(['completed']);
         while ($fetch = $select_completes->fetch(PDO::FETCH_ASSOC)) {
            $total_completes += $fetch['total_price'];
         }
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card shadow-sm text-center p-4">
            <h5>$<?= $total_completes; ?>/-</h5>
            <p>Completed Orders</p>
            <a href="placed_orders.php" class="btn btn-success btn-sm">See Orders</a>
         </div>
      </div>

      <!-- Orders Count -->
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         $number_of_orders = $select_orders->rowCount();
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card shadow-sm text-center p-4">
            <h5><?= $number_of_orders; ?></h5>
            <p>Orders Placed</p>
            <a href="placed_orders.php" class="btn btn-info btn-sm">See Orders</a>
         </div>
      </div>

      <!-- Products Count -->
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $number_of_products = $select_products->rowCount();
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card shadow-sm text-center p-4">
            <h5><?= $number_of_products; ?></h5>
            <p>Products Added</p>
            <a href="products.php" class="btn btn-warning btn-sm">See Products</a>
         </div>
      </div>

      <!-- Users Count -->
      <?php
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();
         $number_of_users = $select_users->rowCount();
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card shadow-sm text-center p-4">
            <h5><?= $number_of_users; ?></h5>
            <p>Normal Users</p>
            <a href="users_accounts.php" class="btn btn-secondary btn-sm">See Users</a>
         </div>
      </div>

      <!-- Admin Count -->
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `admins`");
         $select_admins->execute();
         $number_of_admins = $select_admins->rowCount();
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card shadow-sm text-center p-4">
            <h5><?= $number_of_admins; ?></h5>
            <p>Admin Users</p>
            <a href="admin_accounts.php" class="btn btn-dark btn-sm">See Admins</a>
         </div>
      </div>

      <!-- Messages Count -->
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         $number_of_messages = $select_messages->rowCount();
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card shadow-sm text-center p-4">
            <h5><?= $number_of_messages; ?></h5>
            <p>New Messages</p>
            <a href="messages.php" class="btn btn-danger btn-sm">See Messages</a>
         </div>
      </div>

   </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
