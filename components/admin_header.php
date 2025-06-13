<?php
   if(isset($message)){
      foreach($message as $msg){
         echo '
         <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
            '.htmlspecialchars($msg).'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>
         ';
      }
   }
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
   <div class="container">
      <a class="navbar-brand fw-bold fs-1" href="../admin/dashboard.php">Admin<span class="text-primary">Panel</span></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
         <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="adminNavbar">
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link fs-5" href="../admin/dashboard.php">Home</a></li>
            <li class="nav-item"><a class="nav-link fs-5" href="../admin/products.php">Products</a></li>
            <li class="nav-item"><a class="nav-link fs-5" href="../admin/placed_orders.php">Orders</a></li>
            <li class="nav-item"><a class="nav-link fs-5" href="../admin/admin_accounts.php">Admins</a></li>
            <li class="nav-item"><a class="nav-link fs-5" href="../admin/users_accounts.php">Users</a></li>
            <li class="nav-item"><a class="nav-link fs-5" href="../admin/messages.php">Messages</a></li>
         </ul>

         <ul class="navbar-nav d-flex align-items-center">
            <li class="nav-item dropdown">
               <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                  <i class="fas fa-user"></i>
               </a>
               <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                  <?php
                     $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
                     $select_profile->execute([$admin_id]);
                     $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                  ?>
                  <li class="dropdown-item-text fw-bold"><?= htmlspecialchars($fetch_profile["name"]); ?></li>
                  <li><a class="dropdown-item" href="../admin/update_profile.php">Update Profile</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="../admin/register_admin.php">Register Admin</a></li>
                  <li><a class="dropdown-item" href="../admin/admin_login.php">Login</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item text-danger" href="../components/admin_logout.php" onclick="return confirm('Logout from the website?');">Logout</a></li>
               </ul>
            </li>
         </ul>
      </div>
   </div>
</nav>
