<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "db/db.php";

if (!isset($_SESSION['mail'])) {
    header("Location: adminlogin.php");
    exit();
}

$mail = $_SESSION['mail'];

// FETCH ADMIN
$stmt = mysqli_prepare($conn, "SELECT * FROM adminlogin WHERE mail=?");
mysqli_stmt_bind_param($stmt, "s", $mail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);

// DEFAULT IMAGE
if (empty($admin['profile_img'])) {
    $admin['profile_img'] = "images/admin.png";
}

// UPLOAD
if (isset($_POST['upload_img'])) {
    if (!empty($_FILES['profile_img']['name']) && $_FILES['profile_img']['error'] == 0) {

        $ext = strtolower(pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (in_array($ext,$allowed)) {

            if (!is_dir("uploads")) {
                mkdir("uploads",0777,true);
            }

            $filePath = "uploads/".time()."_".basename($_FILES['profile_img']['name']);

            if (move_uploaded_file($_FILES['profile_img']['tmp_name'],$filePath)) {

                $stmt = mysqli_prepare($conn,"UPDATE adminlogin SET profile_img=? WHERE mail=?");
                mysqli_stmt_bind_param($stmt,"ss",$filePath,$mail);
                mysqli_stmt_execute($stmt);
            }
        }
    }
    header("Location: admindash.php");
    exit();
}

// DELETE
if (isset($_POST['delete_img'])) {
    $default = "images/admin.png";

    $stmt = mysqli_prepare($conn,"UPDATE adminlogin SET profile_img=? WHERE mail=?");
    mysqli_stmt_bind_param($stmt,"ss",$default,$mail);
    mysqli_stmt_execute($stmt);

    header("Location: admindash.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    display:flex;
    background:#f1f5f9;
}

/* SIDEBAR */
.sidebar{
    width:260px;
    height:100vh;
    background:#0f172a;
    color:#fff;
    padding:20px;
    position:fixed;
    display:flex;
    flex-direction:column;
    z-index: 100; /* Ensure sidebar stays above content */
}

/* LOGO */
.logo{
    margin-bottom:25px;
    padding:10px;
    border-radius:12px;
}

.logo img{
    width:160px;
    max-width:100%;
    height:auto;
    object-fit:contain;
}

/* PROFILE */
.profile{
    display:flex;
    flex-direction:column;
    align-items:center;
    margin-bottom:20px;
    cursor:pointer;
    position:relative;
    z-index: 101; /* Ensure modal triggers correctly */
}

.profile img{
    width:85px;
    height:85px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:10px;
    border:3px solid #6366f1;
}

.profile h4{
    font-size:15px;
}

.profile p{
    font-size:12px;
    color:#aaa;
}

/* MENU */
.menu{
    margin-top:10px;
    text-align:left;
    flex:1;
    position:relative;
    z-index: 99;
}

.menu a{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px;
    margin-bottom:8px;
    border-radius:8px;
    text-decoration:none;
    color:#bbb;
    transition:0.3s;
}

.menu a:hover{
    background:#1e293b;
    color:#fff;
}

/* Dropdown submenu */
.dropdown .submenu a{
    display:block;
    padding:8px 20px;
    font-size:14px;
    color:#ccc;
}
.dropdown .submenu a:hover{
    background:#1e293b;
    color:#fff;
}

/* FIXED LOGOUT */
.logout{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:6px;
    padding:10px;
    background:#ef4444;
    border-radius:6px;
    font-size:13px;
    color:#fff;
    text-decoration:none;
    position:absolute;
    bottom:20px;
    left:20px;
    right:20px;
    z-index: 100;
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    justify-content:center;
    align-items:center;
    z-index: 999; /* Ensure modal is above everything */
}

.modal-box{
    background:#fff;
    padding:25px;
    border-radius:12px;
    text-align:center;
    width:350px;
    max-width:90%;
    z-index: 1000;
}

.preview{
    width:90px;
    height:90px;
    border-radius:50%;
    margin-bottom:10px;
}

.btn{
    padding:8px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    color:#fff;
}

.upload{background:#6366f1;}
.delete{background:#ef4444;}
.close{background:#64748b;}

.btn-group{
    display:flex;
    justify-content:center;
    gap:10px;
    margin-top:10px;
}
</style>
</head>

<body>

<div class="sidebar">

<div class="logo">
    <img src="images/h1logo.png">
</div>

<div class="profile" onclick="openModal()">
    <img src="<?php echo $admin['profile_img']; ?>" alt="Profile Image">
    <h4><?php echo $admin['username']; ?></h4>
    <p><?php echo $admin['mail']; ?></p>
</div>

<div class="menu">
    <a href="admindash.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="adminseller.php"><i class="fas fa-user"></i> Sellers</a>
    
    <!-- Orders Dropdown -->
    <div class="dropdown">
        <a href="javascript:void(0)"><i class="fas fa-shopping-cart"></i> Orders <i class="fas fa-caret-down" style="margin-left:auto;"></i></a>
        <div class="submenu" style="display:none; flex-direction:column;">
            <a href="admin_current_orders.php"><i class="fas fa-circle"></i> Current Orders</a>
            <a href="admin_completed_orders.php"><i class="fas fa-circle"></i> Completed Orders</a>
            <a href="admin_cancelorders.php"><i class="fas fa-circle"></i> Cancelled Orders</a>
       </div>
    </div>
	
	<a href="admin_addrents.php"><i class="fas fa-chart-line"></i> Rents </a>
    <a href="feedback_report.php"><i class="fas fa-chart-line"></i> FeedBack Reports</a>
</div>

<a href="logout.php" class="logout">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
<div class="modal-box">

<img id="preview" src="<?php echo $admin['profile_img']; ?>" class="preview" alt="Preview">

<h3><?php echo $admin['username']; ?></h3>
<p><?php echo $admin['mail']; ?></p>

<form method="POST" enctype="multipart/form-data">
<input type="file" name="profile_img" onchange="previewImage(event)" required>

<div class="btn-group">
<button type="submit" name="upload_img" class="btn upload">Upload</button>
<button type="submit" name="delete_img" class="btn delete">Delete</button>
</div>
</form>

<button onclick="closeModal()" class="btn close">Close</button>

</div>
</div>

<script>
function openModal(){
    document.getElementById("modal").style.display="flex";
}
function closeModal(){
    document.getElementById("modal").style.display="none";
}
function previewImage(e){
    let reader = new FileReader();
    reader.onload = function(){
        document.getElementById("preview").src = reader.result;
    }
    reader.readAsDataURL(e.target.files[0]);
}

// Sidebar dropdown toggle
document.querySelectorAll(".dropdown > a").forEach(d => {
    d.addEventListener("click", function(e){
        e.preventDefault();
        let submenu = this.nextElementSibling;
        submenu.style.display = (submenu.style.display === "flex" ? "none" : "flex");
    });
});
</script>

</body>
</html>