<?php
// Connect to database
include "db/db.php";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if (isset($_POST['register'])) {

    //$seller_id = $_POST['seller_id'];       // Seller ID
    $fullname = $_POST['fullname'];
    $mobile = $_POST['mobile'];
    $gmail = $_POST['gmail'];
    $shop_name = $_POST['shop_name'];       // Shop Name
    $password = $_POST['password'];

    // Handle file upload
    if (isset($_FILES['panphoto']) && $_FILES['panphoto']['error'] == 0) {

        $uploadDir = "images/";

        // Create folder if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = basename($_FILES['panphoto']['name']);
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

        // Create a unique name to avoid overwriting
        $newFileName = $fullname . "_" . time() . "." . $fileExt;
        $uploadFile = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['panphoto']['tmp_name'], $uploadFile)) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO sellerregister(fullname, mobile, gmail, shop_name, panphoto, password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss",$fullname, $mobile, $gmail, $shop_name, $newFileName, $password);

           if ($stmt->execute()) {
               echo "<script>alert('Registration Successful'); window.location='sellerlogin.php';</script>";
           } else {
               echo "<script>alert('Database Error: " . $stmt->error . "');</script>";
           }
          $stmt->close();
        } else {
            echo "<script>alert('Failed to upload PAN photo');</script>";
        }

    } else {
        echo "<script>alert('Please upload a valid PAN photo');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seller Registration</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:url("images/adback.jpeg") no-repeat center center/cover;
}

form{
    background:rgba(255,255,255,0.95);
    padding:30px;
    border-radius:12px;
    box-shadow:0 0 25px rgba(0,0,0,0.3);
    width:500px;
}

.logo{
    display:block;
    margin:0 auto 10px;
    width:180px;
    height:70px;
}

h2{
    text-align:center;
    margin-bottom:18px;
    color:#333;
}

label{
    display:block;
    margin-top:12px;
    font-weight:bold;
}

input{
    width:100%;
    padding:10px;
    margin-top:5px;
    border:1px solid #ccc;
    border-radius:6px;
    outline:none;
    transition:0.3s;
}

input:focus{
    border-color:#11998e;
    box-shadow:0 0 5px rgba(17,153,142,0.4);
}

input[type="file"]{
    padding:5px;
}

button{
    width:100%;
    padding:12px;
    margin-top:20px;
    border:none;
    background:green;
    color:white;
    font-size:16px;
    border-radius:6px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#0b7d73;
}

.login-link{
    text-align:center;
    margin-top:15px;
    font-size:14px;
}

.login-link a{
    color:#11998e;
    text-decoration:none;
    font-weight:bold;
}

.login-link a:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<form method="POST" enctype="multipart/form-data">

<img src="images/h1logo.png" alt="Logo" class="logo">

<h2>Seller Registration</h2>



<label>Full Name</label>
<input type="text" name="fullname" placeholder="Enter your name" required>

<label>Mobile Number</label>
<input type="tel" name="mobile" pattern="[0-9]{10}" placeholder="Enter 10-digit mobile number" required>

<label>Gmail</label>
<input type="email" name="gmail" placeholder="Enter your Gmail" required>

<label>Shop Name</label>
<input type="text" name="shop_name" placeholder="Enter your Shop Name" required>

<label>Upload PAN Photo</label>
<input type="file" name="panphoto" accept="image/*" required>

<label>Password</label>
<input type="password" name="password" placeholder="Create password" required>

<button type="submit" name="register">Register</button>

<div class="login-link">
Already have an account?
<a href="sellerlogin.php">Login</a>
</div>

</form>

</body>
</html>