<?php
include "db/db.php";

if(isset($_POST['reset']))
{
    $mobile = $_POST['mobile'];
    $newpass = $_POST['newpass'];

    $check = mysqli_query($conn,"SELECT * FROM sellerregister WHERE mobile='$mobile'");

    if(mysqli_num_rows($check)>0)
    {
        mysqli_query($conn,"UPDATE sellerregister SET password='$newpass' WHERE mobile='$mobile'");
        echo "<script>alert('Password Updated Successfully'); window.location='sellerlogin.php';</script>";
    }
    else
    {
        echo "<script>alert('Mobile Number Not Found');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<style>
body{
font-family:Poppins;
background:#111;
color:white;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.box{
background:#222;
padding:30px;
border-radius:10px;
width:300px;
text-align:center;
}

input{
width:100%;
padding:10px;
margin:10px 0;
border:none;
border-radius:5px;
}

button{
padding:10px;
width:100%;
background:#22c55e;
border:none;
color:white;
}
</style>
</head>

<body>

<div class="box">
<h3>Reset Password</h3>

<form method="POST">
<input type="text" name="mobile" placeholder="Enter Mobile Number" required>
<input type="password" name="newpass" placeholder="New Password" required>

<button type="submit" name="reset">Reset Password</button>
</form>
</div>

</body>
</html>