<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
   <title>About</title>

   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    
   <!-- Swiper CSS -->
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
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

<!-- About Section -->
<section class="py-5">
   <div class="container">
      <div class="row align-items-center">
         <div class="col-md-6 mb-4 mb-md-0">
            <img src="images/about-img.svg" alt="About Us" class="img-fluid">
         </div>
         <div class="col-md-6">
            <h2 class="fw-bold">Why Choose Us?</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam veritatis minus et similique doloribus? Harum molestias tenetur eaque illum quas?</p>
            <a href="contact.php" class="btn btn-primary mt-2">Contact Us</a>
         </div>
      </div>
   </div>
</section>

<!-- Reviews Section -->
<section class="py-5">
   <div class="container">
      <h2 class="text-center mb-4 fw-bold">Client's Reviews</h2>
      <div class="swiper reviews-slider">
         <div class="swiper-wrapper">

            <?php for ($i = 1; $i <= 6; $i++) : ?>
            <div class="swiper-slide">
               <div class="card h-100 text-center p-3">
                  <img src="images/pic-<?= $i ?>.png" alt="Reviewer <?= $i ?>" class="mx-auto rounded-circle mb-3" style="width: 100px; height: 100px;">
                  <p class="mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia tempore distinctio hic.</p>
                  <div class="text-warning mb-2">
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star"></i>
                     <i class="fas fa-star-half-alt"></i>
                  </div>
                  <h5 class="mb-0">John Deo</h5>
               </div>
            </div>
            <?php endfor; ?>

         </div>
         <div class="swiper-pagination mt-4"></div>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- Swiper & Bootstrap JS -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<!-- Swiper Init -->
<script>
var swiper = new Swiper(".reviews-slider", {
   loop: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable: true,
   },
   breakpoints: {
      0: { slidesPerView: 1 },
      768: { slidesPerView: 2 },
      991: { slidesPerView: 3 },
   },
});
</script>

</body>
</html>
