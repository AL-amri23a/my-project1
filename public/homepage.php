<?php
session_start();
include("../config/connect.php");

if (!isset($_SESSION['email'])) {
    header("Location: ../public/index.php");
    exit();
}

if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'ar';
if (isset($_GET['lang'])) $_SESSION['lang'] = $_GET['lang'] === 'en' ? 'en' : 'ar';
$lang = $_SESSION['lang'];
$dir = $lang === 'ar' ? 'rtl' : 'ltr';

$texts = [
    'ar' => [
        'title' =>'الرئيسية',
        'brand' => 'أكاديمية الأبطال',
        'welcome' => 'لوحة التحكم',
        'addMember' => 'إضافة عضو',
        'manageMembers' => 'إدارة الأعضاء',
        'attendance' => 'إدارة الحضور',
        'reports' => 'التقارير',
        'offers' => 'العروض المتاحة',
        'latestNews' => 'آخر الأخبار',
        'news1_title' => 'افتتاح صالة جديدة',
        'news1_desc' => 'تم افتتاح صالة رياضية جديدة مجهزة بأحدث المعدات لجميع الأعضاء.',
        'news2_title' => 'مسابقة كرة قدم',
        'news2_desc' => 'انضم إلى مسابقة كرة القدم الشهرية ونافس على جوائز قيمة.',
        'news3_title' => 'حصص تدريب مجانية',
        'news3_desc' => 'احصل على حصص تدريب شخصية مجانية عند التسجيل لباقة 3 أشهر.',
        'weeklySchedule' => 'الجدول الأسبوعي للتمارين',
        'day1' => 'الإثنين', 'day1_schedule' => 'تمارين اللياقة - 6:00 مساءً',
        'day2' => 'الثلاثاء', 'day2_schedule' => 'تمارين القوة - 7:00 مساءً',
        'day3' => 'الأربعاء', 'day3_schedule' => 'تمارين التحمل - 6:00 مساءً',
        'day4' => 'الخميس', 'day4_schedule' => 'تمارين مرونة - 7:00 مساءً',
        'logout' => 'رجوع', 'hello' => 'مرحبا',
        'table_number' => '#',
        'table_user' => 'المستخدم',
        'table_email' => 'البريد',
        'table_offer' => 'العرض',
        'table_price' => 'السعر',
        'table_date' => 'تاريخ الاشتراك'
    ],
    'en' => [
        'title' => 'Homepage',
        'brand' => 'Champions Academy',
        'welcome' => 'Dashboard',
        'addMember' => 'Add Member',
        'manageMembers' => 'Manage Members',
        'attendance' => 'Attendance',
        'reports' => 'Reports',
        'offers' => 'Available Offers',
        'latestNews' => 'Latest News',
        'news1_title' => 'New Gym Opening',
        'news1_desc' => 'A new gym equipped with the latest equipment for all members has been opened.',
        'news2_title' => 'Football Tournament',
        'news2_desc' => 'Join the monthly football tournament and compete for valuable prizes.',
        'news3_title' => 'Free Training Sessions',
        'news3_desc' => 'Get free personal training sessions when signing up for a 3-month package.',
        'weeklySchedule' => 'Weekly Training Schedule',
        'day1' => 'Monday', 'day1_schedule' => 'Fitness Training - 6:00 PM',
        'day2' => 'Tuesday', 'day2_schedule' => 'Strength Training - 7:00 PM',
        'day3' => 'Wednesday', 'day3_schedule' => 'Endurance Training - 6:00 PM',
        'day4' => 'Thursday', 'day4_schedule' => 'Flexibility Training - 7:00 PM',
        'logout' => 'Back', 'hello' => 'Hello',
        'table_number' => '#',
        'table_user' => 'User',
        'table_email' => 'Email',
        'table_offer' => 'Offer',
        'table_price' => 'Price',
        'table_date' => 'Subscription Date'
    ]
];

$subscriptions = [];
if ($_SESSION['role'] === 'admin') {
    $stmt = $conn->prepare("SELECT u.firstName, u.email, s.offer_title, s.price, s.subscription_date FROM subscriptions s JOIN users u ON s.user_id=u.id ORDER BY subscription_date DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) $subscriptions[] = $row;
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
<style>
body {
    margin: 0;
    font-family: "Poppins", sans-serif;
    background: linear-gradient(-45deg, #ff9a9e, #fad0c4, #a1c4fd, #c2e9fb);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
}
@keyframes gradientBG {
    0% {background-position: 0% 50%;}
    50% {background-position: 100% 50%;}
    100% {background-position: 0% 50%;}
}
.card { border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); transition: transform 0.3s; }
.card:hover { transform: translateY(-5px); }
.card a { text-decoration: none; color: #333; }
.section-title { color: #fff; font-weight: bold; margin-bottom: 20px; text-align: center; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
.news-card, .schedule-card { border-radius: 12px; padding: 15px; background: #fff; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); transition: transform 0.3s; }
.news-card:hover, .schedule-card:hover { transform: translateY(-5px); }
.navbar-brand, .nav-link { color: #fff !important; white-space: nowrap; }
.navbar-nav { align-items: center; }
.table-container { background:#fff; padding:15px; border-radius:10px; box-shadow:0 3px 8px rgba(0,0,0,0.15); margin-top:30px; }
.text-center-flex { display:flex; justify-content:center; align-items:center; flex-wrap:wrap; gap:10px; }
.carousel-item img { max-height:300px; object-fit:cover; }
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


<div class="container mt-3">

  <div id="mainCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active"><img src="../image/adcc-football-academy10.jpg" class="d-block w-100" alt="Image 1"></div>
      <div class="carousel-item"><img src="../image/GAvknOWaIAAVVgH.jpeg" class="d-block w-100" alt="Image 2"></div>
      <div class="carousel-item"><img src="../image/67835c4ccadf3.jpeg" class="d-block w-100" alt="Image 3"></div>
      <div class="carousel-item"><img src="../image/1964125.jpeg" class="d-block w-100" alt="Image 4"></div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
  </div>

  <h1 class="section-title"><?= $texts[$lang]['welcome'] ?></h1>

  <div class="row justify-content-center mb-5">
    <?php if ($_SESSION['role'] == 'admin') { ?>
        <div class="col-md-3 mb-3"><div class="card text-center p-3"><a href="../members/add_member.php"><i class="fas fa-user-plus fa-2x mb-2"></i><br><?= $texts[$lang]['addMember'] ?></a></div></div>
        <div class="col-md-3 mb-3"><div class="card text-center p-3"><a href="view_members.php"><i class="fas fa-users fa-2x mb-2"></i><br><?= $texts[$lang]['manageMembers'] ?></a></div></div>
        <div class="col-md-3 mb-3"><div class="card text-center p-3"><a href="../attendance/attendance.php"><i class="fas fa-calendar-check fa-2x mb-2"></i><br><?= $texts[$lang]['attendance'] ?></a></div></div>
        <div class="col-md-3 mb-3"><div class="card text-center p-3"><a href="../reports/reports.php"><i class="fas fa-chart-line fa-2x mb-2"></i><br><?= $texts[$lang]['reports'] ?></a></div></div>
    <?php } else { ?>
        <div class="col-md-3 mb-3"><div class="card text-center p-3"><a href="../payments/offers.php"><i class="fas fa-gift fa-2x mb-2"></i><br><?= $texts[$lang]['offers'] ?></a></div></div>
    <?php } ?>
  </div>

  <h2 class="section-title"><?= $texts[$lang]['latestNews'] ?></h2>
  <div class="row">
    <div class="col-md-4"><div class="news-card"><h5><?= $texts[$lang]['news1_title'] ?></h5><p><?= $texts[$lang]['news1_desc'] ?></p></div></div>
    <div class="col-md-4"><div class="news-card"><h5><?= $texts[$lang]['news2_title'] ?></h5><p><?= $texts[$lang]['news2_desc'] ?></p></div></div>
    <div class="col-md-4"><div class="news-card"><h5><?= $texts[$lang]['news3_title'] ?></h5><p><?= $texts[$lang]['news3_desc'] ?></p></div></div>
  </div>

  <h2 class="section-title mt-5"><?= $texts[$lang]['weeklySchedule'] ?></h2>
  <div class="row">
    <div class="col-md-6"><div class="schedule-card"><h5><?= $texts[$lang]['day1'] ?></h5><p><?= $texts[$lang]['day1_schedule'] ?></p></div></div>
    <div class="col-md-6"><div class="schedule-card"><h5><?= $texts[$lang]['day2'] ?></h5><p><?= $texts[$lang]['day2_schedule'] ?></p></div></div>
    <div class="col-md-6"><div class="schedule-card"><h5><?= $texts[$lang]['day3'] ?></h5><p><?= $texts[$lang]['day3_schedule'] ?></p></div></div>
    <div class="col-md-6"><div class="schedule-card"><h5><?= $texts[$lang]['day4'] ?></h5><p><?= $texts[$lang]['day4_schedule'] ?></p></div></div>
  </div>

  <?php if ($_SESSION['role']==='admin'): ?>
    <div class="table-container">
      <h2>كل الاشتراكات</h2>
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th><?= $texts[$lang]['table_number'] ?></th>
            <th><?= $texts[$lang]['table_user'] ?></th>
            <th><?= $texts[$lang]['table_email'] ?></th>
            <th><?= $texts[$lang]['table_offer'] ?></th>
            <th><?= $texts[$lang]['table_price'] ?></th>
            <th><?= $texts[$lang]['table_date'] ?></th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($subscriptions as $sub): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= $sub['firstName'] ?></td>
            <td><?= $sub['email'] ?></td>
            <td><?= $sub['offer_title'] ?></td>
            <td><?= $sub['price'] ?></td>
            <td><?= $sub['subscription_date'] ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
var carouselElement = document.querySelector('#mainCarousel');
var carousel = new bootstrap.Carousel(carouselElement, {
    interval: 3000,
    ride: 'carousel'
});
</script>
</body>
</html>
