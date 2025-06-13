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
      <a class="navbar-brand fw-bold fs-1" href="home.php">Belanja<span class="text-primary">Tech</span></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
         <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link fs-4" href="home.php">Home</a></li>
            <li class="nav-item"><a class="nav-link fs-4" href="about.php">About</a></li>
            <li class="nav-item"><a class="nav-link fs-4" href="orders.php">Orders</a></li>
            <li class="nav-item"><a class="nav-link fs-4" href="shop.php">Shop</a></li>
            <li class="nav-item"><a class="nav-link fs-4" href="contact.php">Contact</a></li>
         </ul>

         <ul class="navbar-nav d-flex align-items-center">
            <?php
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
            $total_wishlist_counts = $count_wishlist_items->rowCount();

            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_counts = $count_cart_items->rowCount();
         ?>
            <li class="nav-item me-3">
               <a class="nav-link" href="search_page.php"><i class="fas fa-search"></i></a>
            </li>
            <li class="nav-item me-3">
               <a class="nav-link position-relative" href="wishlist.php">
                  <i class="fas fa-heart"></i>
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $total_wishlist_counts; ?></span>
               </a>
            </li>
            <li class="nav-item me-3">
               <a class="nav-link position-relative" href="cart.php">
                  <i class="fas fa-shopping-cart"></i>
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $total_cart_counts; ?></span>
               </a>
            </li>
            <li class="nav-item dropdown">
               <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                  <i class="fas fa-user"></i>
               </a>
               <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <?php
                     $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                     $select_profile->execute([$user_id]);
                     if($select_profile->rowCount() > 0){
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                  ?>
                     <li class="dropdown-item-text fw-bold"><?= htmlspecialchars($fetch_profile["name"]); ?></li>
                     <li><a class="dropdown-item" href="update_user.php">Update Profile</a></li>
                     <li><hr class="dropdown-divider"></li>
                     <li><a class="dropdown-item text-danger" href="components/user_logout.php" onclick="return confirm('logout from the website?');">Logout</a></li>
                  <?php } else { ?>
                     <li><a class="dropdown-item" href="user_register.php">Register</a></li>
                     <li><a class="dropdown-item" href="user_login.php">Login</a></li>
                  <?php } ?>
               </ul>
            </li>
         </ul>
      </div>
   </div>
</nav>