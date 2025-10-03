<?php

include("../config/connect.php");

$admin_name     = "Administrator";
$admin_username = "admin";
$admin_email    = "admin@example.com";
$admin_pass     = password_hash("admin123", PASSWORD_DEFAULT); 
$admin_role     = "admin";

$check = $conn->prepare("SELECT * FROM users WHERE username = ?");
$check->bind_param("s", $admin_username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo " الأدمن موجود مسبقاً.";
} else {
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $admin_name, $admin_username, $admin_email, $admin_pass, $admin_role);

    if ($stmt->execute()) {
        echo "تم إنشاء الأدمن بنجاح.<br>";
        echo " استخدم البريد: <b>$admin_email</b><br>";
        echo " كلمة المرور: <b>admin123</b><br>";
    } else {
        echo " خطأ أثناء إنشاء الأدمن: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
