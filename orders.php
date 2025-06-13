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
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="py-5 bg-light">
   <div class="container">
      <h2 class="text-center mb-5">Placed Orders</h2>

      <div class="row justify-content-center">

      <?php
         if($user_id == ''){
            echo '<div class="col-12"><div class="alert alert-warning text-center">Please login to see your orders</div></div>';
         }else{
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
            $select_orders->execute([$user_id]);
            if($select_orders->rowCount() > 0){
               while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
         <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm">
               <div class="card-body">
                  <p><strong>Placed on:</strong> <?= $fetch_orders['placed_on']; ?></p>
                  <p><strong>Name:</strong> <?= $fetch_orders['name']; ?></p>
                  <p><strong>Email:</strong> <?= $fetch_orders['email']; ?></p>
                  <p><strong>Number:</strong> <?= $fetch_orders['number']; ?></p>
                  <p><strong>Address:</strong> <?= $fetch_orders['address']; ?></p>
                  <p><strong>Payment Method:</strong> <?= $fetch_orders['method']; ?></p>
                  <p><strong>Your Orders:</strong> <?= $fetch_orders['total_products']; ?></p>
                  <p><strong>Total Price:</strong> $<?= $fetch_orders['total_price']; ?>/-</p>
                  <p>
                     <strong>Payment Status:</strong> 
                     <span class="<?= $fetch_orders['payment_status'] == 'pending' ? 'text-danger' : 'text-success'; ?>">
                        <?= $fetch_orders['payment_status']; ?>
                     </span>
                  </p>
               </div>
            </div>
         </div>
      <?php
               }
            } else {
               echo '<div class="col-12"><div class="alert alert-info text-center">No orders placed yet!</div></div>';
            }
         }
      ?>

      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- Bootstrap JS (optional for dropdowns, modals, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
