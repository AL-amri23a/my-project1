<?php
session_start();
include("../config/connect.php");

// ===== إعداد اللغة =====
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'ar';
}
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'en' ? 'en' : 'ar';
}
$lang = $_SESSION['lang'];

// الترجمات
$texts = [
    'ar' => [
        'title' => 'قائمة الأعضاء',
        'back' => 'الرئيسية',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'confirmDelete' => 'هل أنت متأكد أنك تريد حذف هذا العضو؟'
    ],
    'en' => [
        'title' => 'Members List',
        'back' => 'Homepage',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'confirmDelete' => 'Are you sure you want to delete this member?'
    ]
];

// ===== حذف العضو =====
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM memberships WHERE user_id=$id");
    $conn->query("DELETE FROM users WHERE id=$id");

    header("Location: view_members.php");
    exit;
}

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang=='ar'?'rtl':'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $texts[$lang]['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: "Poppins", sans-serif; }
        table { margin: 20px auto; width: 90%; }
        th, td { text-align: center; }
        a.btn { margin: 2px; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .top-bar .lang-buttons a { margin-left: 5px; margin-right: 5px; }
    </style>
</head>
<body>

<div class="container mt-4">

    <!-- الشريط العلوي -->
    <div class="top-bar">
        <div class="lang-buttons">
            <a href="?lang=ar" class="btn btn-sm btn-outline-dark">العربية</a>
            <a href="?lang=en" class="btn btn-sm btn-outline-dark">English</a>
        </div>
        <div class="back-home">
            <a href="../public/homepage.php" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> <?= $texts[$lang]['back'] ?></a>
        </div>
    </div>

    <h3 class="mb-4"><?= $texts[$lang]['title'] ?></h3>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()){ ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['username'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['role'] ?></td>
                <td>
                    <a href="../members/edit_member.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><?= $texts[$lang]['edit'] ?></a>
                    <a href="view_members.php?delete=<?= $row['id'] ?>" onclick="return confirm('<?= $texts[$lang]['confirmDelete'] ?>')" class="btn btn-sm btn-danger"><?= $texts[$lang]['delete'] ?></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
