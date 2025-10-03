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
        'title' => 'إدارة الحضور',
        'addAttendance' => 'إضافة حضور جديد',
        'back' => 'الصفحة الرئيسية',
        'memberName' => 'اسم العضو',
        'date' => 'التاريخ',
        'status' => 'الحالة',
        'options' => 'خيارات',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'present' => 'حاضر',
        'absent' => 'غائب',
        'late' => 'متأخر',
        'confirmDelete' => 'هل أنت متأكد من الحذف؟'
    ],
    'en' => [
        'title' => 'Attendance Management',
        'addAttendance' => 'Add New Attendance',
        'back' => 'Homepage',
        'memberName' => 'Member Name',
        'date' => 'Date',
        'status' => 'Status',
        'options' => 'Options',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'present' => 'Present',
        'absent' => 'Absent',
        'late' => 'Late',
        'confirmDelete' => 'Are you sure you want to delete this record?'
    ]
];

// ===== حذف سجل الحضور =====
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM attendance WHERE id=$id");
    header("Location: attendance.php");
    exit;
}

$result = $conn->query("
    SELECT a.id, a.date, a.status, u.FirstName, u.LastName 
    FROM attendance a 
    JOIN users u ON a.user_id = u.id 
    ORDER BY a.date DESC
");
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
        .container { margin-top: 40px; }
        table { text-align: center; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-bar .lang-buttons a { margin-left: 5px; margin-right: 5px; }
    </style>
</head>
<body>

<div class="container">

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

    <h3 class="mb-4 text-center"><?= $texts[$lang]['title'] ?></h3>

    <div class="mb-3 text-end">
        <a href="attendance_add.php" class="btn btn-success"><?= $texts[$lang]['addAttendance'] ?></a>
    </div>

    <table class="table table-bordered table-hover shadow">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th><?= $texts[$lang]['memberName'] ?></th>
                <th><?= $texts[$lang]['date'] ?></th>
                <th><?= $texts[$lang]['status'] ?></th>
                <th><?= $texts[$lang]['options'] ?></th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()){ ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['FirstName'] . " " . $row['LastName'] ?></td>
                    <td><?= $row['date'] ?></td>
                    <td>
                        <?php 
                        if($row['status'] == "Present"){ echo '<span class="badge bg-success">'.$texts[$lang]['present'].'</span>'; }
                        elseif($row['status'] == "Absent"){ echo '<span class="badge bg-danger">'.$texts[$lang]['absent'].'</span>'; }
                        else { echo '<span class="badge bg-warning text-dark">'.$texts[$lang]['late'].'</span>'; }
                        ?>
                    </td>
                    <td>
                        <a href="../attendance/attendance_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"> <?= $texts[$lang]['edit'] ?></a>
                        <a href="attendance.php?delete=<?= $row['id'] ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('<?= $texts[$lang]['confirmDelete'] ?>')"> <?= $texts[$lang]['delete'] ?></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
