 <?php
session_start();
include("../config/connect.php");

// التحقق من صلاحية المدير
if(!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../public/index.php");
    exit();
}

$lang = $_SESSION['lang'] ?? 'ar';
$dir = $lang==='ar'?'rtl':'ltr';

// جلب جميع الاشتراكات مع بيانات المستخدم
$query = "SELECT s.id, u.firstName, u.email, s.offer_title, s.price, s.subscription_date 
          FROM subscriptions s 
          JOIN users u ON s.user_id = u.id
          ORDER BY s.subscription_date DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $lang==='ar'?'جميع الاشتراكات':'All Subscriptions' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f8f9fa; direction:<?= $dir ?>; padding:20px; }
.table-container { max-width:1000px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
h2 { margin-bottom: 20px; }
</style>
</head>
<body>
<div class="table-container">
<h2><?= $lang==='ar'?'جميع الاشتراكات':'All Subscriptions' ?></h2>
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>#</th>
    <th><?= $lang==='ar'?'المستخدم':'User' ?></th>
    <th><?= $lang==='ar'?'البريد':'Email' ?></th>
    <th><?= $lang==='ar'?'العرض':'Offer' ?></th>
    <th><?= $lang==='ar'?'السعر':'Price' ?></th>
    <th><?= $lang==='ar'?'تاريخ الاشتراك':'Subscription Date' ?></th>
</tr>
</thead>
<tbody>
<?php $i=1; while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= $row['firstName'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['offer_title'] ?></td>
    <td><?= $row['price'] ?></td>
    <td><?= $row['subscription_date'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
