<?php
include "db/db.php";

$success = false;

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // OPTIONAL: store in database (if table exists)
    $query = "INSERT INTO contact(name,email,message) VALUES('$name','$email','$message')";
    $result = mysqli_query($conn,$query);

    if($result){
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Us | Krushi Saarthi</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

/* RESET */
body{
margin:0;
font-family:'Poppins',sans-serif;
background:#f1f5f9;
}

/* HEADER */
.header{
position:fixed;
top:0;
left:0;
width:100%;
height:65px;
background:#064e3b;
color:#fff;
display:flex;
justify-content:space-between;
align-items:center;
padding:0 40px;
z-index:1000;
}

/* LOGO */
.logo{
display:flex;
align-items:center;
}

.logo-img{
height:45px;
}

/* NAV */
.nav a{
color:#fff;
margin-left:20px;
text-decoration:none;
font-size:14px;
}

.nav a:hover{
color:#facc15;
}

/* MAIN */
.main{
padding-top:100px;
padding-bottom:80px;
max-width:1100px;
margin:auto;
}

/* MAP */
.map{
width:100%;
height:300px;
border:none;
border-radius:10px;
margin-bottom:30px;
}

/* CONTACT */
.contact-container{
display:flex;
gap:30px;
flex-wrap:wrap;
}

/* FORM */
.form-box{
flex:1;
background:#fff;
padding:25px;
border-radius:10px;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

.form-box h2{
margin-bottom:15px;
color:#064e3b;
}

.input-group{
margin-bottom:12px;
}

.input-group input,
.input-group textarea{
width:100%;
padding:10px;
border:1px solid #ccc;
border-radius:6px;
}

textarea{
resize:none;
height:100px;
}

/* BUTTON */
.btn{
width:100%;
padding:10px;
border:none;
background:#16a34a;
color:#fff;
border-radius:20px;
font-weight:600;
cursor:pointer;
}

.btn:hover{
background:#15803d;
}

/* INFO */
.info-box{
flex:1;
background:#064e3b;
color:#fff;
padding:25px;
border-radius:10px;
}

.info-box h2{
color:#facc15;
}

/* FOOTER */
.footer{
position:fixed;
bottom:0;
left:0;
width:100%;
background:#064e3b;
color:#fff;
text-align:center;
padding:10px;
}

</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
<div class="logo">
<a href="index.php">
<img src="images/h1logo.png" class="logo-img">
</a>
<span style="margin-left:10px;color:#facc15;font-weight:700;">
</span>
</div>

<div class="nav">
<a href="index.php">Home</a>
</div>
</div>

<!-- MAIN -->
<div class="main">

<!-- MAP -->
<iframe class="map"
src="Your Google Map Address"
allowfullscreen>
</iframe>

<div class="contact-container">

<!-- FORM -->
<div class="form-box">
<h2>Contact Us</h2>

<form method="POST">

<div class="input-group">
<input type="text" name="name" placeholder="Your Name" required>
</div>

<div class="input-group">
<input type="email" name="email" placeholder="Your Email" required>
</div>

<div class="input-group">
<textarea name="message" placeholder="Your Message" required></textarea>
</div>

<button class="btn" type="submit" name="submit">Send Message</button>

</form>
</div>

<!-- INFO -->
<div class="info-box">
<h2>Get In Touch</h2>
<p><b>Address:</b> Karnataka, India</p>
<p><b>Phone:</b> +91 1234567890</p>
<p><b>Email:</b> Unkown@gmail.com</p>
<p><b>Hours:</b> 10 AM - 6 PM</p>
</div>

</div>
</div>

<!-- FOOTER -->
<div class="footer">
© <?php echo date("Y"); ?> Krushi Saarthi
</div>

<!-- SUCCESS POPUP + REDIRECT -->
<?php if($success){ ?>
<script>
alert("Our team will contact you soon...");
window.location.href = "index.php";
</script>
<?php } ?>

</body>
</html>