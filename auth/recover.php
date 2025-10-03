<?php
session_start();
include("../config/connect.php");

if(isset($_POST['recover'])){
    $email = $_POST['email'];

    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($query) > 0){
 
        $_SESSION['recover_email'] = $email;

        header("Location: reset_password.php");
        exit();
    } else {
        echo "<p style='color:red;'>Email not found.</p>";
    }
}
?>

<h2>Recover Password</h2>
<form method="post">
    <input type="email" name="email" placeholder="Enter your email" required>
    <input type="submit" name="recover" value="Recover Password">
</form>
