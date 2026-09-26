<?php
session_start();
include "db/db.php";

$error="";

if(isset($_POST['login']))
{
$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM adminlogin WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result) == 1)
{
$row = mysqli_fetch_assoc($result);

$mail = $row['mail'];

$_SESSION['mail'] = $mail;

header("Location: Admindash.php");
exit();
}
else
{
$error="Invalid username or password!";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | Premium</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
height:100vh;
display:flex;
background:
linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
url('images/ad2back.jpeg') no-repeat center center/cover;
}

.left-side{
width:55%;
}

.right-side{
width:45%;
display:flex;
justify-content:center;
align-items:center;
}

.login-box{
width:400px;
padding:40px;
background:rgba(255,255,255,0.15);
backdrop-filter:blur(15px);
border-radius:20px;
box-shadow:0 8px 32px rgba(0,0,0,0.3);
border:1px solid rgba(255,255,255,0.3);
text-align:center;
animation:fadeIn 0.8s ease;
}

@keyframes fadeIn{
from{opacity:0; transform:translateX(30px);}
to{opacity:1; transform:translateX(0);}
}

.logo img{
width:150px;
margin-bottom:15px;
}

.login-box h2{
color:white;
margin-bottom:25px;
font-weight:600;
}

.input-group{
position:relative;
margin-bottom:20px;
}

.input-group input{
width:100%;
padding:12px 40px 12px 15px;
border:none;
outline:none;
border-radius:10px;
background:rgba(255,255,255,0.85);
font-size:14px;
}

.toggle-password{
position:absolute;
right:12px;
top:50%;
transform:translateY(-50%);
cursor:pointer;
font-size:14px;
color:#333;
}

.login-box button{
width:100%;
padding:12px;
border:none;
border-radius:30px;
background:linear-gradient(135deg,#4CAF50,#2e7d32);
color:white;
font-size:15px;
font-weight:500;
cursor:pointer;
transition:0.3s;
}

.login-box button:hover{
transform:scale(1.05);
box-shadow:0 5px 20px rgba(0,0,0,0.3);
}

/* Small link-style button */
.bottom-links{
margin-top:10px;
text-align:center;
}

.bottom-links a{
text-decoration:none;
font-size:13px;
color:#ddd;
transition:0.3s;
}

.bottom-links a:hover{
color:#4CAF50;
text-decoration:underline;
}

#error-message{
color:#ffcccc;
margin-top:10px;
font-size:14px;
}

@media(max-width:900px){
body{
flex-direction:column;
}
.left-side,.right-side{
width:100%;
height:50%;
}
}
</style>
</head>

<body>

<div class="left-side"></div>

<div class="right-side">

<div class="login-box">

<div class="logo">
<img src="images/h1logo.png">
</div>

<h2>Admin Login</h2>

<form method="POST">

<div class="input-group">
<input type="text" name="username" placeholder="Enter Username" required>
</div>

<div class="input-group">
<input type="password" id="password" name="password" placeholder="Enter Password" required>
<span class="toggle-password" onclick="togglePassword()">👁</span>
</div>

<button type="submit" name="login">Login</button>

<!-- Small corner link -->
<div class="bottom-links">
    <a href="index.php">← Home</a>
</div>

<div id="error-message">
<?php echo $error; ?>
</div>

</form>

</div>
</div>

<script>
function togglePassword(){
const password = document.getElementById("password");
password.type = password.type === "password" ? "text" : "password";
}
</script>

</body>
</html>