<?php
session_start();
include("../config/connect.php"); 

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'ar';
}
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'en' ? 'en' : 'ar';
}
$lang = $_SESSION['lang'];

$texts = [
    'ar' => [
        'title' => 'تقارير الأعضاء',
        'totalMembers' => 'إجمالي الأعضاء',
        'usersCount' => 'عدد Users',
        'adminsCount' => 'عدد Admins',
        'fullName' => 'الاسم الكامل',
        'email' => 'البريد',
        'role' => 'الدور',
        'backMembers' => 'عودة لقائمة الأعضاء',
        'homepage' => 'الصفحة الرئيسية',
        'admins' => 'Admins',
        'users' => 'Users'
    ],
    'en' => [
        'title' => 'Members Reports',
        'totalMembers' => 'Total Members',
        'usersCount' => 'Users Count',
        'adminsCount' => 'Admins Count',
        'fullName' => 'Full Name',
        'email' => 'Email',
        'role' => 'Role',
        'backMembers' => 'Back to Members List',
        'homepage' => 'Homepage',
        'admins' => 'Admins',
        'users' => 'Users'
    ]
];

$total_members = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$admin_count   = $conn->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetch_row()[0];
$user_count    = $conn->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetch_row()[0];

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang=='ar'?'rtl':'ltr' ?>">
<head>
<meta charset="UTF-8">
<title><?= $texts[$lang]['title'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { background-color:#f8f9fa; font-family:"Poppins", sans-serif; text-align:center; }
.card { margin-bottom:15px; }
.table th, .table td { text-align:center; }
a.btn { margin-top:15px; }
.top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
.top-bar .lang-buttons a { margin-left:5px; margin-right:5px; }
</style>
</head>
<body>

<div class="container mt-4">

   
    <div class="top-bar">
        <div class="lang-buttons">
            <a href="?lang=ar" class="btn btn-sm btn-outline-dark">العربية</a>
            <a href="?lang=en" class="btn btn-sm btn-outline-dark">English</a>
        </div>
        <div class="back-home">
            <a href="../public/homepage.php" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> <?= $texts[$lang]['homepage'] ?></a>
        </div>
    </div>

    <h2 class="mb-4"><?= $texts[$lang]['title'] ?></h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5><?= $texts[$lang]['totalMembers'] ?></h5>
                    <h3><?= $total_members ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5><?= $texts[$lang]['usersCount'] ?></h5>
                    <h3><?= $user_count ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5><?= $texts[$lang]['adminsCount'] ?></h5>
                    <h3><?= $admin_count ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <canvas id="roleChart"></canvas>
        </div>
    </div>

    <script>
    var ctx = document.getElementById('roleChart').getContext('2d');
    var roleChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['<?= $texts[$lang]['admins'] ?>','<?= $texts[$lang]['users'] ?>'],
            datasets: [{
                data: [<?= $admin_count ?>, <?= $user_count ?>],
                backgroundColor: ['#ffc107','#28a745']
            }]
        }
    });
    </script>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th><?= $texts[$lang]['fullName'] ?></th>
                <th><?= $texts[$lang]['email'] ?></th>
                <th><?= $texts[$lang]['role'] ?></th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()){ ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['FirstName'] . " " . $row['lastName'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['role'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <a href="../public/view_members.php" class="btn btn-secondary"><?= $texts[$lang]['backMembers'] ?></a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
