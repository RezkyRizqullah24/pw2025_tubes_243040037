<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit;
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<div class="container my-5">
   <h2 class="mb-4">Messages</h2>

   <div class="row g-4">
      <?php
         $select_messages = $conn->prepare("SELECT * FROM `messages`");
         $select_messages->execute();
         if($select_messages->rowCount() > 0){
            while($msg = $select_messages->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="col-md-6 col-lg-4">
         <div class="card h-100">
            <div class="card-body">
               <p><strong>User ID:</strong> <?= $msg['user_id']; ?></p>
               <p><strong>Name:</strong> <?= htmlspecialchars($msg['name']); ?></p>
               <p><strong>Email:</strong> <?= htmlspecialchars($msg['email']); ?></p>
               <p><strong>Phone:</strong> <?= htmlspecialchars($msg['number']); ?></p>
               <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($msg['message'])); ?></p>
               <a href="messages.php?delete=<?= $msg['id']; ?>" 
                  onclick="return confirm('Delete this message?');"
                  class="btn btn-sm btn-outline-danger mt-3">
                  Delete
               </a>
            </div>
         </div>
      </div>
      <?php
            }
         } else {
            echo '<div class="col-12"><div class="alert alert-info text-center">You have no messages.</div></div>';
         }
      ?>
   </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
