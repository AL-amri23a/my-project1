<?php
session_start();
include("../config/connect.php"); 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgotPassword'])) {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = "⚠️ الرجاء إدخال البريد الإلكتروني.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $error = "❌ البريد غير مسجل.";
        } else {
         
            $otp = rand(100000, 999999);
            $expiry = date("Y-m-d H:i:s", time() + 300); 

            $stmt2 = $conn->prepare("INSERT INTO password_resets (email, otp_code, expiry) VALUES (?, ?, ?)");
            $stmt2->bind_param("sss", $email, $otp, $expiry);
            $stmt2->execute();
            $_SESSION['reset_email'] = $email;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'yourgmail@gmail.com'; 
                $mail->Password = 'your_app_password';   
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('yourgmail@gmail.com', 'النادي الرياضي');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'رمز التحقق OTP';
                $mail->Body = "رمز التحقق الخاص بك هو: <b>$otp</b><br>الرمز صالح لمدة 5 دقائق.";

                $mail->send();
                header("Location: verify_otp.php");
                exit();
            } catch (Exception $e) {
                $error = " فشل الإرسال: {$mail->ErrorInfo}";
            }
        }
    }
}
?>
