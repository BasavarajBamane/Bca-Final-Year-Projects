<?php
session_start();
include "db/db.php";

/* CHECK LOGIN */
if (!isset($_SESSION['seller_id'])) {
    header("Location: sellerlogin.php");
    exit();
}

$seller_id = $_SESSION['seller_id'];
$popup_msg = "";

/* ================= DELETE ORDER ================= */
if (isset($_GET['delete'])) {

    $order_id = intval($_GET['delete']);

    $delete = mysqli_query($conn, "
        DELETE FROM orders 
        WHERE order_id='$order_id' AND seller_id='$seller_id'
    ");

    if($delete){
        $popup_msg = "❌ Cancelled Order Deleted";
    } else {
        $popup_msg = "❌ Delete Failed: ".mysqli_error($conn);
    }
}

/* ================= FETCH CANCELLED ORDERS ================= */
$result = mysqli_query($conn, "
    SELECT * FROM orders 
    WHERE seller_id='$seller_id' 
    AND status='Cancelled'
    ORDER BY order_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cancelled Orders</title>

<style>
body{font-family:Arial;background:#f5f7f6;}
.main-content{margin-left:260px;padding:20px;}
h2{margin-bottom:15px;}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;}
th{background:#1f2937;color:#ef4444;padding:12px;text-align:left;}
td{padding:10px;border-bottom:1px solid #eee;}
tr:hover{background:#f9fafb;}

.status{font-weight:bold;color:#991b1b;}

.action-btn{padding:5px 10px;border-radius:5px;text-decoration:none;font-size:12px;color:white;}
.delete-btn{background:#ef4444;}
.delete-btn:hover{background:#dc2626;}

.popup{
    position:fixed;
    top:20px;
    right:20px;
    padding:12px 20px;
    background:#ef4444;
    color:white;
    border-radius:5px;
    z-index:999;
    opacity:0.95;
}
</style>
</head>
<body>

<?php include "sellersidebar.php"; ?>

<div class="main-content">

<h2>❌ Cancelled Orders</h2>

<?php if(!empty($popup_msg)): ?>
<div class="popup"><?php echo $popup_msg; ?></div>
<meta http-equiv="refresh" content="3;url=cancelled_orders.php">
<?php endif; ?>

<table>
<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Product</th>
<th>Qty</th>
<th>Status</th>
<th>Cancel Reason</th> <!-- NEW COLUMN -->
<th>Date</th>
<th>Actions</th>
</tr>

<?php
if(mysqli_num_rows($result)>0){
while($row=mysqli_fetch_assoc($result)){
?>

<tr>

<td>#<?php echo $row['order_id']; ?></td>
<td><?php echo htmlspecialchars($row['customer_name']); ?></td>
<td><?php echo htmlspecialchars($row['product_name']); ?></td>
<td><?php echo $row['quantity']; ?></td>

<td class="status">
<?php echo $row['status']; ?>
</td>

<!-- SHOW CANCEL REASON -->
<td>
<?php 
if(!empty($row['cancel_reason'])){
    echo htmlspecialchars($row['cancel_reason']);
} else {
    echo "—";
}
?>
</td>

<td><?php echo date('d M Y',strtotime($row['created_at'])); ?></td>

<td>
<a class="action-btn delete-btn"
href="?delete=<?php echo $row['order_id']; ?>"
onclick="return confirm('Delete this cancelled order?');">
Delete
</a>
</td>

</tr>

<?php
}
}else{
echo "<tr><td colspan='8'>No Cancelled Orders Found</td></tr>";
}
?>

</table>

</div>

</body>
</html>