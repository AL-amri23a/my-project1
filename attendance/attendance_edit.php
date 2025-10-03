<?php
session_start();
include("../config/connect.php");

$id = intval($_GET['id']);
$error = $success = "";

$result = $conn->query("
    SELECT a.*, u.FirstName, u.LastName 
    FROM attendance a 
    JOIN users u ON a.user_id = u.id 
    WHERE a.id=$id
");
$attendance = $result->fetch_assoc();

$users = $conn->query("SELECT id, FirstName, LastName FROM users ORDER BY FirstName ASC");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = intval($_POST['user_id']);
    $date    = $_POST['date'];
    $status  = $_POST['status'];

    $stmt = $conn->prepare("UPDATE attendance SET user_id=?, date=?, status=? WHERE id=?");
    $stmt->bind_param("issi", $user_id, $date, $status, $id);

    if ($stmt->execute()) {
        header("Location: attendance.php");
        exit;
    } else {
        $error = "حدث خطأ: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل حضور</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { direction: rtl; background: #f8f9fa; }
        .container { margin-top: 50px; max-width: 600px; }
    </style>
</head>
<body>
<div class="container">
    <h3 class="mb-4 text-center">✏️ تعديل حضور</h3>

    <?php if($error){ ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php } ?>

    <form method="POST" class="p-3 border bg-white rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label">العضو</label>
            <select name="user_id" class="form-select" required>
                <?php while($u = $users->fetch_assoc()){ ?>
                    <option value="<?= $u['id'] ?>" <?= ($attendance['user_id']==$u['id'])?'selected':'' ?>>
                        <?= $u['FirstName'] . " " . $u['LastName'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">التاريخ</label>
            <input type="date" name="date" value="<?= $attendance['date'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-select" required>
                <option value="Present" <?= $attendance['status']=='Present'?'selected':'' ?>>حاضر</option>
                <option value="Absent" <?= $attendance['status']=='Absent'?'selected':'' ?>>غائب</option>
                <option value="Late" <?= $attendance['status']=='Late'?'selected':'' ?>>متأخر</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">💾 تحديث</button>
        <a href="attendance.php" class="btn btn-secondary">⬅️ رجوع</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
