<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
}

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Swiper CSS -->
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
   <style>

.category-slider .swiper-pagination {
   position: static;
   margin-top: 1rem;
   text-align: center;
}
</style>
</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <div class="container-fluid p-0">
      <!-- Home Slider -->
      <section class="py-5">
         <div class="swiper home-slider">
            <div class="swiper-wrapper">
               <?php
               $slides = [
                  ["images/home-img-4.png", "Latest Smartphones"],
                  ["images/home-img-5.png", "Latest Watches"],
                  ["images/home-img-6.png", "Latest Laptops"]
               ];
               foreach ($slides as $slide) {
               ?>
               <div class="swiper-slide d-flex align-items-center">
                  <div class="row w-100 align-items-center">
                     <div class="col-md-6 text-center">
                        <img src="<?= $slide[0]; ?>" alt="" class="img-fluid">
                     </div>
                     <div class="col-md-6 text-center">
                        <span class="text-muted">Upto 50% off</span>
                        <h3><?= $slide[1]; ?></h3>
                        <a href="shop.php" class="btn btn-primary">Shop Now</a>
                     </div>
                  </div>
               </div>
               <?php } ?>
            </div>
            <div class="swiper-pagination home-pagination"></div>
         </div>
      </section>

      <!-- Kategori -->
      <section class="text-center py-5">
         <h2 class="mb-4">Shop by Category</h2>
         <div class="swiper category-slider">
            <div class="swiper-wrapper">
               <?php
               $categories = [
                  ['laptop', 'icon-1.png'],
                  ['tv', 'icon-2.png'],
                  ['kamera', 'icon-3.png'],
                  ['mouse', 'icon-4.png'],
                  ['kulkas', 'icon-5.png'],
                  ['washing', 'icon-6.png'],
                  ['smartphone', 'icon-7.png'],
                  ['watch', 'icon-8.png']
               ];
               foreach ($categories as $cat) {
                  echo '<a href="category.php?category=' . $cat[0] . '" class="swiper-slide text-decoration-none text-dark">
                           <img src="images/' . $cat[1] . '" alt="" class="img-fluid">
                           <h5 class="mt-2">' . ucfirst($cat[0]) . '</h5>
                        </a>';
               }
               ?>
            </div>
            <div class="swiper-pagination category-pagination"></div>
         </div>
      </section>

      <!-- Produk -->
      <section class="py-5">
         <div class="container">
            <h2 class="text-center mb-4">Latest Products</h2>
            <div class="swiper products-slider">
               <div class="swiper-wrapper">
                  <?php
                  $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
                  $select_products->execute();
                  if ($select_products->rowCount() > 0) {
                     while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                  <form action="" method="post" class="swiper-slide card p-3">
                     <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                     <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                     <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                     <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                     <div class="d-flex justify-content-between">
                        <button type="submit" name="add_to_wishlist" class="btn btn-outline-danger"><i class="fas fa-heart"></i></button>
                        <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="btn btn-outline-secondary"><i class="fas fa-eye"></i></a>
                     </div>
                     <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="" class="img-fluid my-2">
                     <div class="fw-bold"><?= $fetch_product['name']; ?></div>
                     <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="text-primary fw-semibold">$<?= $fetch_product['price']; ?>/-</div>
                        <input type="number" name="qty" class="form-control w-25" min="1" max="99" value="1">
                     </div>
                     <input type="submit" value="Add to Cart" class="btn btn-primary mt-3" name="add_to_cart">
                  </form>
                  <?php }
                  } else {
                     echo '<p class="text-center">No products added yet!</p>';
                  } ?>
               </div>
               <div class="swiper-pagination products-pagination"></div>
            </div>
         </div>
      </section>
   </div>

   <?php include 'components/footer.php'; ?>

   <!-- Skrip -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
   <script>
      new Swiper(".home-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: { el: ".home-pagination", clickable: true },
      });

      new Swiper(".category-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: { el: ".category-pagination", clickable: true },
         breakpoints: {
            0: { slidesPerView: 2 },
            650: { slidesPerView: 3 },
            768: { slidesPerView: 4 },
            1024: { slidesPerView: 5 },
         }
      });
      
      new Swiper(".products-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: { el: ".products-pagination", clickable: true },
         breakpoints: {
            550: { slidesPerView: 2 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
         }
      });
   </script>

</body>

</html>
