<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "db/db.php";

/* ENABLE ERRORS */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* CHECK DB CONNECTION */
if (!$conn) {
    die("Database Connection Failed");
}

/* CHECK ADMIN SESSION */
if (!isset($_SESSION['mail'])) {
    header("Location: adminlogin.php");
    exit();
}

$mail = $_SESSION['mail'];

/* FETCH ADMIN */
$stmt = mysqli_prepare($conn, "SELECT * FROM adminlogin WHERE mail=?");
mysqli_stmt_bind_param($stmt, "s", $mail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);

/* DEFAULT IMAGE */
if (empty($admin['profile_img'])) {
    $admin['profile_img'] = "images/admin.png";
}

/* FETCH FEEDBACKS */
$feedbacks = [];
$res = mysqli_query($conn, "SELECT * FROM feedback ORDER BY id DESC");
if ($res) {
    $feedbacks = mysqli_fetch_all($res, MYSQLI_ASSOC);
}
$count = count($feedbacks);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Feedback Reports</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="style.css">

<style>


/* SIDEBAR */
.sidebar{width:260px;height:100vh;background:#0f172a;color:#fff;padding:20px;position:fixed;display:flex;flex-direction:column;z-index:100;}
.logo{margin-bottom:25px;padding:10px;border-radius:12px;}
.logo img{width:160px;max-width:100%;height:auto;object-fit:contain;}
.profile{display:flex;flex-direction:column;align-items:center;margin-bottom:20px;cursor:pointer;position:relative;z-index:101;}
.profile img{width:85px;height:85px;border-radius:50%;object-fit:cover;margin-bottom:10px;border:3px solid #6366f1;}
.profile h4{font-size:15px;}
.profile p{font-size:12px;color:#aaa;}
.menu{margin-top:10px;text-align:left;flex:1;position:relative;z-index:99;}
.menu a{display:flex;align-items:center;gap:10px;padding:10px;margin-bottom:8px;border-radius:8px;text-decoration:none;color:#bbb;transition:0.3s;}
.menu a:hover, .menu a.active{background:#1e293b;color:#fff;}
.logout{display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:#ef4444;border-radius:6px;font-size:13px;color:#fff;text-decoration:none;position:absolute;bottom:20px;left:20px;right:20px;}

/* MODAL */
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);justify-content:center;align-items:center;z-index:999;}
.modal-box{background:#fff;padding:25px;border-radius:12px;text-align:center;width:350px;max-width:90%;z-index:1000;}
.preview{width:90px;height:90px;border-radius:50%;margin-bottom:10px;}
.btn{padding:8px 12px;border:none;border-radius:6px;cursor:pointer;color:#fff;}
.upload{background:#6366f1;} .delete{background:#ef4444;} .close{background:#64748b;}
.btn-group{display:flex;justify-content:center;gap:10px;margin-top:10px;}

/* MAIN CONTENT */
.main-content{margin-left:260px;padding:20px;width:100%;flex:1;}
h2{margin-bottom:15px;color:#0f172a;}
table{width:80%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);}
table th, table td{padding:12px;text-align:center;border-bottom:1px solid #e2e8f0;}
table th{background:#6366f1;color:#fff;}
table tr:hover{background:#f1f5f9;}

/* FIXED FOOTER */
footer{background:#0f172a;color:#fff;text-align:center;padding:15px;position:fixed;bottom:0;left:270px;width:calc(100% - 270px);box-shadow:0 -2px 6px rgba(0,0,0,0.2);}
</style>
</head>

<body>

<!-- SIDEBAR -->
<?php include "sidebar.php"; ?>

<!-- MAIN CONTENT -->
<div class="main-content">
<h2>Feedback Reports</h2>

<?php if($count>0): ?>
<table>
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Type</th>
<th>Opinion</th>
<th>Rating</th>
</tr>
</thead>
<tbody>
<?php foreach($feedbacks as $fb): ?>
<tr>
<td><?php echo $fb['id']; ?></td>
<td><?php echo htmlspecialchars($fb['name']); ?></td>
<td><?php echo htmlspecialchars($fb['email']); ?></td>
<td><?php echo htmlspecialchars($fb['type']); ?></td>
<td><?php echo htmlspecialchars($fb['opinion']); ?></td>
<td><?php echo htmlspecialchars($fb['rating']); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php else: ?>
<p>No feedback found.</p>
<?php endif; ?>
</div>

<!-- FIXED FOOTER -->
<footer>
    &copy; <?php echo date("Y"); ?> Krushi Saarthi Admin Panel. All Rights Reserved.
</footer>

<!-- PROFILE MODAL -->
<div class="modal" id="modal">
<div class="modal-box">
<img id="preview" src="<?php echo $admin['profile_img']; ?>" class="preview">
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
function openModal(){document.getElementById("modal").style.display="flex";}
function closeModal(){document.getElementById("modal").style.display="none";}
function previewImage(e){
    let reader = new FileReader();
    reader.onload = function(){document.getElementById("preview").src = reader.result;}
    reader.readAsDataURL(e.target.files[0]);
}
</script>

</body>
</html>