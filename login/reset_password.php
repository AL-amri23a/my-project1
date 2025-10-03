<?php
session_start();
include("../config/connect.php");

$error = '';
$success = '';

if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resetPassword'])) {
    $newPass = trim($_POST['new_password']);
    $confirmPass = trim($_POST['confirm_password']);
    $email = $_SESSION['reset_email'];

    if (empty($newPass) || empty($confirmPass)) {
        $error = " أدخل كلمة المرور.";
    } elseif ($newPass !== $confirmPass) {
        $error = " كلمتا المرور غير متطابقتين.";
    } else {
        $hashedPass = password_hash($newPass, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPass, $email);

        if ($stmt->execute()) {
            $success = " تم تغيير كلمة المرور بنجاح.";
            unset($_SESSION['reset_email'], $_SESSION['otp_verified']);
        } else {
            $error = " حدث خطأ.";
        }
    }
}
?>
