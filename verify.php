<?php
session_start();
include("../config/connect.php");

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verifyOtp'])) {
    $otp = trim($_POST['otp']);
    $email = $_SESSION['reset_email'] ?? null;

    if (!$email) {
        $error = "⚠️ لم يتم العثور على البريد.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM password_resets WHERE email = ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $reset = $result->fetch_assoc();

        if (!$reset) {
            $error = "⚠️ لم يتم العثور على رمز تحقق.";
        } elseif (time() > strtotime($reset['expiry'])) {
            $error = "⏰ انتهت صلاحية الرمز.";
        } elseif ($otp != $reset['otp_code']) {
            $error = "❌ الرمز غير صحيح.";
        } else {
            $_SESSION['otp_verified'] = true;
            header("Location: reset_password.php");
            exit();
        }
    }
}
?>
