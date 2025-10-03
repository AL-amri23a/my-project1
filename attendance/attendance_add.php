<?php
session_start();
include("../config/connect.php");

$users = $conn->query("SELECT id, FirstName, LastName FROM users ORDER BY FirstName ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = intval($_POST['user_id']);
    $date = $_POST['date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO attendance (user_id, date, status) VALUES (?,?,?)");
    $stmt->bind_param("iss", $user_id, $date, $status);

    if ($stmt->execute()) {
        header("Location: attendance.php");
        exit;
    } else {
        echo "خطأ: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إضافة حضور</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { direction: rtl; background: #f8f9fa; }
        .container { margin-top: 50px; max-width: 600px; }
    </style>
</head>
<body>

<div class="container">
    <h3 class="mb-4 text-center">➕ إضافة حضور جديد</h3>

    <form method="POST" class="p-3 border bg-white rounded shadow-sm">
        <div class="mb-3">
            <label class="form-label">العضو</label>
            <select name="user_id" class="form-select" required>
                <option value="">اختر عضو</option>
                <?php while($u = $users->fetch_assoc()){ ?>
                    <option value="<?= $u['id'] ?>"><?= $u['FirstName'] . " " . $u['LastName'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">التاريخ</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-select" required>
                <option value="Present">حاضر</option>
                <option value="Absent">غائب</option>
                <option value="Late">متأخر</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">💾 حفظ</button>
        <a href="attendance.php" class="btn btn-secondary">⬅️ رجوع</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
