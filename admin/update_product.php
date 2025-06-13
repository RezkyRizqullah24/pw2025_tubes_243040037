<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit;
}

if (isset($_POST['update'])) {
   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $price = $_POST['price'];
   $details = $_POST['details'];
   $category = $_POST['category'];

   $conn->prepare("UPDATE `products` SET name = ?, price = ?, details = ?, category = ? WHERE id = ?")
      ->execute([$name, $price, $details, $category, $pid]);

   $message = [];

   function update_image($field, $old_image, $pid, $conn, &$message)
   {
      $image = $_FILES[$field]['name'];
      $size = $_FILES[$field]['size'];
      $tmp = $_FILES[$field]['tmp_name'];
      $folder = '../uploaded_img/' . $image;

      if (!empty($image)) {
         if ($size > 2000000) {
            $message[] = "$field size is too large!";
         } else {
            $conn->prepare("UPDATE `products` SET $field = ? WHERE id = ?")->execute([$image, $pid]);
            move_uploaded_file($tmp, $folder);
            unlink('../uploaded_img/' . $old_image);
            $message[] = "$field updated successfully!";
         }
      }
   }

   update_image('image_01', $_POST['old_image_01'], $pid, $conn, $message);
   update_image('image_02', $_POST['old_image_02'], $pid, $conn, $message);
   update_image('image_03', $_POST['old_image_03'], $pid, $conn, $message);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Update Product</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <div class="container my-5">
      <h2 class="mb-4">Update Product</h2>

      <?php
      if (!empty($message)) {
         foreach ($message as $msg) {
            echo '<div class="alert alert-info">' . htmlspecialchars($msg) . '</div>';
         }
      }

      $update_id = $_GET['update'] ?? '';
      $stmt = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $stmt->execute([$update_id]);
      if ($stmt->rowCount() > 0) {
         $product = $stmt->fetch(PDO::FETCH_ASSOC);
         ?>

         <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="pid" value="<?= $product['id']; ?>">
            <input type="hidden" name="old_image_01" value="<?= $product['image_01']; ?>">
            <input type="hidden" name="old_image_02" value="<?= $product['image_02']; ?>">
            <input type="hidden" name="old_image_03" value="<?= $product['image_03']; ?>">

            <div class="row mb-4">
               <div class="col-md-6">
                  <img src="../uploaded_img/<?= $product['image_01']; ?>" class="img-fluid border rounded mb-2"
                     alt="Main Image">
               </div>
               <div class="col-md-6">
                  <div class="row g-2">
                     <div class="col-4">
                        <img src="../uploaded_img/<?= $product['image_01']; ?>" class="img-fluid border rounded"
                           alt="Sub Image 1">
                     </div>
                     <div class="col-4">
                        <img src="../uploaded_img/<?= $product['image_02']; ?>" class="img-fluid border rounded"
                           alt="Sub Image 2">
                     </div>
                     <div class="col-4">
                        <img src="../uploaded_img/<?= $product['image_03']; ?>" class="img-fluid border rounded"
                           alt="Sub Image 3">
                     </div>
                  </div>
               </div>

            </div>

            <div class="mb-3">
               <label class="form-label">Product Name</label>
               <input type="text" name="name" class="form-control" required maxlength="100"
                  value="<?= htmlspecialchars($product['name']); ?>">
            </div>

            <div class="mb-3">
               <label class="form-label">Price</label>
               <input type="number" name="price" class="form-control" required min="0" max="9999999999"
                  value="<?= $product['price']; ?>" onkeypress="if(this.value.length == 10) return false;">
            </div>

            <div class="mb-3">
               <label class="form-label">Details</label>
               <textarea name="details" class="form-control" required
                  rows="5"><?= htmlspecialchars($product['details']); ?></textarea>
            </div>

            <div class="mb-3">
               <label class="form-label">Category</label>
               <input type="text" name="category" class="form-control" required maxlength="100"
                  value="<?= htmlspecialchars($product['category']); ?>">
            </div>

            <div class="mb-3">
               <label class="form-label">Image 01</label>
               <input type="file" name="image_01" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
               <label class="form-label">Image 02</label>
               <input type="file" name="image_02" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
               <label class="form-label">Image 03</label>
               <input type="file" name="image_03" class="form-control" accept="image/*">
            </div>

            <div class="d-flex gap-3">
               <button type="submit" name="update" class="btn btn-primary">Update</button>
               <a href="products.php" class="btn btn-secondary">Go Back</a>
            </div>
         </form>

         <?php
      } else {
         echo '<div class="alert alert-warning">No product found!</div>';
      }
      ?>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>