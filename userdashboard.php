<?php
session_start();
include "db/db.php";

// ✅ Check if user is logged in
if(!isset($_SESSION['email'])){
    header("Location: userlogin.php");
    exit();
}

$email = $_SESSION['email'];
$success = "";

/* FETCH USER */
$stmt = mysqli_prepare($conn, "SELECT * FROM usersregister WHERE email=?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

/* UPDATE PROFILE */
if(isset($_POST['update_profile'])){
    $name = $_POST['fullname'];
    $mobile = $_POST['mobile'];
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $profile_img = $user['profile_img'];

    if(isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] == 0){
        $file_name = time() . "_" . basename($_FILES['profile_img']['name']);
        $file_tmp = $_FILES['profile_img']['tmp_name'];
        $path = "uploads/" . $file_name;

        move_uploaded_file($file_tmp, $path);
        $profile_img = $path;
    }

    if(!empty($new)){
        if($current === $user['password']){
            if($new === $confirm){
                $stmt = mysqli_prepare($conn, "UPDATE usersregister SET fullname=?, mobile=?, password=?, profile_img=? WHERE email=?");
                mysqli_stmt_bind_param($stmt, "sssss", $name, $mobile, $new, $profile_img, $email);
                mysqli_stmt_execute($stmt);
                $success = "Profile updated successfully!";
            } else {
                $success = "New passwords do not match!";
            }
        } else {
            $success = "Current password is incorrect!";
        }
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE usersregister SET fullname=?, mobile=?, profile_img=? WHERE email=?");
        mysqli_stmt_bind_param($stmt, "ssss", $name, $mobile, $profile_img, $email);
        mysqli_stmt_execute($stmt);
        $success = "Profile updated successfully!";
    }

    $stmt = mysqli_prepare($conn, "SELECT * FROM usersregister WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
}

/* SEARCH */
$search = "";
if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if($search != ""){
    $product_sql = "SELECT * FROM products 
                    WHERE status='Active' 
                    AND product_name LIKE '%$search%' 
                    ORDER BY created_at DESC";
} else {
    $product_sql = "SELECT * FROM products 
                    WHERE status='Active' 
                    ORDER BY created_at DESC";
}

$product_result = mysqli_query($conn, $product_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Krushi Saarthi | Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:linear-gradient(135deg,#e6f4ea,#f4f9ff);}

/* HEADER */
.header{position:fixed;top:0;width:100%;height:70px;background:#fff;display:flex;align-items:center;justify-content:space-between;padding:0 25px;box-shadow:0 5px 20px rgba(0,0,0,0.1);z-index:1000;}
.header-left{display:flex;align-items:center;gap:25px;}
.logo-container img{width:150px;height:55px;}
.nav{display:flex;gap:20px;}
.nav a{text-decoration:none;color:#333;font-weight:500;}
.nav a:hover{color:#2c7a2c;}
.search-box input{padding:8px 15px;border-radius:20px;border:1px solid #ccc;width:250px;}
.header-right{display:flex;align-items:center;gap:15px;}
.profile{display:flex;align-items:center;gap:8px;cursor:pointer;}
.profile img{width:42px;height:42px;border-radius:50%;border:2px solid #2c7a2c;}
.logout-btn{background:#ff4b2b;color:#fff;border:none;padding:8px 15px;border-radius:20px;cursor:pointer;}

/* MAIN */
.main{margin-top:90px;padding:20px;}
.welcome{background:#fff;padding:25px;border-radius:15px;margin-bottom:25px;box-shadow:0 10px 25px rgba(0,0,0,0.1);}

/* 🔥 FIXED PRODUCTS LAYOUT */
.products{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    justify-content:flex-start; /* align properly */
}

/* Better responsive card sizing */
/* CARD FIXED LAYOUT */
.card{
    width:23%;
    min-width:250px;
    background:#fff;
    padding:15px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
    transition:0.3s;

    display:flex;
    flex-direction:column;
    justify-content:space-between; /* push buy button to bottom */
    min-height:350px; /* optional, ensures consistent card height */
}

.card img{
    width:100%;
    height:140px;
    object-fit:cover;
    border-radius:10px;
    margin-bottom:10px;
}

.card h4{
    margin-top:10px;
    font-size:16px;
    font-weight:600;
}

.card p{
    margin-top:5px;
    color:#2c2c2c;
    font-weight:500;
    flex-grow:1; /* makes the text section grow and push button down */
}

.buy-btn{
    display:inline-block;
    margin-top:10px;
    padding:8px 15px;
    background:#2c7a2c;
    color:#fff;
    border-radius:20px;
    text-decoration:none;
    font-size:14px;
    align-self:center; /* centers the button horizontally */
}
.buy-btn:hover{
    background:#1e5e1e;
}
/* MOBILE FIX */
@media(max-width:900px){
    .card{width:48%;}
}
@media(max-width:500px){
    .card{width:100%;}
}

/* MODAL (unchanged) */
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);justify-content:center;align-items:center;z-index:2000;}
.modal-content{background:#fff;padding:25px;width:400px;border-radius:12px;}
.modal-content input{width:100%;padding:8px;margin-bottom:10px;border-radius:6px;border:1px solid #ccc;}
.save-btn{width:100%;padding:10px;background:#2c7a2c;color:#fff;border:none;border-radius:6px;cursor:pointer;}
.close-btn{float:right;cursor:pointer;color:red;font-weight:bold;}

/* FOOTER */
.footer{
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background:#2c7a2c;
    color:#fff;
	height:30px;
    text-align:center;
    padding:8px 19px;
    font-size:14px;
    box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
    z-index: 1000;
}

.footer a{
    color: #fff;
    text-decoration: underline;
    margin: 0 5px;
}

.footer a:hover{
    color: #d1ffd1;
}
</style>
</head>

<body>

<?php if($success!=""){ ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>

<!-- HEADER -->
<div class="header">
<div class="header-left">
    <div class="logo-container">
        <img src="images/h1logo.png">
    </div>
    <div class="nav">
        <a href="#">Home</a>
        <a href="myorders.php">My Orders</a>
    </div>
</div>

<div class="search-box">
    <form method="GET">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo $search; ?>">
    </form>
</div>

<div class="header-right">
    <div class="profile" onclick="openProfile()">
        <img src="<?php echo $user['profile_img']; ?>">
        <span><?php echo $user['fullname']; ?></span>
    </div>
    <form action="logout.php" method="POST">
        <button class="logout-btn">Logout</button>
    </form>
</div>
</div>

<!-- MAIN -->
<div class="main">
<div class="welcome">
<h2>👋 Welcome, <?php echo $user['fullname']; ?></h2>
<p>🌾 Explore modern agriculture tools & machines</p>
</div>

<div class="category">
<h2>🚜 Products</h2>

<div class="products">

<?php if(mysqli_num_rows($product_result) == 0){ ?>
    <p style="color:red;">No products found</p>
<?php } ?>

<?php while($row = mysqli_fetch_assoc($product_result)){ 
    $stock = (int)$row['quantity'];
?>

<div class="card" style="border:2px;">
    <img src="<?php echo !empty($row['image']) ? $row['image'] : 'images/no-image.png'; ?>" alt="<?php echo $row['product_name']; ?>">

    <!-- Product name in light black -->
    <h4 style="color:#2c2c2c;"><?php echo $row['product_name']; ?></h4>

    <p>
  <b style="color:black;">Brand :</b> 
  <span style="color:#2c2c2c;"><?php echo $row['brand']; ?></span>
</p>

<p>
  <b style="color:black;">Category :</b> 
  <span style="color:#2c2c2c;"><?php echo $row['category']; ?></span>
</p>

<?php if(!empty($row['hp'])){ ?>
<p>
  <b style="color:black;">HP :</b> 
  <span style="color:#2c2c2c;"><?php echo $row['hp']; ?></span>
</p>
<?php } ?>
    <p><b>Price : </b> <span style="color:black;">₹<?php echo number_format($row['price']); ?></span></p>

    <?php if($stock <= 0){ ?>
        <p style="color:red;font-weight:bold;">Out of Stock</p>
        <a class="buy-btn" style="background:gray;">Not Available</a>
    <?php } else { ?>
        <a href="buy.php?id=<?php echo $row['id']; ?>" class="buy-btn" style="background:green;">Buy Now</a>
    <?php } ?>
</div>

<?php } ?>
</div></div>
</div>
</div>

<!-- PROFILE MODAL (unchanged) -->
<div class="modal" id="profileModal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeProfile()">X</span>
        <h3>Update Profile</h3>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" required>
            <input type="text" name="mobile" value="<?php echo $user['mobile']; ?>">
            <input type="file" name="profile_img">
            <input type="password" name="current_password" placeholder="Current Password">
            <input type="password" name="new_password" placeholder="New Password">
            <input type="password" name="confirm_password" placeholder="Confirm Password">
            <button type="submit" name="update_profile" class="save-btn">Save Changes</button>
        </form>
    </div>
</div>
<!-- FOOTER -->
<div class="footer">
    <p>© 2026 Krushi Saarthi. All rights reserved</p>
</div>
<script>
function openProfile(){ document.getElementById("profileModal").style.display="flex"; }
function closeProfile(){ document.getElementById("profileModal").style.display="none"; }
window.onclick = function(e){
    if(e.target == document.getElementById("profileModal")){
        closeProfile();
    }
}
</script>

</body>
</html>