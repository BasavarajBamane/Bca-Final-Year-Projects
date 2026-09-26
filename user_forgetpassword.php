<?php
session_start();
include "db/db.php";

$error = "";
$success = false;

if(isset($_POST['submit']))
{
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if($new_password !== $confirm_password){
        $error = "Passwords do not match!";
    } else {
        // CHECK IF EMAIL EXISTS
        $stmt = mysqli_prepare($conn, "SELECT * FROM usersregister WHERE email=?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0){
            // UPDATE PASSWORD
            $stmt2 = mysqli_prepare($conn, "UPDATE usersregister SET password=? WHERE email=?");
            mysqli_stmt_bind_param($stmt2, "ss", $new_password, $email);
            if(mysqli_stmt_execute($stmt2)){
                $success = true; // Trigger popup
            } else {
                $error = "Failed to update password. Try again!";
            }
        } else {
            $error = "Email not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Krushi Saarthi | Forgot Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{
    font-family:"Segoe UI",sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    background:#f0f4f2;
}
.box{
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    width:400px;
}
h2{text-align:center;margin-bottom:20px;}
input{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid #ccc;
}
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#1e6f3d;
    color:#fff;
    font-weight:bold;
    cursor:pointer;
}
.error{color:red;text-align:center;margin-bottom:10px;}
a{color:#1e6f3d;text-decoration:none;}
a:hover{text-decoration:underline;}
</style>
</head>
<body>

<div class="box">
<h2>Forgot Password</h2>

<?php if($error != ""){ echo "<div class='error'>$error</div>"; } ?>

<form method="POST">
<input type="email" name="email" placeholder="Enter your email" required>
<input type="password" name="new_password" placeholder="New Password" required>
<input type="password" name="confirm_password" placeholder="Confirm Password" required>
<button type="submit" name="submit">Reset Password</button>
</form>

</div>

<?php
if($success){
    // JS popup and redirect
    echo "<script>
        alert('Password changed successfully!');
        window.location.href='userlogin.php';
    </script>";
}
?>

</body>
</html>