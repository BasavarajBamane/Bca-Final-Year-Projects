<?php
ob_start();
session_start();
include "db/db.php";

/* ENABLE ERRORS */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* CHECK DB CONNECTION */
if(!$conn){
    die("Database Connection Failed");
}

/* CHECK ADMIN SESSION */
if(!isset($_SESSION['mail'])){
    header("Location: adminlogin.php");
    exit();
}

/* SUCCESS MESSAGE */
$msg = "";

/* DELETE SELLER */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM sellerregister WHERE seller_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: adminseller.php?msg=deleted");
    exit();
}

/* APPROVE SELLER */
if(isset($_GET['approve'])){
    $id = intval($_GET['approve']);
    $stmt = $conn->prepare("UPDATE sellerregister SET status='Approved' WHERE seller_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: adminseller.php?msg=approved");
    exit();
}

/* BLOCK SELLER */
if(isset($_GET['block'])){
    $id = intval($_GET['block']);
    $stmt = $conn->prepare("UPDATE sellerregister SET status='Blocked' WHERE seller_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: adminseller.php?msg=blocked");
    exit();
}

/* FETCH SELLERS */
$query = "SELECT seller_id, fullname, mobile, gmail, shop_name, status, created_at 
          FROM sellerregister 
          ORDER BY seller_id DESC";

$result = mysqli_query($conn, $query);

if(!$result){
    die("Query Failed: " . mysqli_error($conn));
}

$sellers = mysqli_fetch_all($result, MYSQLI_ASSOC);
$count = count($sellers);

/* MESSAGE HANDLER */
if(isset($_GET['msg'])){
    if($_GET['msg'] == 'approved') $msg = "Approved Successfully!";
    if($_GET['msg'] == 'blocked') $msg = "Blocked Successfully!";
    if($_GET['msg'] == 'deleted') $msg = "Deleted Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Sellers</title>
<link rel="stylesheet" href="style.css">
<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f4f4;
}

.main{
    margin-left:270px;
    padding:20px;
}

/* SMALL TABLE */
table{
    width:1068px;
    margin:auto;
    background:#fff;
    border-collapse:collapse;
    font-size:14px;
}

th, td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

th{
    background:#00ab41;
    color:#fff;
}

/* BUTTONS FIT */
.btn{
    padding:4px 8px;
    font-size:12px;
    color:#fff;
    text-decoration:none;
    border-radius:3px;
    margin:2px;
    display:inline-block;
}

.approve{background:green;}
.block{background:orange;}
.delete{background:red;}

tr:hover{
    background:#f9f9f9;
}

.footer{
    position:fixed;
    bottom:0;
    left:270px;
    width:calc(98.5% - 260px);
    background:#00ab41;
    color:#fff;
    text-align:center;
    padding:10px;
}
</style>
</head>

<body>

<?php include "sidebar.php"; ?>


<div class="main">

<h2 style="text-align:center;">All Sellers</h2>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Mobile</th>
<th>Email</th>
<th>Shop</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php if($count > 0){ ?>

    <?php foreach($sellers as $row){ ?>

    <tr>
        <td><?php echo $row['seller_id']; ?></td>
        <td><?php echo $row['fullname']; ?></td>
        <td><?php echo $row['mobile']; ?></td>
        <td><?php echo $row['gmail']; ?></td>
        <td><?php echo $row['shop_name']; ?></td>
        <td><?php echo !empty($row['status']) ? $row['status'] : 'Pending'; ?></td>
        <td><?php echo $row['created_at']; ?></td>

        <td>
            <?php if($row['status'] != 'Approved'){ ?>
                <a class="btn approve" href="?approve=<?php echo $row['seller_id']; ?>">Approve</a>
            <?php } ?>

            <?php if($row['status'] != 'Blocked'){ ?>
                <a class="btn block" href="?block=<?php echo $row['seller_id']; ?>">Block</a>
            <?php } ?>

            <a class="btn delete" 
               href="?delete=<?php echo $row['seller_id']; ?>" 
               onclick="return confirm('Delete this seller?')">
               Delete
            </a>
        </td>
    </tr>

    <?php } ?>

<?php } else { ?>

<tr>
<td colspan="8">No Sellers Found</td>
</tr>

<?php } ?>

</table>

</div>

<div class="footer">
© <?php echo date("Y"); ?> Admin Panel
</div>

<?php include "footer.php"; ?>

<!-- POPUP MESSAGE -->
<?php if(!empty($msg)){ ?>
<script>
alert("<?php echo $msg; ?>");
</script>
<?php } ?>

</body>
</html>