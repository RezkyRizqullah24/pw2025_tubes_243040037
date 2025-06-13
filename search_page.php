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

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Page</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="py-4 bg-light">
   <div class="container">
      <form action="" method="post" class="d-flex justify-content-center">
         <input type="text" name="search_box" placeholder="Search here..." maxlength="100" class="form-control w-50 me-2" required>
         <button type="submit" class="btn btn-primary" name="search_btn">
            <i class="fas fa-search"></i>
         </button>
      </form>
   </div>
</section>

<section class="py-5">
   <div class="container">
      <div class="row g-4">

      <?php
         if(isset($_POST['search_box']) || isset($_POST['search_btn'])){
            $search_box = $_POST['search_box'];
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ?");
            $select_products->execute(["%$search_box%"]);
            if($select_products->rowCount() > 0){
               while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="col-md-4">
         <form action="" method="post" class="card h-100 shadow-sm">
            <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
            <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
            <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
            <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">

            <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" class="card-img-top" alt="<?= $fetch_product['name']; ?>">
            <div class="card-body d-flex flex-column">
               <h5 class="card-title"><?= $fetch_product['name']; ?></h5>
               <p class="card-text text-success fw-bold">$<?= $fetch_product['price']; ?>/-</p>

               <div class="input-group mb-3">
                  <span class="input-group-text">Qty</span>
                  <input type="number" name="qty" class="form-control" min="1" max="99" value="1" onkeypress="if(this.value.length == 2) return false;">
               </div>

               <div class="mt-auto">
                  <div class="d-flex justify-content-between mb-2">
                     <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="btn btn-outline-secondary" title="Quick View"><i class="fas fa-eye"></i></a>
                     <button type="submit" name="add_to_wishlist" class="btn btn-outline-danger" title="Add to Wishlist"><i class="fas fa-heart"></i></button>
                  </div>
                  <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to Cart</button>
               </div>
            </div>
         </form>
      </div>
      <?php
               }
            } else {
               echo '<div class="col-12 text-center"><p class="text-muted">No products found!</p></div>';
            }
         }
      ?>

      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
