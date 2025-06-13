<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_id]);
   $message[] = 'Payment status updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="container my-5">

   <?php if(isset($message)) {
      foreach($message as $msg) {
         echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'
         . htmlspecialchars($msg) .
         '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';
      }
   } ?>

   <h2 class="mb-4">Placed Orders</h2>

   <div class="row g-4">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         if($select_orders->rowCount() > 0){
            while($order = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card h-100">
            <div class="card-body">
               <p><strong>Placed on:</strong> <?= $order['placed_on']; ?></p>
               <p><strong>Name:</strong> <?= htmlspecialchars($order['name']); ?></p>
               <p><strong>Number:</strong> <?= htmlspecialchars($order['number']); ?></p>
               <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order['address'])); ?></p>
               <p><strong>Total Products:</strong> <?= $order['total_products']; ?></p>
               <p><strong>Total Price:</strong> $<?= $order['total_price']; ?>/-</p>
               <p><strong>Payment Method:</strong> <?= $order['method']; ?></p>

               <form method="post">
                  <input type="hidden" name="order_id" value="<?= $order['id']; ?>">

                  <div class="mb-3">
                     <label for="status-<?= $order['id']; ?>" class="form-label">Payment Status</label>
                     <select name="payment_status" id="status-<?= $order['id']; ?>" class="form-select">
                        <option selected disabled><?= $order['payment_status']; ?></option>
                        <option value="pending">pending</option>
                        <option value="completed">completed</option>
                     </select>
                  </div>

                  <div class="d-flex justify-content-between">
                     <button type="submit" name="update_payment" class="btn btn-sm btn-outline-primary">Update</button>
                     <a href="placed_orders.php?delete=<?= $order['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this order?');">Delete</a>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <?php
            }
         } else {
            echo '<div class="col-12"><div class="alert alert-warning text-center">No orders placed yet!</div></div>';
         }
      ?>
   </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
