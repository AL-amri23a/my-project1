<?php
session_start();
include("../config/connect.php");

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: ../public/index.php");
    exit();
}

$error = $success = "";
if(isset($_POST['add_payment'])){
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO payments (user_id, amount, date) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $user_id, $amount, $date);
    if($stmt->execute()){
        $success = "تم إضافة الدفعة بنجاح!";
    } else {
        $error = "حدث خطأ: " . $stmt->error;
    }
    $stmt->close();
}
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM payments WHERE id=$id");
    header("Location: revenues.php");
    exit();
}
$payments = $conn->query("SELECT p.id, u.username, u.email, p.amount, p.date 
                          FROM payments p
                          JOIN users u ON p.user_id = u.id
                          ORDER BY p.date DESC");
$users = $conn->query("SELECT id, username FROM users ORDER BY username ASC");
?>

<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>الإيرادات - أكاديمية الأبطال</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body { font-family: "Poppins", sans-serif; background: #f8f9fa; direction: rtl; }
.container { margin-top: 30px; }
.table th, .table td { text-align: center; }
.section-title { color: #333; font-weight: bold; margin-bottom: 20px; text-align: center; }
</style>
</head>
<body>

<div class="container">
    <h2 class="section-title">الإيرادات</h2>

    <div class="mb-3 text-center">
        <a href="../public/homepage.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> العودة للصفحة الرئيسية</a>
    </div>

    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>

    <div class="card mb-4 p-3">
        <h5 class="mb-3"> إضافة دفعة جديدة</h5>
        <form method="POST" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label>اختر العضو</label>
                <select name="user_id" class="form-select" required>
                    <option value="">اختر العضو</option>
                    <?php while($u = $users->fetch_assoc()){ ?>
                        <option value="<?= $u['id'] ?>"><?= $u['username'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>المبلغ (ر.ع)</label>
                <input type="number" name="amount" class="form-control" step="0.01" required>
            </div>
            <div class="col-md-3">
                <label>التاريخ</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="add_payment" class="btn btn-success w-100"><i class="fas fa-plus"></i> إضافة</button>
            </div>
        </form>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>اسم العضو</th>
                <th>البريد الإلكتروني</th>
                <th>المبلغ</th>
                <th>التاريخ</th>
                <th>خيارات</th>
            </tr>
        </thead>
        <tbody>
            <?php while($p = $payments->fetch_assoc()){ ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= $p['username'] ?></td>
                <td><?= $p['email'] ?></td>
                <td><?= $p['amount'] ?> ر.ع</td>
                <td><?= $p['date'] ?></td>
                <td>
                    <a href="revenues.php?delete=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه الدفعة؟')">
                        حذف
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
