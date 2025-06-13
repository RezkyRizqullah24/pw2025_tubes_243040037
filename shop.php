<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

include 'components/wishlist_cart.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>

   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="py-5">
      <div class="container">
         <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Products</h2>
            <form method="get" class="d-flex">
               <select name="sort" class="form-select" onchange="this.form.submit()">
                  <option value="">Urutkan</option>
                  <option value="newest" <?= isset($_GET['sort']) && $_GET['sort'] == 'newest' ? 'selected' : '' ?>>Terbaru</option>
                  <option value="oldest" <?= isset($_GET['sort']) && $_GET['sort'] == 'oldest' ? 'selected' : '' ?>>Terlama</option>
               </select>
            </form>
         </div>

         <div class="row">
            <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
               while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
                  <div class="col-md-4 mb-4">
                     <form action="" method="post" class="card h-100 p-3">
                        <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                        <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                        <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                        <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">

                        <div class="d-flex justify-content-between mb-2">
                           <button class="btn btn-outline-danger btn-sm" type="submit" name="add_to_wishlist"><i class="fas fa-heart"></i></button>
                           <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-eye"></i></a>
                        </div>

                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" class="card-img-top mb-3" alt="product">

                        <div class="card-body p-0">
                           <h5 class="card-title"><?= $fetch_product['name']; ?></h5>
                           <div class="d-flex justify-content-between align-items-center mt-2">
                              <span class="text-primary fw-bold">$<?= $fetch_product['price']; ?>/-</span>
                              <input type="number" name="qty" class="form-control w-25" min="1" max="99" value="1">
                           </div>
                           <input type="submit" value="Add to Cart" class="btn btn-primary mt-3 w-100" name="add_to_cart">
                        </div>
                     </form>
                  </div>
            <?php
               }
            } else {
               echo '<div class="col-12"><p class="text-center">No products found!</p></div>';
            }
            ?>
         </div>
      </div>
   </section>

   <?php include 'components/footer.php'; ?>

   <!-- Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
