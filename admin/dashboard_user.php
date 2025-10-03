<?php
session_start();
if(!isset($_SESSION['email']) || $_SESSION['role'] !== 'user'){
    header("Location: ../public/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>لوحة المستخدم - النادي الرياضي</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body { background: #f8f9fa; }
.card { border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align:center; padding:20px; }
.card a { text-decoration: none; color:#000; display:block; }
.card a:hover { color:#007bff; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">النادي الرياضي</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><span class="nav-link">مرحبا <?= $_SESSION['username'] ?></span></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h2 class="text-center mb-4">لوحة المستخدم</h2>
  <div class="row g-4 justify-content-center">
    <div class="col-md-3"><div class="card"><a href="offers.php"><i class="fas fa-gift fa-2x mb-2"></i> العروض المتاحة</a></div></div>
    <div class="col-md-3"><div class="card"><a href="my_membership.php"><i class="fas fa-id-card fa-2x mb-2"></i> عضويتي</a></div></div>
    <div class="col-md-3"><div class="card"><a href="my_attendance.php"><i class="fas fa-calendar-check fa-2x mb-2"></i> الحضور</a></div></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
