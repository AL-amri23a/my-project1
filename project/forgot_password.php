<?php
session_start();
include("connect.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$message = "";
$step = $_SESSION['step'] ?? 1; 


if (isset($_POST['requestOTP'])) {
    $email = trim($_POST['email']);
    if (!empty($email)) {
        $_SESSION['email'] = $email;


        $otp = rand(100000, 999999);
        $expires = date("Y-m-d H:i:s", strtotime("+5 minutes"));
        $stmt = $con->prepare("INSERT INTO password_resets (email, otp, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $otp, $expires]);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'collegeme23@gmail.com';
            $mail->Password = 'emdnnauazfuhicku';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('collegeme23@gmail.com', 'OTP Service');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "رمز التحقق OTP";
            $mail->Body = "رمز التحقق الخاص بك هو: <b>$otp</b> (صالح لمدة 5 دقائق)";
            $mail->send();

            $message = "✅ تم إرسال رمز التحقق إلى بريدك الإلكتروني.";
            $_SESSION['step'] = 2;
            $_SESSION['last_otp_time'] = time();
        } catch (Exception $e) {
            $message = "❌ فشل في إرسال البريد: {$mail->ErrorInfo}";
        }
    } else {
        $message = "⚠️ يرجى إدخال البريد الإلكتروني.";
    }
}

// ====== التحقق من OTP ======
if (isset($_POST['verifyOTP'])) {
    $otp = trim($_POST['otp']);
    $email = $_SESSION['email'] ?? "";

    $stmt = $con->prepare("SELECT * FROM password_resets WHERE email=? AND otp=? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$email, $otp]);
    $row = $stmt->fetch();

    if ($row && strtotime($row['expires_at']) > time()) {
        $_SESSION['step'] = 3;
        $message = " رمز التحقق صحيح. يمكنك الآن تعيين كلمة مرور جديدة.";
    } else {
        $message = " رمز التحقق غير صحيح أو منتهي الصلاحية.";
    }
}
if (isset($_POST['resetPassword'])) {
    $newPass = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_SESSION['email'];

    $stmt = $con->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->execute([$newPass, $email]);

    $_SESSION['step'] = 1;
    unset($_SESSION['email']);
    $message = " تم تغيير كلمة المرور بنجاح!";
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>نسيت كلمة المرور</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
  <h2 class="mb-4">نسيت كلمة المرور</h2>
  <div class="alert alert-info"><?= $message ?></div>

  <!-- الخطوة 1: إدخال البريد -->
  <?php if ($_SESSION['step'] == 1): ?>
  <form method="POST">
    <input type="email" name="email" class="form-control mb-3" placeholder="أدخل بريدك" required>
    <button type="submit" name="requestOTP" class="btn btn-primary w-100">إرسال رمز التحقق</button>
  </form>
  <?php endif; ?>

  <?php if ($_SESSION['step'] == 2): ?>
  <form method="POST">
    <input type="text" name="otp" class="form-control mb-3" placeholder="أدخل رمز التحقق" required>
    <button type="submit" name="verifyOTP" class="btn btn-success w-100 mb-2">تحقق</button>
  </form>

  <?php
  $disabled = (time() - $_SESSION['last_otp_time'] < 30) ? "disabled" : "";
  ?>
  <form method="POST">
    <button type="submit" name="requestOTP" class="btn btn-secondary w-100" <?= $disabled ?>>إعادة إرسال الرمز</button>
  </form>
  <?php endif; ?>

  <?php if ($_SESSION['step'] == 3): ?>
  <form method="POST">
    <input type="password" name="password" class="form-control mb-3" placeholder="كلمة المرور الجديدة" required>
    <button type="submit" name="resetPassword" class="btn btn-warning w-100">تغيير كلمة المرور</button>
  </form>
  <?php endif; ?>
</body>
</html>
