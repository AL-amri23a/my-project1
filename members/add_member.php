<?php
session_start();
include("../config/connect.php");

if(!isset($_SESSION['email'])){
    header("Location: ../public/index.php");
    exit();
}

$role = $_SESSION['role'] ?? "user";
$message = "";

// إعداد اللغة
if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'ar';
if (isset($_GET['lang'])) $_SESSION['lang'] = $_GET['lang'] === 'en' ? 'en' : 'ar';
$lang = $_SESSION['lang'];
$dir = $lang === 'ar' ? 'rtl' : 'ltr';

// الترجمات
$texts = [
    'ar' => [
        'title' => 'إضافة عضو جديد',
        'brand' => 'أكاديمية الأبطال',
        'home' => 'الرئيسية',
        'members' => 'الأعضاء',
        'memberships' => 'العضويات',
        'attendance' => 'الحضور',
        'payments' => 'الإيرادات',
        'reports' => 'التقارير',
        'firstName' => 'الاسم الأول',
        'lastName' => 'الاسم الأخير',
        'email' => 'البريد الإلكتروني',
        'membershipType' => 'نوع العضوية',
        'startDate' => 'تاريخ البداية',
        'endDate' => 'تاريخ النهاية',
        'fee' => 'الرسوم',
        'role' => 'الدور',
        'activity' => 'النشاط الرياضي',
        'addMember' => 'إضافة العضو',
        'back' => 'العودة للصفحة الرئيسية',
        'successAdd' => 'تم إضافة العضو بنجاح!',
        'errorAdd' => 'حدث خطأ: '
    ],
    'en' => [
        'title' => 'Add New Member',
        'brand' => 'Champions Academy',
        'home' => 'Home',
        'members' => 'Members',
        'memberships' => 'Memberships',
        'attendance' => 'Attendance',
        'payments' => 'Payments',
        'reports' => 'Reports',
        'firstName' => 'First Name',
        'lastName' => 'Last Name',
        'email' => 'Email',
        'membershipType' => 'Membership Type',
        'startDate' => 'Start Date',
        'endDate' => 'End Date',
        'fee' => 'Fee',
        'role' => 'Role',
        'activity' => 'Activity',
        'addMember' => 'Add Member',
        'back' => 'Back to Home',
        'successAdd' => 'Member added successfully!',
        'errorAdd' => 'Error: '
    ]
];

// إضافة عضو
if(isset($_POST['addMember'])){
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $type = $_POST['type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $fee = $_POST['fee'];
    $rolePost = $_POST['role'];
    $activity = $_POST['activity'];

    $password = md5("12345"); 
    $insertUser = "INSERT INTO users(firstName,lastName,email,password,role) 
                   VALUES ('$firstName','$lastName','$email','$password','$rolePost')";
    if($conn->query($insertUser) === TRUE){
        $user_id = $conn->insert_id;
        $insertMembership = "INSERT INTO memberships(user_id,type,start_date,end_date,fee,activity) 
                             VALUES ('$user_id','$type','$start_date','$end_date','$fee','$activity')";
        if($conn->query($insertMembership) === TRUE){
            $message = "<div class='alert alert-success text-center'>{$texts[$lang]['successAdd']}</div>";
        } else {
            $message = "<div class='alert alert-danger text-center'>{$texts[$lang]['errorAdd']}".$conn->error."</div>";
        }
    } else {
        $message = "<div class='alert alert-danger text-center'>{$texts[$lang]['errorAdd']}".$conn->error."</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $texts[$lang]['title'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="homepage.php"><?= $texts[$lang]['brand'] ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if($role == 'admin'){ ?>
          <li class="nav-item"><a class="nav-link active" href="#"><?= $texts[$lang]['addMember'] ?></a></li>
          <li class="nav-item"><a class="nav-link" href="../public/view_members.php"><?= $texts[$lang]['members'] ?></a></li>
          <li class="nav-item"><a class="nav-link" href="../attendance/attendance.php"><?= $texts[$lang]['attendance'] ?></a></li>
          <li class="nav-item"><a class="nav-link" href="../reports/revenues.php"><?= $texts[$lang]['payments'] ?></a></li>
          <li class="nav-item"><a class="nav-link" href="../reports/reports.php"><?= $texts[$lang]['reports'] ?></a></li>
        <?php } ?>
      </ul>

      <ul class="navbar-nav ms-auto d-flex align-items-center gap-2">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
            <?= $_SESSION['firstName'] ?> <?= $_SESSION['lastName'] ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#"><?= $texts[$lang]['email'] ?>: <?= $_SESSION['email'] ?></a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="../auth/logout.php"><?= $texts[$lang]['logout'] ?></a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link text-white" href="?lang=ar">العربية</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="?lang=en">English</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Form -->
<div class="container">
    <h2 class="text-center mb-4"><?= $texts[$lang]['title'] ?></h2>
    <?php if($message) echo $message; ?>
    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label"><?= $texts[$lang]['firstName'] ?></label>
            <input type="text" name="firstName" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= $texts[$lang]['lastName'] ?></label>
            <input type="text" name="lastName" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= $texts[$lang]['email'] ?></label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= $texts[$lang]['membershipType'] ?></label>
            <select name="type" class="form-select" required>
                <option value=""><?= $texts[$lang]['membershipType'] ?></option>
                <option value="Basic">Basic</option>
                <option value="Premium">Premium</option>
                <option value="VIP">VIP</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= $texts[$lang]['startDate'] ?></label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= $texts[$lang]['endDate'] ?></label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= $texts[$lang]['fee'] ?></label>
            <input type="number" step="0.01" name="fee" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= $texts[$lang]['role'] ?></label>
            <select name="role" class="form-select" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label"><?= $texts[$lang]['activity'] ?></label>
            <select name="activity" class="form-select" required>
                <option value="">اختر النشاط</option>
                <option value="كرة القدم">كرة القدم</option>
                <option value="السباحة">السباحة</option>
                <option value="كرة السلة">كرة السلة</option>
                <option value="كاراتيه">كاراتيه</option>
            </select>
        </div>
        <div class="col-12 text-center">
            <button type="submit" name="addMember" class="btn btn-primary"><i class="fas fa-user-plus"></i> <?= $texts[$lang]['addMember'] ?></button>
            <a href="../public/homepage.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> <?= $texts[$lang]['back'] ?></a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
