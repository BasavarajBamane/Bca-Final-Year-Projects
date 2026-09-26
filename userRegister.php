<?php
session_start();
include "db/db.php";

$error = "";

if(isset($_POST['register']))
{
    // ✅ CHECK TERMS
    if(!isset($_POST['terms'])){
        $error = "Please accept Terms & Conditions!";
    } else {

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile   = mysqli_real_escape_string($conn, $_POST['mobile']);
    
    // ❌ NO HASH (as you requested)
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check existing user
    $check = mysqli_query($conn, 
    "SELECT * FROM usersregister WHERE email='$email' OR mobile='$mobile'");

    if(mysqli_num_rows($check) > 0)
    {
        $error = "User already registered with this Email or Mobile!";
    }
    else
    {
        // Insert into DB
        $insert = mysqli_query($conn, 
        "INSERT INTO usersregister (fullname,email,mobile,password,created_at)
        VALUES ('$fullname','$email','$mobile','$password',NOW())");

        if($insert)
        {
            $_SESSION['fullname'] = $fullname;
            header("Location: userlogin.php");
            exit();
        }
        else
        {
            $error = "Something went wrong. Try again!";
        }
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Krushi Saarthi | User Register</title>

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
background:url('https://images.unsplash.com/photo-1500937386664-56d1dfef3854') no-repeat center center/cover;
display:flex;
justify-content:center;
align-items:center;
}

body::before{
content:"";
position:absolute;
width:100%;
height:100%;
background:linear-gradient(to right,rgba(0,0,0,0.7),rgba(0,0,0,0.4));
}

.wrapper{
position:relative;
width:880px;
height:520px;
display:flex;
border-radius:25px;
overflow:hidden;
box-shadow:0 30px 60px rgba(0,0,0,0.6);
}

.left{
flex:1;
background:linear-gradient(135deg,#1b5e20,#4caf50);
color:#fff;
padding:40px;
display:flex;
flex-direction:column;
justify-content:center;
position:relative;
}

.left h1{
font-size:32px;
margin-bottom:15px;
}

.left p{
font-size:14px;
line-height:1.6;
}

.tractor{
position:absolute;
bottom:20px;
left:-120px;
width:110px;
animation:moveTractor 10s linear infinite;
}

@keyframes moveTractor{
0%{left:-120px;}
100%{left:100%;}
}

.right{
flex:1;
background:rgba(255,255,255,0.12);
backdrop-filter:blur(25px);
padding:40px;
color:#fff;
display:flex;
flex-direction:column;
justify-content:center;
}

.logo{
text-align:center;
margin-bottom:10px;
}

.logo img{
width:120px;
}

.right h2{
text-align:center;
margin-bottom:15px;
}

.input-box{
position:relative;
margin-bottom:18px;
}

.input-box input{
width:100%;
padding:14px 12px;
border:none;
border-radius:10px;
outline:none;
font-size:14px;
}

.input-box label{
position:absolute;
left:14px;
top:14px;
font-size:13px;
color:#666;
pointer-events:none;
transition:0.3s;
}

.input-box input:focus + label,
.input-box input:valid + label{
top:-8px;
left:10px;
background:#1b5e20;
color:#fff;
padding:2px 6px;
font-size:11px;
border-radius:4px;
}

button{
width:100%;
padding:14px;
border:none;
border-radius:10px;
background:linear-gradient(45deg,#1b5e20,#4caf50);
color:#fff;
font-weight:600;
font-size:15px;
cursor:pointer;
}

.footer{
text-align:center;
margin-top:12px;
font-size:13px;
}

.footer a{
color:#4caf50;
text-decoration:none;
font-weight:600;
}

.error{
color:#ffb3b3;
text-align:center;
margin-bottom:10px;
font-weight:500;
}

.terms-box{
font-size:13px;
margin-bottom:15px;
}

.terms-box a{
color:#4caf50;
cursor:pointer;
text-decoration:underline;
}

/* MODAL */
.modal{
display:none;
position:fixed;
z-index:999;
left:0;
top:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.8);
}

.modal-content{
background:#fff;
color:#000;
margin:5% auto;
padding:20px;
width:60%;
max-height:70%;
overflow-y:auto;
border-radius:10px;
}

.close{
float:right;
font-size:22px;
cursor:pointer;
font-weight:bold;
color:red;
}

</style>
</head>

<body>

<div class="wrapper">

<div class="left">
<h1>Welcome to Krushi Saarthi 🌾</h1>
<p>
India's trusted AgriTech platform for buying, selling and renting farming equipment.
</p>
<img src="https://cdn-icons-png.flaticon.com/512/1995/1995501.png" class="tractor">
</div>

<div class="right">

<div class="logo">
<img src="images/h1logo.png">
</div>

<h2>Create Account</h2>

<?php if($error != ""){ ?>
<div class="error"><?php echo $error; ?></div>
<?php } ?>

<form method="POST" action="" onsubmit="return validateForm()">

<div class="input-box">
<input type="text" name="fullname" required>
<label>Full Name</label>
</div>

<div class="input-box">
<input type="email" name="email" required>
<label>Email Address</label>
</div>

<div class="input-box">
<input type="tel" name="mobile" pattern="[0-9]{10}" maxlength="10" required>
<label>Mobile Number</label>
</div>

<div class="input-box">
<input type="password" name="password" required>
<label>Password</label>
</div>

<div class="terms-box">
<input type="checkbox" id="terms" name="terms">
I agree to <a onclick="openModal()">Terms & Conditions</a>
</div>

<button type="submit" name="register">Register</button>

</form>

<div class="footer">
Already have account? <a href="login.php">Login</a>
</div>

</div>
</div>

<!-- TERMS MODAL -->
<div id="termsModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal()">&times;</span>

<h2>Terms & Conditions</h2>

<p><b>1.</b> Users must provide accurate information.</p>
<p><b>2.</b> Orders once placed cannot be cancelled after dispatch.</p>
<p><b>3.</b> If order is <b>Out for Delivery</b>, cancellation is strictly not allowed.</p>
<p><b>4.</b> If customer refuses to accept delivery, action may be taken according to platform policies.</p>
<p><b>5.</b> Misuse of platform may lead to account suspension.</p>
<p><b>6.</b> By registering, you agree to all terms.</p>

</div>
</div>

<script>
function openModal(){
document.getElementById("termsModal").style.display = "block";
}

function closeModal(){
document.getElementById("termsModal").style.display = "none";
}

function validateForm(){
let check = document.getElementById("terms").checked;

if(!check){
alert("Please accept Terms & Conditions");
return false;
}
return true;
}
</script>

</body>
</html>