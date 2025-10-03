<?php
session_start();
include("../config/connect.php");

$id = intval($_GET['id']);
$error = $success = "";

$result = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $result->fetch_assoc();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);

    if($stmt->execute()){
        $success = "تم تحديث بيانات العضو";
        header("Location: ../public/view_members.php");
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
<title>تعديل العضو</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { text-align:center; direction:rtl; background:#f8f9fa; }
.container { max-width:500px; margin-top:50px; }
</style>
</head>
<body>
<div class="container">
    <h3 class="mb-3">تعديل العضو</h3>
    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">الاسم</label>
            <input type="text" name="username" value="<?= $user['username'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" value="<?= $user['email'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">الدور</label>
            <select name="role" class="form-control" required>
                <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
                <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">تحديث العضو</button>
        <a href="../public/view_members.php" class="btn btn-secondary w-100 mt-2">عودة لقائمة الأعضاء</a>
    </form>
</div>
</body>
</html>
