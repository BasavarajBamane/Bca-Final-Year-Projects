<?php
session_start();
include "db/db.php";

/* ✅ OPTIONAL: CHECK LOGIN (REMOVE IF NOT NEEDED) */
if(!isset($_SESSION['seller_id'])){
    header("Location: sellerlogin.php");
    exit();
}

/* ✅ DELETE ORDER */
if (isset($_GET['delete'])) {
    $order_id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM orders WHERE order_id='$order_id'");
    
    header("Location: seller_completed_orders.php?deleted=1");
    exit();
}

/* ✅ FETCH ALL COMPLETED (DELIVERED) ORDERS */
$query = "SELECT * FROM orders 
          WHERE status='Delivered'
          ORDER BY order_id DESC";

$result = mysqli_query($conn, $query);

/* ✅ COUNT */
$count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Completed Orders</title>

<style>
body{font-family:Arial;background:#f5f7f6;}
.main-content{margin-left:260px;padding:20px;}

h2{margin-bottom:15px;}

table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
}

th{
    background:#1f2937;
    color:#22c55e;
    padding:12px;
    text-align:left;
}

td{
    padding:10px;
    border-bottom:1px solid #eee;
}

tr:hover{background:#f9fafb;}

.completed{
    color:#166534;
    font-weight:bold;
}

.action-btn{
    padding:6px 12px;
    border-radius:5px;
    text-decoration:none;
    font-size:12px;
    color:white;
}

.delete-btn{background:#ef4444;}
.delete-btn:hover{background:#dc2626;}
</style>
</head>

<body>

<?php include "sellersidebar.php"; ?>

<div class="main-content">

<h2>✅ All Completed Orders</h2>

<?php if(isset($_GET['deleted'])): ?>
<p style="color:red;">❌ Order deleted successfully</p>
<?php endif; ?>

<p>Total Completed Orders: <?php echo $count; ?></p>

<table>

<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Product</th>
<th>Qty</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php
if($count > 0){
while($row = mysqli_fetch_assoc($result)){
?>

<tr>

<td>#<?php echo $row['order_id']; ?></td>
<td><?php echo htmlspecialchars($row['customer_name']); ?></td>
<td><?php echo htmlspecialchars($row['product_name']); ?></td>
<td><?php echo $row['quantity']; ?></td>

<td class="completed">
<?php echo $row['status']; ?>
</td>

<td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>

<td>
<a class="action-btn delete-btn"
href="seller_completed_orders.php?delete=<?php echo $row['order_id']; ?>"
onclick="return confirm('Are you sure you want to delete this order?');">
Delete
</a>
</td>

</tr>

<?php
}
}else{
echo "<tr><td colspan='7'>No Completed Orders</td></tr>";
}
?>

</table>

</div>

</body>
</html>