<?php
session_start();
include "db/db.php";

$error = "";

if(isset($_POST['submit']))
{
    $input = trim($_POST['fullname']); // email OR mobile OR fullname
    $password = trim($_POST['password']);

    // SEARCH USER
    $sql = "SELECT * FROM usersregister WHERE email=? OR mobile=? OR fullname=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $input, $input, $input);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_assoc($result);

        // PASSWORD CHECK
        if($password == $row['password'])
        {
            $_SESSION['email'] = $row['email'];
            header("Location: userdashboard.php");
            exit();
        }
        else
        {
            $error = "Invalid Password!";
        }
    }
    else
    {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Krushi Saarthi | Buyer Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
:root {
    --green-dark:#1e6f3d;
}

/* Basic Reset */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Segoe UI",sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:url('https://images.unsplash.com/photo-1500937386664-56d1dfef3854') no-repeat center/cover;
}

body::before{
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background:linear-gradient(45deg, rgba(0,0,0,0.6), rgba(30,111,61,0.6));
}

.login-box{
    position:relative;
    width:420px;
    padding:35px;
    border-radius:15px;
    backdrop-filter:blur(15px);
    background:rgba(255,255,255,0.15);
    color:#fff;
    box-shadow:0 15px 40px rgba(0,0,0,0.4);
    z-index:2;
}

/* NEW: TOP LEFT BUTTON */
.back-home{
    position:absolute;
    top:15px;
    left:15px;
    padding:6px 12px;
    border-radius:6px;
    background:#ffffff;
    color:#1e6f3d;
    font-size:12px;
    font-weight:bold;
    text-decoration:none;
}

.back-home:hover{
    background:#e6f4ea;
}

.logo{
    text-align:center;
    margin-bottom:20px;
}

.logo img{
    width:150px;
}

h2{
    text-align:center;
    margin-bottom:20px;
}

.form-group{
    margin-bottom:15px;
}

label{
    font-size:13px;
    display:block;
    margin-bottom:5px;
}

input{
    width:100%;
    padding:10px;
    border-radius:6px;
    border:none;
}

.password-box{
    position:relative;
}

.toggle{
    position:absolute;
    right:10px;
    top:35px;
    cursor:pointer;
    color:#000;
}

button{
    width:100%;
    padding:12px;
    margin-top:15px;
    border:none;
    border-radius:8px;
    background:linear-gradient(135deg,var(--green-dark),#2fa866);
    color:#fff;
    font-weight:bold;
    cursor:pointer;
}

.error{
    text-align:center;
    color:#ffb3b3;
    margin-bottom:10px;
}

.register, .forgot{
    display:block;
    text-align:center;
    margin-top:10px;
    color:#fff;
    font-size:13px;
    text-decoration:none;
}

.register:hover, .forgot:hover{
    text-decoration:underline;
    color:#cce6cc;
}
</style>
</head>

<body>

<div class="login-box">

<!-- TOP LEFT BUTTON -->
<a href="index.php" class="back-home">⬅ Home</a>

<div class="logo">
<img src="images/h1logo.png">
</div>

<h2>User / Buyer Login</h2>

<!-- ERROR -->
<?php if($error != ""){ ?>
<div class="error"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">

<div class="form-group">
<label>Email / Mobile / Name</label>
<input type="text" name="fullname" required>
</div>

<div class="form-group password-box">
<label>Password</label>
<input type="password" id="password" name="password" required>
<span class="toggle" onclick="togglePassword()">👁</span>
</div>

<button type="submit" name="submit">LOGIN</button>

<a href="user_forgetpassword.php" class="forgot">Forgot Password?</a>
<a href="userregister.php" class="register">New User? Create Account</a>

</form>

</div>

<script>
function togglePassword(){
    let p=document.getElementById("password");
    p.type=p.type==="password"?"text":"password";
}
</script>

</body>
</html>