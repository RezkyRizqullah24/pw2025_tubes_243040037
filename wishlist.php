<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
   exit;
}

include 'components/wishlist_cart.php';

if(isset($_POST['delete'])){
   $wishlist_id = $_POST['wishlist_id'];
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
   $delete_wishlist_item->execute([$wishlist_id]);
}

if(isset($_GET['delete_all'])){
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist_item->execute([$user_id]);
   header('location:wishlist.php');
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Wishlist</title>

   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="py-5">
   <div class="container">
      <h3 class="mb-4 text-center">Your Wishlist</h3>

      <div class="row g-4">
         <?php
         $grand_total = 0;
         $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
         $select_wishlist->execute([$user_id]);
         if($select_wishlist->rowCount() > 0){
            while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
               $grand_total += $fetch_wishlist['price'];
         ?>
         <div class="col-md-4">
            <form action="" method="post">
               <div class="card h-100 shadow-sm">
                  <img src="uploaded_img/<?= $fetch_wishlist['image']; ?>" class="card-img-top" alt="<?= $fetch_wishlist['name']; ?>">
                  <div class="card-body">
                     <h5 class="card-title"><?= $fetch_wishlist['name']; ?></h5>
                     <p class="card-text text-muted">$<?= $fetch_wishlist['price']; ?>/-</p>

                     <div class="mb-3">
                        <label for="qty<?= $fetch_wishlist['id']; ?>" class="form-label">Quantity</label>
                        <input type="number" name="qty" id="qty<?= $fetch_wishlist['id']; ?>" class="form-control" min="1" max="99" value="1" onkeypress="if(this.value.length == 2) return false;">
                     </div>

                     <div class="d-flex justify-content-between">
                        <a href="quick_view.php?pid=<?= $fetch_wishlist['pid']; ?>" class="btn btn-outline-secondary">
                           <i class="fas fa-eye"></i>
                        </a>
                        <button type="submit" name="add_to_cart" class="btn btn-success">Add to Cart</button>
                     </div>
                  </div>
                  <div class="card-footer bg-transparent border-top-0">
                     <input type="hidden" name="pid" value="<?= $fetch_wishlist['pid']; ?>">
                     <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">
                     <input type="hidden" name="name" value="<?= $fetch_wishlist['name']; ?>">
                     <input type="hidden" name="price" value="<?= $fetch_wishlist['price']; ?>">
                     <input type="hidden" name="image" value="<?= $fetch_wishlist['image']; ?>">

                     <button type="submit" name="delete" onclick="return confirm('Delete this from wishlist?');" class="btn btn-danger w-100 mt-2">Delete</button>
                  </div>
               </div>
            </form>
         </div>
         <?php
            }
         } else {
            echo '<p class="text-center">Your wishlist is empty.</p>';
         }
         ?>
      </div>

      <?php if($grand_total > 0): ?>
      <div class="mt-5 text-center">
         <p class="fs-5">Grand Total: <strong>$<?= $grand_total; ?>/-</strong></p>
         <a href="shop.php" class="btn btn-outline-primary me-2">Continue Shopping</a>
         <a href="wishlist.php?delete_all" class="btn btn-outline-danger" onclick="return confirm('Delete all items from wishlist?');">Delete All</a>
      </div>
      <?php endif; ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
