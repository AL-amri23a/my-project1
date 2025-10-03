<?php
session_start();
include("../config/connect.php");
require '../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'ar';
}
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'en' ? 'en' : 'ar';
}
$lang = $_SESSION['lang'];

$texts = [
    'ar' => [
        'login' => 'تسجيل الدخول',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'forgot' => 'نسيت كلمة المرور؟',
        'reset' => 'إعادة تعيين كلمة المرور',
        'otp' => 'رمز التحقق',
        'newPass' => 'كلمة المرور الجديدة',
        'send' => 'إرسال',
        'verify' => 'تحقق',
        'change' => 'تغيير كلمة المرور',
        'back' => 'العودة لتسجيل الدخول',
        'otpSent' => ' تم إرسال رمز التحقق إلى بريدك الإلكتروني.',
        'otpExpired' => 'انتهت صلاحية الرمز، يرجى إعادة الإرسال.',
        'otpWrong' => ' رمز التحقق غير صحيح.',
        'passwordChanged' => 'تم تغيير كلمة المرور بنجاح! يمكنك الآن تسجيل الدخول.',
        'fillFields' => 'الرجاء تعبئة الحقول.',
        'wrongPassword' => 'كلمة المرور غير صحيحة.',
        'emailNotFound' => 'البريد الإلكتروني غير مسجل.',
        'enterEmail' => 'يرجى إدخال البريد الإلكتروني.',
        'enterNewPass' => 'الرجاء إدخال كلمة مرور جديدة.',
        'resendWait' => ' يمكنك إعادة الإرسال بعد 30 ثانية.'
    ],
    'en' => [
        'login' => 'Login',
        'email' => 'Email',
        'password' => 'Password',
        'forgot' => 'Forgot Password?',
        'reset' => 'Reset Password',
        'otp' => 'OTP Code',
        'newPass' => 'New Password',
        'send' => 'Send',
        'verify' => 'Verify',
        'change' => 'Change Password',
        'back' => 'Back to Login',
        'otpSent' => ' OTP has been sent to your email.',
        'otpExpired' => 'OTP expired, please resend.',
        'otpWrong' => ' Incorrect OTP.',
        'passwordChanged' => 'Password changed successfully! You can now login.',
        'fillFields' => 'Please fill in all fields.',
        'wrongPassword' => 'Incorrect password.',
        'emailNotFound' => 'Email not registered.',
        'enterEmail' => 'Please enter email.',
        'enterNewPass' => 'Please enter new password.',
        'resendWait' => '⏳ You can resend after 30 seconds.'
    ]
];

$error = '';
$success = '';

// ===== تسجيل الدخول =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signIn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = $texts[$lang]['fillFields'];
    } else {
        $stmt = $conn->prepare("SELECT id, FirstName, lastName, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password']) || md5($password) === $user['password']) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['firstName'] = $user['FirstName'];
                $_SESSION['lastName'] = $user['lastName'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                header('Location: ../public/homepage.php');
                exit();
            } else {
                $error = $texts[$lang]['wrongPassword'];
            }
        } else {
            $error = $texts[$lang]['emailNotFound'];
        }
        $stmt->close();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['requestOTP'])) {
    $email = trim($_POST['email']);
    if (!empty($email)) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email'] = $email;
        $_SESSION['otp_expire'] = time() + 300;
        $_SESSION['otp_sent'] = true;
        $_SESSION['last_otp_time'] = time();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'collegeme23@gmail.com';
            $mail->Password   = 'emdnnauazfuhicku';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('collegeme23@gmail.com', 'OTP Service');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $texts[$lang]['otp'];
            $mail->Body = $texts[$lang]['otp'] . " : <b>$otp</b> (5 minutes valid)";

            $mail->send();
            $success = $texts[$lang]['otpSent'];
        } catch (Exception $e) {
            $error = "❌ Failed to send: {$mail->ErrorInfo}";
        }
    } else {
        $error = $texts[$lang]['enterEmail'];
    }
}

// ===== تحقق OTP =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verifyOTP'])) {
    $otp_input = trim($_POST['otp']);
    if (isset($_SESSION['otp']) && isset($_SESSION['otp_expire'])) {
        if (time() > $_SESSION['otp_expire']) {
            $error = $texts[$lang]['otpExpired'];
            unset($_SESSION['otp'], $_SESSION['otp_sent'], $_SESSION['otp_verified']);
        } elseif ($otp_input == $_SESSION['otp']) {
            $_SESSION['otp_verified'] = true;
            $success = $texts[$lang]['otpSent'];
        } else {
            $error = $texts[$lang]['otpWrong'];
        }
    } else {
        $error = $texts[$lang]['enterEmail'];
    }
}

// ===== إعادة تعيين كلمة المرور =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resetPassword'])) {
    $newPassword = trim($_POST['newPassword']);
    $email = $_SESSION['otp_email'] ?? null;

    if (!empty($newPassword) && $email) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed, $email);
        if ($stmt->execute()) {
            $success = $texts[$lang]['passwordChanged'];
            unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_verified'], $_SESSION['otp_sent']);
        } else {
            $error = "Error updating password.";
        }
        $stmt->close();
    } else {
        $error = $texts[$lang]['enterNewPass'];
    }
}

?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $texts[$lang]['login'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f0f2f5; font-family: 'Poppins', sans-serif; }
.card { border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
.card-header { background-color: #343a40; color: #fff; text-align: center; font-weight: bold; }
.btn-primary { background-color: #343a40; border: none; }
a { text-decoration: none; } a:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="container mt-5">
<div class="d-flex justify-content-end mb-3">
  <a href="?lang=ar" class="btn btn-sm btn-outline-dark me-2">العربية</a>
  <a href="?lang=en" class="btn btn-sm btn-outline-dark">English</a>
</div>
<div class="row justify-content-center">
<div class="col-md-5">

<!-- تسجيل الدخول -->
<div class="card shadow-sm mb-3 login-card" <?php if(isset($_POST['requestOTP'])||isset($_POST['verifyOTP'])||isset($_POST['resetPassword'])) echo 'style="display:none;"'; ?>>
  <div class="card-header"><?= $texts[$lang]['login'] ?></div>
  <div class="card-body">
    <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if(!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label"><?= $texts[$lang]['email'] ?></label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><?= $texts[$lang]['password'] ?></label>
        <input type="password" name="password" class="form-control">
      </div>
      <button type="submit" name="signIn" class="btn btn-primary w-100 mb-2"><?= $texts[$lang]['login'] ?></button>
    </form>
    <div class="text-center"><a href="#" id="showResetForm"><?= $texts[$lang]['forgot'] ?></a></div>
  </div>
</div>

<!-- إعادة تعيين كلمة المرور -->
<div class="card shadow-sm" id="resetForm" style="display:<?php echo (isset($_POST['requestOTP'])||isset($_POST['verifyOTP'])||isset($_POST['resetPassword']))?'block':'none'; ?>">
  <div class="card-header"><?= $texts[$lang]['reset'] ?></div>
  <div class="card-body">
    <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if(!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

    <!-- المرحلة 1: البريد -->
    <?php if(!isset($_SESSION['otp_sent'])): ?>
    <form method="POST">
      <div class="mb-3">
        <label><?= $texts[$lang]['email'] ?></label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <button type="submit" name="requestOTP" class="btn btn-primary w-100"><?= $texts[$lang]['send'] ?></button>
    </form>
    <?php endif; ?>

    <!-- المرحلة 2: OTP -->
    <?php if(isset($_SESSION['otp_sent']) && !isset($_SESSION['otp_verified'])): ?>
    <form method="POST">
      <div class="mb-3">
        <label><?= $texts[$lang]['otp'] ?></label>
        <input type="text" name="otp" class="form-control" required>
      </div>
      <button type="submit" name="verifyOTP" class="btn btn-primary w-100 mb-2"><?= $texts[$lang]['verify'] ?></button>
    </form>
    <?php if(time() - ($_SESSION['last_otp_time'] ?? 0) > 30): ?>
    <form method="POST">
      <input type="hidden" name="email" value="<?= $_SESSION['otp_email'] ?>">
      <button type="submit" name="requestOTP" class="btn btn-secondary w-100"><?= $texts[$lang]['send'] ?></button>
    </form>
    <?php else: ?>
      <p class="text-muted text-center"><?= $texts[$lang]['resendWait'] ?></p>
    <?php endif; ?>
    <?php endif; ?>

    <!-- المرحلة 3: كلمة المرور الجديدة -->
    <?php if(isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true): ?>
    <form method="POST">
      <div class="mb-3">
        <label><?= $texts[$lang]['newPass'] ?></label>
        <input type="password" name="newPassword" class="form-control" required>
      </div>
      <button type="submit" name="resetPassword" class="btn btn-primary w-100"><?= $texts[$lang]['change'] ?></button>
    </form>
    <?php endif; ?>

    <div class="text-center mt-2"><a href="#" id="backToLogin"><?= $texts[$lang]['back'] ?></a></div>
  </div>
</div>

</div></div></div>
<script>
document.getElementById("showResetForm").addEventListener("click", function(e){
  e.preventDefault();
  document.getElementById("resetForm").style.display = "block";
  document.querySelector('.login-card').style.display = "none";
});
document.getElementById("backToLogin").addEventListener("click", function(e){
  e.preventDefault();
  document.getElementById("resetForm").style.display = "none";
  document.querySelector('.login-card').style.display = "block";
});
</script>
</body>
</html>
