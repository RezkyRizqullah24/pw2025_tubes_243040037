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
   <title>Quick View</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="py-5">
   <div class="container">
      <h1 class="text-center mb-5 text-uppercase">Quick View</h1>

      <?php
        $pid = $_GET['pid'] ?? '';
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $select_products->execute([$pid]);
        if($select_products->rowCount() > 0){
           while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>

      <form action="" method="post">
         <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">

         <div class="row g-4">
            <!-- Image Column -->
            <div class="col-md-6">
               <div class="mb-3">
                  <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" class="img-fluid rounded shadow-sm" alt="<?= $fetch_product['name']; ?>">
               </div>
               <div class="d-flex gap-2">
                  <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" class="img-thumbnail" style="width: 80px;" alt="">
                  <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" class="img-thumbnail" style="width: 80px;" alt="">
                  <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" class="img-thumbnail" style="width: 80px;" alt="">
               </div>
            </div>

            <!-- Content Column -->
            <div class="col-md-6">
               <h2 class="mb-3"><?= $fetch_product['name']; ?></h2>
               <p class="fs-4 text-success fw-semibold">$<?= $fetch_product['price']; ?>/-</p>

               <div class="mb-3">
                  <label for="qty" class="form-label">Quantity</label>
                  <input type="number" name="qty" id="qty" class="form-control w-25" min="1" max="99" value="1" onkeypress="if(this.value.length == 2) return false;">
               </div>

               <p class="mb-4"><?= $fetch_product['details']; ?></p>

               <div class="d-flex gap-2">
                  <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                  <button type="submit" name="add_to_wishlist" class="btn btn-outline-danger">Add to Wishlist</button>
               </div>
            </div>
         </div>
      </form>

      <?php
         }
      } else {
         echo '<div class="text-center text-muted">No products added yet!</div>';
      }
      ?>

   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
