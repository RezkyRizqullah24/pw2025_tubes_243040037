<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['add_product'])){
   $name = $_POST['name'];
   $price = $_POST['price'];
   $category = $_POST['category'];
   $details = $_POST['details'];

   $image_01 = $_FILES['image_01']['name'];
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Product name already exists!';
   } else {
      $insert_products = $conn->prepare("INSERT INTO `products`(name, details, price, category, image_01, image_02, image_03) VALUES(?,?,?,?,?,?,?)");
      $insert_products->execute([$name, $details, $price, $category, $image_01, $image_02, $image_03]);

      if($insert_products){
         if($image_size_01 > 2000000 || $image_size_02 > 2000000 || $image_size_03 > 2000000){
            $message[] = 'Image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'New product added!';
         }
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
   $conn->prepare("DELETE FROM `products` WHERE id = ?")->execute([$delete_id]);
   $conn->prepare("DELETE FROM `cart` WHERE pid = ?")->execute([$delete_id]);
   $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?")->execute([$delete_id]);
   header('location:products.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin - Products</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="container my-5">
   <?php if(isset($message)) {
      foreach($message as $msg) {
         echo '<div class="alert alert-info alert-dismissible fade show" role="alert">'
            . htmlspecialchars($msg) .
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';
      }
   } ?>

   <h2 class="mb-4">Add New Product</h2>
   <form method="post" enctype="multipart/form-data" class="row g-3">
      <div class="col-md-6">
         <label class="form-label">Product Name</label>
         <input type="text" name="name" class="form-control" required maxlength="100" placeholder="Enter product name">
      </div>
      <div class="col-md-6">
         <label class="form-label">Price</label>
         <input type="number" name="price" class="form-control" min="0" required max="9999999999" placeholder="Enter price" onkeypress="if(this.value.length == 10) return false;">
      </div>
      <div class="col-md-6">
         <label class="form-label">Category</label>
         <input type="text" name="category" class="form-control" required maxlength="100" placeholder="Enter category">
      </div>
      <div class="col-md-6">
         <label class="form-label">Image 01</label>
         <input type="file" name="image_01" class="form-control" required accept="image/*">
      </div>
      <div class="col-md-6">
         <label class="form-label">Image 02</label>
         <input type="file" name="image_02" class="form-control" required accept="image/*">
      </div>
      <div class="col-md-6">
         <label class="form-label">Image 03</label>
         <input type="file" name="image_03" class="form-control" required accept="image/*">
      </div>
      <div class="col-12">
         <label class="form-label">Details</label>
         <textarea name="details" class="form-control" required maxlength="500" rows="5" placeholder="Enter product details"></textarea>
      </div>
      <div class="col-12">
         <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
      </div>
   </form>

   <hr class="my-5">

   <h2 class="mb-4">Products Added</h2>
   <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($product = $select_products->fetch(PDO::FETCH_ASSOC)){ ?>
            <div class="col">
               <div class="card h-100">
                  <img src="../uploaded_img/<?= $product['image_01']; ?>" class="card-img-top" alt="Product Image">
                  <div class="card-body">
                     <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                     <h6 class="card-subtitle mb-2 text-muted">$<?= $product['price']; ?></h6>
                     <p class="card-text"><?= nl2br(htmlspecialchars($product['details'])); ?></p>
                  </div>
                  <div class="card-footer d-flex justify-content-between">
                     <a href="update_product.php?update=<?= $product['id']; ?>" class="btn btn-outline-secondary btn-sm">Update</a>
                     <a href="products.php?delete=<?= $product['id']; ?>" onclick="return confirm('Delete this product?');" class="btn btn-outline-danger btn-sm">Delete</a>
                  </div>
               </div>
            </div>
      <?php } 
      } else {
         echo '<div class="col-12"><div class="alert alert-warning text-center">No products added yet!</div></div>';
      } ?>
   </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
