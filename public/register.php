<?php
session_start();
include("../config/connect.php");

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['register'])) {
    $firstName = trim($_POST['firstName']);
    $lastName  = trim($_POST['lastName']);
    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $password  = trim($_POST['password']);
    $role      = "user"; // افتراضي مستخدم عادي

    if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password)) {
        $error = "الرجاء ملء جميع الحقول.";
    } else {
        // التحقق إذا البريد موجود
        $check = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "هذا البريد مسجل مسبقاً.";
        } else {
            // تشفير كلمة المرور
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (FirstName, lastName, username, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $firstName, $lastName, $username, $email, $hashed, $role);

            if ($stmt->execute()) {
                $success = "تم إنشاء الحساب بنجاح! <a href='index.php'>تسجيل الدخول</a>";
            } else {
                $error = "حدث خطأ أثناء التسجيل.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>تسجيل جديد</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header text-center"><h4>إنشاء حساب جديد</h4></div>
        <div class="card-body">
          <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
          <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

          <form method="POST">
            <div class="mb-3">
              <label class="form-label">الاسم الأول</label>
              <input type="text" name="firstName" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">الاسم الأخير</label>
              <input type="text" name="lastName" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">اسم المستخدم</label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">البريد الإلكتروني</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">كلمة المرور</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="register" class="btn btn-success w-100">تسجيل جديد</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
