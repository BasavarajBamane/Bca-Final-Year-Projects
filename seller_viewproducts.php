<?php
session_start();
include "db/db.php";

/* CHECK LOGIN */
if(!isset($_SESSION['seller_id'])){
    header("Location: sellerlogin.php");
    exit();
}

$seller_id = $_SESSION['seller_id'];

/* DELETE PRODUCT */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    mysqli_query($conn,"DELETE FROM products WHERE id='$id' AND seller_id='$seller_id'");
    
    echo "<script>alert('Product Deleted Successfully'); window.location='seller_viewproducts.php';</script>";
    exit();
}

/* FETCH PRODUCTS */
$query = "SELECT * FROM products WHERE seller_id='$seller_id' ORDER BY id DESC";
$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Products</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f5f7f6;
}

/* MAIN */
.main-content{
    margin-left:600px;
    padding:25px;
    padding-bottom:70px;
}

h2{
    margin-bottom:20px;
    color:#16a34a;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

th{
    background:#1f2937;
    color:white;
    padding:12px;
    font-size:14px;
}

td{
    padding:10px;
    font-size:13px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f9fafb;
}

/* IMAGE */
td img{
    width:60px;
    height:60px;
    object-fit:cover;
    border-radius:8px;
    border:1px solid #ddd;
}

/* STATUS */
.status-active{
    color:green;
    font-weight:bold;
}

.status-inactive{
    color:red;
    font-weight:bold;
}

/* BUTTONS */
.btn{
    padding:6px 10px;
    border-radius:5px;
    text-decoration:none;
    font-size:12px;
    color:white;
    display:inline-block;
}

.edit{
    background:#3b82f6;
}

.edit:hover{
    background:#2563eb;
}

.delete{
    background:#ef4444;
}

.delete:hover{
    background:#dc2626;
}

/* EMPTY */
.no-data{
    text-align:center;
    padding:20px;
    color:#888;
}

/* FOOTER */
.footer{
    position:fixed;
    bottom:0;
    left:265px;
    width:calc(100% - 265px);
    background:radial-gradient(circle,#22c55e55,transparent);
    padding:10px;
    text-align:center;
    font-size:12px;
    color:black;
    border-top:1px solid rgba(0,0,0,0.1);
}
</style>

</head>

<body>

<?php include "sellersidebar.php"; ?>

<div class="main-content">

<h2>📦 My Products</h2>

<table>

<tr>
<th>S.No</th>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Category</th>
<th>HP</th>
<th>Price</th>
<th>Qty</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php 
if(mysqli_num_rows($result)>0){ 
$sn = 1;
while($row = mysqli_fetch_assoc($result)){ 

$statusClass = strtolower($row['status']) == 'active' ? 'status-active' : 'status-inactive';
?>

<tr>

<td><?php echo $sn++; ?></td>

<td><?php echo $row['id']; ?></td>

<td>
<?php 
if(!empty($row['image']) && file_exists("C:/xampp/htdocs/Krushi Saarthi/".$row['image'])){
    echo "<img src='".$row['image']."' alt='product'>";
} else {
    echo "No Image";
}
?>
</td>

<td><?php echo htmlspecialchars($row['product_name']); ?></td>
<td><?php echo htmlspecialchars($row['category']); ?></td>

<td><?php echo $row['hp']; ?></td>

<td>₹<?php echo $row['price']; ?></td>
<td><?php echo $row['quantity']; ?></td>

<td class="<?php echo $statusClass; ?>">
<?php echo $row['status']; ?>
</td>

<td>
<a class="btn edit" href="editp.php?id=<?php echo $row['id']; ?>">Edit</a>

<a class="btn delete" 
href="?delete=<?php echo $row['id']; ?>" 
onclick="return confirm('Are you sure to delete this product?')">
Delete
</a>
</td>

</tr>

<?php 
} 
}else{
echo "<tr><td colspan='10' class='no-data'>No Products Found</td></tr>";
} 
?>

</table>

</div>

<div class="footer">
© 2026 Krushi Saarthi | All Rights Reserved 🌾
</div>

</body>
</html>