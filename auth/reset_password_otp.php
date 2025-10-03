<?php
session_start();
include("config/connect.php");

$message = '';
if(!isset($_SESSION['reset_user_id'])){
    header("Location: forgot_password.php");
    exit();
}

$user_id = intval($_SESSION['reset_user_id']);
if(!isset($_SESSION['otp_verified'])){
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otp'])){
        $otp = trim($_POST['otp']);

        $stmt = $conn->prepare("SELECT id, expires_at FROM password_resets WHERE user_id = ? AND otp = ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("is", $user_id, $otp);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows === 1){
            $stmt->bind_result($reset_id, $expires_at);
            $stmt->fetch();
            $stmt->close();

            if(strtotime($expires_at) >= time()){
                $_SESSION['otp_verified'] = true;
                $message = "<div class='alert alert-success'>تم التحقق من OTP. يمكنك الآن تغيير كلمة المرور.</div>";
            } else {
                $message = "<div class='alert alert-danger'>انتهت صلاحية رمز التحقق. الرجاء طلب رمز جديد.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>رمز التحقق غير صحيح.</div>";
        }
    }
}
if(isset($_SESSION['otp_verified']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])){
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if(empty($new_password) || empty($confirm_password)){
        $message = "<div class='alert alert-danger'>الرجاء تعبئة جميع الحقول.</div>";
    } elseif($new_password !== $confirm_password){
        $message = "<div class='alert alert-danger'>كلمتا المرور غير متطابقتين.</div>";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $upd->bind_param("si", $hashed, $user_id);
        if($upd->execute()){
            $del = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
            $del->bind_param("i", $user_id);
            $del->execute();
            $del->close();

            unset($_SESSION['reset_user_id'], $_SESSION['reset_sent_time'], $_SESSION['otp_verified']);
            $message = "<div class='alert alert-success'>تم تغيير كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن.</div>";
        } else {
            $message = "<div class='alert alert-danger'>حدث خطأ أثناء حفظ كلمة المرور.</div>";
        }
        $upd->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="utf-8">
<title>إعادة تعيين كلمة المرور</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-header text-center"><h5>إعادة تعيين كلمة المرور</h5></div>
        <div class="card-body">
          <?= $message ?>

          <?php if(!isset($_SESSION['otp_verified'])): ?>
          <form method="post">
              <div class="mb-3">
                <label class="form-label">رمز التحقق (OTP)</label>
                <input type="text" name="otp" class="form-control" required>
              </div>
              <button class="btn btn-primary w-100">تحقق</button>
          </form>

          <?php else: ?>
          <form method="post">
              <div class="mb-3">
                <label class="form-label">كلمة المرور الجديدة</label>
                <input type="password" name="new_password" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">تأكيد كلمة المرور</label>
                <input type="password" name="confirm_password" class="form-control" required>
              </div>
              <button class="btn btn-success w-100">تأكيد وتغيير كلمة المرور</button>
          </form>
          <?php endif; ?>

          <div class="mt-3 text-center">
            <a href="login.php">العودة لتسجيل الدخول</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
