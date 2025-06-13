<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
   exit;
}

if (isset($_POST['order'])) {
   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $address = $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   $message = [];

   if ($check_cart->rowCount() > 0) {
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'Pesanan berhasil dibuat!';
   } else {
      $message[] = 'Keranjang kamu kosong.';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Checkout</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="container my-5">
   <div class="row justify-content-center">
      <div class="col-lg-8">

         <?php if (!empty($message)): ?>
            <?php foreach ($message as $msg): ?>
               <div class="alert alert-info"><?= htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
         <?php endif; ?>

         <form action="" method="POST" class="card shadow p-4">
            <h3 class="mb-4">Your Orders</h3>

            <div class="mb-4">
               <?php
               $grand_total = 0;
               $cart_items = [];
               $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $select_cart->execute([$user_id]);
               if ($select_cart->rowCount() > 0) {
                  while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                     $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ')';
                     $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                     ?>
                     <p class="mb-1"><?= $fetch_cart['name']; ?> <span class="text-muted">(<?= '$' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity']; ?>)</span></p>
                     <?php
                  }
                  $total_products = implode(', ', $cart_items);
               } else {
                  echo '<p class="text-danger">Keranjang kamu kosong.</p>';
                  $total_products = '';
               }
               ?>
               <input type="hidden" name="total_products" value="<?= $total_products; ?>">
               <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
               <div class="fw-bold mt-3">Total: <span class="text-success">$<?= $grand_total; ?></span></div>
            </div>

            <h4 class="mb-3">Complete Your Orders</h4>
            <div class="row g-3">

               <div class="col-md-6">
                  <label class="form-label">Nama</label>
                  <input type="text" name="name" class="form-control" required maxlength="20">
               </div>

               <div class="col-md-6">
                  <label class="form-label">Nomor HP</label>
                  <input type="number" name="number" class="form-control" required min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;">
               </div>

               <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" required maxlength="50">
               </div>

               <div class="col-md-6">
                  <label class="form-label">Payment Method</label>
                  <select name="method" class="form-select" required>
                     <option value="cash on delivery">Cash on Delivery</option>
                     <option value="credit card">Credit Card</option>
                     <option value="paytm">e-Banking</option>
                     <option value="paypal">PayPal</option>
                  </select>
               </div>

               <div class="col-md-6">
                  <label class="form-label">Full Address</label>
                  <input type="text" name="street" class="form-control" required maxlength="50">
               </div>

               <div class="col-md-6">
                  <label class="form-label">City</label>
                  <input type="text" name="city" class="form-control" required maxlength="50">
               </div>

               <div class="col-md-6">
                  <label class="form-label">State</label>
                  <input type="text" name="state" class="form-control" required maxlength="50">
               </div>

               <div class="col-md-6">
                  <label class="form-label">Country</label>
                  <input type="text" name="country" class="form-control" required maxlength="50">
               </div>

               <div class="col-md-6">
                  <label class="form-label">Post Code</label>
                  <input type="text" name="pin_code" class="form-control" required maxlength="10">
               </div>
            </div>

            <div class="mt-4">
               <button type="submit" name="order" class="btn btn-primary w-100" <?= ($grand_total > 0) ? '' : 'disabled'; ?>>Place Order</button>
            </div>
         </form>

      </div>
   </div>
</div>

<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
