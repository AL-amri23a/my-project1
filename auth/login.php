<?php
session_start();
include("../config/connect.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $row = $result->fetch_assoc();

        if(password_verify($password, $row['password'])){
            $_SESSION['id']        = $row['id'];
            $_SESSION['firstName'] = $row['FirstName'];
            $_SESSION['lastName']  = $row['lastName'];
            $_SESSION['username']  = $row['username'];
            $_SESSION['email']     = $row['email'];
            $_SESSION['role']      = $row['role'];

            if($row['role'] == 'admin'){
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../user/dashboard.php");
            }
            exit();
        } else {
            $error = "كلمة المرور غير صحيحة";
        }
    } else {
        $error = "البريد الإلكتروني غير مسجل";
    }
}
?>
