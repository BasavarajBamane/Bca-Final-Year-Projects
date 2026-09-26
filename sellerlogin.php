<?php
session_start();
include "db/db.php";

$error = "";

if(isset($_POST['login']))
{
    $input = trim($_POST['fullname']); // name OR mobile
    $password = trim($_POST['password']);

    // ✅ PREPARED STATEMENT
    $sql = "SELECT * FROM sellerregister 
            WHERE fullname=? OR mobile=? 
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();

        // ❌ BLOCKED
        if($row['status'] === 'Blocked'){
            $error = "Your account is blocked by admin!";
        }

        // ❌ NOT APPROVED
        elseif($row['status'] !== 'Approved'){
            $error = "Your account is not approved yet!";
        }

        // ✅ PASSWORD CHECK
        elseif($password === $row['password']){
            $_SESSION['seller_id'] = $row['seller_id'];
            header("Location: sellerdashboard.php");
            exit();
        }
        else{
            $error = "Invalid password!";
        }
    }
    else{
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Seller Login | Krushi Saarthi</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

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
justify-content:center;
align-items:center;
background:url('Sellerbackgroundimage1.png') no-repeat center center/cover;
position:relative;
}

body::before{
content:"";
position:absolute;
width:100%;
height:100%;
background:rgba(0,0,0,0.6);
backdrop-filter:blur(6px);
z-index:0;
}

.container{
width:1000px;
height:550px;
display:flex;
border-radius:30px;
overflow:hidden;
position:relative;
z-index:1;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(20px);
border:1px solid rgba(255,255,255,0.2);
box-shadow:0 40px 80px rgba(0,0,0,0.7);
}

.home-btn{
position:absolute;
top:20px;
left:20px;
background:white;
color:#166534;
padding:6px 14px;
border-radius:20px;
font-size:12px;
text-decoration:none;
font-weight:600;
box-shadow:0 4px 10px rgba(0,0,0,0.3);
z-index:2;
}

.home-btn:hover{
background:#22c55e;
color:white;
}

.left{
width:55%;
padding:60px;
background:linear-gradient(160deg,#166534,#22c55e);
color:white;
display:flex;
flex-direction:column;
justify-content:center;
}

.left img{
width:160px;
margin-bottom:20px;
}

.left h1{
font-size:34px;
margin-bottom:10px;
}

.left p{
font-size:14px;
line-height:1.6;
}

.right{
width:45%;
display:flex;
justify-content:center;
align-items:center;
padding:40px;
}

.login-box{
width:85%;
}

.login-box h3{
text-align:center;
color:#22c55e;
margin-bottom:30px;
}

.input-group{
margin-bottom:20px;
}

.input-group input{
width:100%;
padding:14px;
border-radius:25px;
border:1px solid rgba(255,255,255,0.5);
background:rgba(255,255,255,0.2);
color:#fff;
font-size:14px;
}

.input-group input::placeholder{
color:#eee;
font-weight:500;
}

.input-group input:focus{
background:rgba(255,255,255,0.3);
border:1px solid #22c55e;
}

.options{
text-align:left;
margin-bottom:20px;
}

.options a{
color:#22c55e;
font-size:13px;
text-decoration:none;
}

button{
width:100%;
padding:14px;
border:none;
border-radius:25px;
background:#22c55e;
color:white;
font-weight:bold;
cursor:pointer;
}

.register{
text-align:center;
margin-top:15px;
color:#ddd;
}

.register a{
color:#22c55e;
text-decoration:none;
}

@media(max-width:1000px){
.container{
flex-direction:column;
height:auto;
}

.left,.right{
width:100%;
}
}
</style>
</head>

<body>

<div class="container">

<a href="index.php" class="home-btn">← Home</a>

<div class="left">
<img src="images/h1logo.png">
<h1>Grow Your Agri Business</h1>
<p>
Sell tractors, tools & farming equipment easily.
Manage orders and grow digitally.
</p>
</div>

<div class="right">
<div class="login-box">

<h3>SELLER LOGIN</h3>

<form method="POST">

<div class="input-group">
<input type="text" name="fullname" placeholder="Enter Name or Mobile" required>
</div>

<div class="input-group">
<input type="password" name="password" placeholder="Enter Password" required>
</div>

<div class="options">
<a href="forgetpassword.php">Forgot Password?</a>
</div>

<button type="submit" name="login">LOGIN</button>

</form>

<div class="register">
New Seller? <a href="SellerReg.php">Register Now</a>
</div>

</div>
</div>

</div>

<!-- ✅ ERROR ALERT -->
<?php if(!empty($error)){ ?>
<script>
alert("<?php echo $error; ?>");
</script>
<?php } ?>

</body>
</html>