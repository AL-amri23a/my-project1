<?php
include("connect.php"); 

$sql = "CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
)";
if($conn->query($sql) === TRUE){
    echo "تم إنشاء جدول الأقسام بنجاح!<br>";
} else {
    echo "حدث خطأ: " . $conn->error;
}

$departments = ['قسم التقنية','قسم الموارد البشرية','قسم المالية'];
foreach($departments as $dept){
    $stmt = $conn->prepare("INSERT INTO departments (name) VALUES (?)");
    $stmt->bind_param("s", $dept);
    $stmt->execute();
}
echo "تم إضافة الأقسام الافتراضية.";
?>
