<?php
session_start();
include "db/db.php";

if(!isset($_SESSION['mail'])){
    header("Location: adminlogin.php");
    exit();
}

// Handle Add Equipment
if(isset($_POST['add_equipment'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $hourly = mysqli_real_escape_string($conn, $_POST['hourly']);
    $daily = mysqli_real_escape_string($conn, $_POST['daily']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $img_path = "";
    if(isset($_FILES['img']) && $_FILES['img']['error']==0){
        $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];
        if(in_array($ext,$allowed)){
            if(!is_dir("uploads")) mkdir("uploads",0777,true);
            $img_path = "uploads/".time()."_".basename($_FILES['img']['name']);
            move_uploaded_file($_FILES['img']['tmp_name'],$img_path);
        }
    }

    mysqli_query($conn, "INSERT INTO equipments (name, model, hourly, daily, status, img) VALUES ('$name','$model','$hourly','$daily','$status','$img_path')");
    header("Location: admin_addrents.php");
    exit();
}

// Handle Edit Equipment
if(isset($_POST['edit_equipment'])){
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $model = mysqli_real_escape_string($conn,$_POST['model']);
    $hourly = mysqli_real_escape_string($conn,$_POST['hourly']);
    $daily = mysqli_real_escape_string($conn,$_POST['daily']);
    $status = mysqli_real_escape_string($conn,$_POST['status']);
    $img_path = $_POST['current_img'];

    if(isset($_FILES['img']) && $_FILES['img']['error']==0){
        $ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];
        if(in_array($ext,$allowed)){
            if(!is_dir("uploads")) mkdir("uploads",0777,true);
            $img_path = "uploads/".time()."_".basename($_FILES['img']['name']);
            move_uploaded_file($_FILES['img']['tmp_name'],$img_path);
        }
    }

    mysqli_query($conn,"UPDATE equipments SET name='$name', model='$model', hourly='$hourly', daily='$daily', status='$status', img='$img_path' WHERE id=$id");
    header("Location: admin_addrents.php");
    exit();
}

// Handle Delete
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $res = mysqli_query($conn,"SELECT img FROM equipments WHERE id=$id");
    $row = mysqli_fetch_assoc($res);
    if(!empty($row['img']) && file_exists($row['img'])) unlink($row['img']);
    mysqli_query($conn,"DELETE FROM equipments WHERE id=$id");
    header("Location: admin_addrents.php");
    exit();
}

// Fetch equipments
$equipments = mysqli_query($conn,"SELECT * FROM equipments ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Panel - Equipments</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{display:flex;min-height:100vh;background:#f4f6f9;}
.sidebar{width:260px;background:#0f172a;color:#fff;padding:20px;position:fixed;height:100vh;}
.sidebar .logo img{width:160px;margin-bottom:20px;}
.sidebar .profile{display:flex;flex-direction:column;align-items:center;margin-bottom:20px;}
.sidebar .profile img{width:85px;height:85px;border-radius:50%;margin-bottom:10px;border:3px solid #6366f1;}
.sidebar .profile h4{font-size:15px;}
.sidebar .profile p{font-size:12px;color:#aaa;}
.sidebar .menu{margin-top:10px;}
.sidebar .menu a{display:flex;align-items:center;gap:10px;padding:10px;margin-bottom:8px;border-radius:8px;text-decoration:none;color:#bbb;transition:0.3s;}
.sidebar .menu a:hover{background:#1e293b;color:#fff;}
.sidebar .logout{display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;background:#ef4444;border-radius:6px;font-size:13px;text-decoration:none;position:absolute;bottom:20px;left:20px;right:20px;}
.main-content{margin-left:260px;flex:1;padding:20px;}
.grid-container{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-top:20px;}
.grid-item{background:#fff;border-radius:12px;padding:40px 20px;text-align:center;box-shadow:0 4px 15px rgba(0,0,0,0.08);cursor:pointer;transition:0.3s;}
.grid-item:hover{transform:translateY(-5px);box-shadow:0 8px 20px rgba(0,0,0,0.15);}
.grid-item i{font-size:50px;color:#2ecc71;margin-bottom:15px;}
.grid-item h3{font-size:18px;color:#333;font-weight:600;}
table{width:100%;border-collapse:collapse;margin-top:20px;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,0.08);font-size:14px;}
th,td{padding:8px 12px;text-align:left;}
th{background:#2ecc71;color:#fff;font-size:13px;}
tr:nth-child(even){background:#f9f9f9;}
tr:hover{background:#eafaf1;}
img{width:60px;border-radius:4px;}
a.delete,a.edit{color:#fff;text-decoration:none;font-weight:bold;padding:5px 10px;border-radius:6px;font-size:12px;}
a.delete{background:#e74c3c;}
a.edit{background:#6366f1;margin-right:5px;}
button.action-btn{background:#6366f1;color:#fff;border:none;padding:5px 10px;border-radius:6px;cursor:pointer;margin-right:5px;}
button.action-btn:hover{background:#2ecc71;}

/* MODAL */
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);justify-content:center;align-items:center;z-index:999;}
.modal-box{background:#fff;padding:25px;border-radius:12px;width:400px;max-width:90%;}
.modal-box h2{margin-bottom:15px;}
.modal-box form input, .modal-box form select{width:100%;margin-bottom:10px;padding:8px;border-radius:6px;border:1px solid #ccc;}
.modal-box form button{background:#2ecc71;color:#fff;border:none;padding:10px;border-radius:6px;cursor:pointer;width:100%;margin-top:5px;}
.modal-box form button:hover{background:#27ae60;}
.modal-close{background:#ef4444;margin-top:10px;}
#editPreview{width:80px;height:80px;margin-bottom:10px;border-radius:6px;object-fit:cover;border:1px solid #ccc;}

footer{background:#2ecc71;color:#fff;text-align:center;padding:12px;position:fixed;left:260px;bottom:0;width:calc(100% - 260px);font-weight:bold;}
@media(max-width:768px){body{flex-direction:column;}.sidebar{width:100%;height:auto;position:relative;}.main-content{margin-left:0;}footer{left:0;width:100%;}}
</style>
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main-content">
<h2>Admin Panel</h2>
<div class="grid-container">
    <div class="grid-item" onclick="openAddModal()"><i class="fas fa-plus-circle"></i><h3>Add Equipment</h3></div>
    <div class="grid-item"><i class="fas fa-box-open"></i><h3>View/Edit Equipments</h3></div>
</div>

<!-- VIEW TABLE -->
<div id="viewTable" style="margin-top:20px;">
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Model</th><th>Hourly</th><th>Daily</th><th>Status</th><th>Image</th><th>Actions</th>
        </tr>
        <?php while($row=mysqli_fetch_assoc($equipments)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['model'] ?></td>
            <td><?= $row['hourly'] ?></td>
            <td><?= $row['daily'] ?></td>
            <td><?= $row['status'] ?></td>
            <td><?php if(!empty($row['img']) && file_exists($row['img'])): ?><img src="<?= $row['img'] ?>"><?php else: ?>N/A<?php endif; ?></td>
            <td>
                <button class="action-btn" onclick="openEditModal(<?= $row['id'] ?>,'<?= addslashes($row['name']) ?>','<?= addslashes($row['model']) ?>','<?= $row['hourly'] ?>','<?= $row['daily'] ?>','<?= $row['status'] ?>','<?= $row['img'] ?>')">Edit</button>
                <a class="delete" href="admin_addrents.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- ADD MODAL -->
<div class="modal" id="addModal">
    <div class="modal-box">
        <h2>Add Equipment</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="model" placeholder="Model" required>
            <input type="text" name="hourly" placeholder="Hourly Rent" required>
            <input type="text" name="daily" placeholder="Daily Rent" required>
            <select name="status">
                <option value="Available">Available</option>
                <option value="Rented">Rented</option>
                <option value="Unavailable">Unavailable</option>
            </select>
            <input type="file" name="img" accept="image/*" required>
            <button type="submit" name="add_equipment">Add</button>
        </form>
        <button onclick="closeAddModal()" class="modal-close">Close</button>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal" id="editModal">
    <div class="modal-box">
        <h2>Edit Equipment</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="editId">
            <input type="hidden" name="current_img" id="currentImg">
            <img id="editPreview" src="" alt="Preview">
            <input type="text" name="name" id="editName" placeholder="Name" required>
            <input type="text" name="model" id="editModel" placeholder="Model" required>
            <input type="text" name="hourly" id="editHourly" placeholder="Hourly Rent" required>
            <input type="text" name="daily" id="editDaily" placeholder="Daily Rent" required>
            <select name="status" id="editStatus">
                <option value="Available">Available</option>
                <option value="Rented">Rented</option>
                <option value="Unavailable">Unavailable</option>
            </select>
            <input type="file" name="img" accept="image/*" onchange="previewEditImage(event)">
            <button type="submit" name="edit_equipment">Save Changes</button>
        </form>
        <button onclick="closeEditModal()" class="modal-close">Close</button>
    </div>
</div>

</div>
<footer>Krushi Saarthi Admin Panel &copy; <?= date('Y') ?></footer>

<script>
function openAddModal(){document.getElementById('addModal').style.display='flex';}
function closeAddModal(){document.getElementById('addModal').style.display='none';}

function openEditModal(id,name,model,hourly,daily,status,img){
    document.getElementById('editId').value=id;
    document.getElementById('editName').value=name;
    document.getElementById('editModel').value=model;
    document.getElementById('editHourly').value=hourly;
    document.getElementById('editDaily').value=daily;
    document.getElementById('editStatus').value=status;
    document.getElementById('currentImg').value=img;
    if(img){document.getElementById('editPreview').src=img;} else {document.getElementById('editPreview').src='';}
    document.getElementById('editModal').style.display='flex';
}
function closeEditModal(){document.getElementById('editModal').style.display='none';}
function previewEditImage(e){
    let reader = new FileReader();
    reader.onload = function(){document.getElementById('editPreview').src=reader.result;}
    reader.readAsDataURL(e.target.files[0]);
}
</script>

</body>
</html>