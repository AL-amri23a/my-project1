<?php
session_start();
if(!isset($_SESSION['email'])){
    header("Location: ../public/index.php");
    exit();
}
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'user'){
    header("Location: ../public/index.php");
    exit();
}

if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'ar';
if (isset($_GET['lang'])) $_SESSION['lang'] = $_GET['lang'] === 'en' ? 'en' : 'ar';
$lang = $_SESSION['lang'];
$dir = $lang === 'ar' ? 'rtl' : 'ltr';

$texts = [
    'ar' => [
        'title' => 'العروض - أكاديمية الأبطال',
        'brand' => 'أكاديمية الأبطال',
        'hello' => 'مرحبا',
        'logout' => 'تسجيل الخروج',
        'offersTitle' => 'عروضنا الخاصة',
        'offer1_title' => 'خصم 20% على العضوية الشهرية',
        'offer1_desc' => 'استمتع بعضوية شاملة مع خصم خاص عند التسجيل خلال هذا الشهر.',
        'offer1_badge' => 'جديد',
        'offer2_title' => 'باقات اللياقة المميزة',
        'offer2_desc' => 'اشترك في باقة 3 أشهر واحصل على جلسة تدريب شخصية مجانية.',
        'offer2_badge' => 'لفترة محدودة',
        'offer3_title' => 'العناية بالصحة',
        'offer3_desc' => 'استشارة تغذية مجانية عند الاشتراك في العضوية السنوية.',
        'offer3_badge' => 'حصري'
    ],
    'en' => [
        'title' => 'Offers - Champions Academy',
        'brand' => 'Champions Academy',
        'hello' => 'Hello',
        'logout' => 'Logout',
        'offersTitle' => 'Our Special Offers',
        'offer1_title' => '20% Discount on Monthly Membership',
        'offer1_desc' => 'Enjoy a full membership with a special discount when registering this month.',
        'offer1_badge' => 'New',
        'offer2_title' => 'Premium Fitness Packages',
        'offer2_desc' => 'Subscribe to a 3-month package and get a free personal training session.',
        'offer2_badge' => 'Limited Time',
        'offer3_title' => 'Health Care',
        'offer3_desc' => 'Free nutrition consultation when subscribing to the annual membership.',
        'offer3_badge' => 'Exclusive'
    ]
];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $texts[$lang]['title'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body { background: #f8f9fa; direction: <?= $dir ?>; }
.offer-card {
    border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: 0.3s; min-height: 220px; display:flex;
    flex-direction:column; justify-content:space-between;
    text-align:center; padding:20px;
}
.offer-card:hover { transform: translateY(-5px); }
.offer-card i { font-size: 3.5rem; margin-bottom: 10px; }
.offer-card h5 { font-weight: bold; font-size: 1.1rem; margin-bottom: 5px; }
.offer-card p { font-size: 0.95rem; }
.navbar-brand { font-size: 1.3rem; white-space: nowrap; }
.navbar-nav { display: flex; gap: 10px; align-items: center; }
.back-btn { margin-bottom: 20px; }
a.text-decoration-none { color: inherit; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand" href="../public/homepage.php"><?= $texts[$lang]['brand'] ?></a>

    <span class="text-white mx-auto"><?= $texts[$lang]['hello'] ?> <?= $_SESSION['firstName']; ?></span>

    <div class="d-flex align-items-center gap-2">
      <a class="nav-link text-white" href="?lang=ar">العربية</a>
      <a class="nav-link text-white" href="?lang=en">English</a>
      <a href="../public/index.php" class="btn btn-outline-light btn-sm"><?= $texts[$lang]['logout'] ?></a>
    </div>
  </div>
</nav>

<div class="container my-5">
  <h1 class="text-center mb-4"><?= $texts[$lang]['offersTitle'] ?></h1>
  <div class="row justify-content-center">
    
    <div class="col-md-4 mb-3">
      <a href="offer.php?id=1" class="text-decoration-none">
        <div class="card offer-card">
          <i class="fas fa-dumbbell text-primary"></i>
          <h5><?= $texts[$lang]['offer1_title'] ?></h5>
          <p><?= $texts[$lang]['offer1_desc'] ?></p>
          <span class="badge bg-success"><?= $texts[$lang]['offer1_badge'] ?></span>
        </div>
      </a>
    </div>

    <div class="col-md-4 mb-3">
      <a href="offer.php?id=2" class="text-decoration-none">
        <div class="card offer-card">
          <i class="fas fa-bolt text-warning"></i>
          <h5><?= $texts[$lang]['offer2_title'] ?></h5>
          <p><?= $texts[$lang]['offer2_desc'] ?></p>
          <span class="badge bg-warning text-dark"><?= $texts[$lang]['offer2_badge'] ?></span>
        </div>
      </a>
    </div>

    <div class="col-md-4 mb-3">
      <a href="offer.php?id=3" class="text-decoration-none">
        <div class="card offer-card">
          <i class="fas fa-heartbeat text-danger"></i>
          <h5><?= $texts[$lang]['offer3_title'] ?></h5>
          <p><?= $texts[$lang]['offer3_desc'] ?></p>
          <span class="badge bg-danger"><?= $texts[$lang]['offer3_badge'] ?></span>
        </div>
      </a>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
