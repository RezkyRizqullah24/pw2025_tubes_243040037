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

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
   exit;
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>

   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="py-5">
   <div class="container">
      <h3 class="mb-4 text-center">Shopping Cart</h3>

      <div class="row g-4">
         <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
               $grand_total += $sub_total;
         ?>
         <div class="col-md-4">
            <form action="" method="post">
               <div class="card h-100 shadow-sm">
                  <img src="uploaded_img/<?= $fetch_cart['image']; ?>" class="card-img-top" alt="<?= $fetch_cart['name']; ?>">
                  <div class="card-body">
                     <h5 class="card-title"><?= $fetch_cart['name']; ?></h5>
                     <p class="card-text text-muted">Price: $<?= $fetch_cart['price']; ?>/-</p>

                     <div class="mb-3">
                        <label for="qty<?= $fetch_cart['id']; ?>" class="form-label">Quantity</label>
                        <div class="input-group">
                           <input type="number" name="qty" id="qty<?= $fetch_cart['id']; ?>" class="form-control" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" onkeypress="if(this.value.length == 2) return false;">
                           <button type="submit" name="update_qty" class="btn btn-outline-secondary"><i class="fas fa-edit"></i></button>
                        </div>
                     </div>

                     <p class="text-success">Subtotal: <strong>$<?= $sub_total; ?>/-</strong></p>

                     <div class="d-flex justify-content-between align-items-center">
                        <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="btn btn-outline-info"><i class="fas fa-eye"></i></a>
                        <button type="submit" name="delete" onclick="return confirm('Delete this item from cart?');" class="btn btn-danger">Delete</button>
                     </div>
                  </div>
                  <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
               </div>
            </form>
         </div>
         <?php
            }
         } else {
            echo '<p class="text-center">Your cart is empty.</p>';
         }
         ?>
      </div>

      <?php if($grand_total > 0): ?>
      <div class="mt-5 text-center">
         <p class="fs-5">Grand Total: <strong>$<?= $grand_total; ?>/-</strong></p>
         <a href="shop.php" class="btn btn-outline-primary me-2">Continue Shopping</a>
         <a href="cart.php?delete_all" class="btn btn-outline-danger me-2" onclick="return confirm('Delete all items from cart?');">Delete All</a>
         <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
      </div>
      <?php endif; ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
