<?php
include 'components/connect.php';
session_start();

// Jika sudah login, langsung arahkan ke home
if (isset($_SESSION['user_id'])) {
    header('location:home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Pilih Login</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- Bootstrap 5 -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">


<div class="container d-flex justify-content-center align-items-center flex-grow-1">
   <div class="card shadow p-4 w-100" style="max-width: 400px;">
      <div class="text-center mb-4">
         <i class="fas fa-user-shield fa-3x text-primary mb-2"></i>
         <h4 class="fw-bold">Kamu Siapa?</h4>
         <p class="text-muted mb-0">Pilih jenis akun untuk login</p>
      </div>
      <div class="d-grid gap-3">
         <a href="admin/admin_login.php" class="btn btn-primary">
            <i class="fas fa-user-cog me-2"></i> Login sebagai Admin
         </a>
         <a href="user_login.php" class="btn btn-outline-secondary">
            <i class="fas fa-user me-2"></i> Login sebagai User
         </a>
      </div>
   </div>
</div>

<?php include 'components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
