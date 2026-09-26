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
$delivered = "Delivered";

/* ================= UPDATE STATUS ================= */
if (isset($_POST['update_status'])) {

    $order_id = intval($_POST['order_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);

    // ✅ Added Out for Delivery
    $allowed = ['Pending','Confirmed','Shipped','Out for Delivery','Delivered','Cancelled'];

    if(!in_array($new_status, $allowed)){
        $popup_msg = "❌ Invalid status";
    } else {

        $update = mysqli_query($conn, "
            UPDATE orders 
            SET status='$new_status' 
            WHERE order_id='$order_id' AND seller_id='$seller_id'
        ");

        if($update){

            // ✅ Generate bill ONLY when Delivered
            if($new_status == 'Delivered'){

                $order_q = mysqli_query($conn, "
                    SELECT * FROM orders WHERE order_id='$order_id'
                ");
                $order = mysqli_fetch_assoc($order_q);

                $check = mysqli_query($conn, "
                    SELECT bill_id FROM bills WHERE order_id='$order_id'
                ");

                if(mysqli_num_rows($check) == 0){

                    $bill_no = 'BILL'.str_pad($order['order_id'], 5, '0', STR_PAD_LEFT);

                    $total_amount = (float)$order['total'];
                    $gst = round($total_amount * 0.18, 2);
                    $final_amount = $total_amount + $gst;

                    $insert = mysqli_query($conn, "
                        INSERT INTO bills (
                            order_id, user_id, customer_name, product_id, product_name,
                            product_image, seller_id, seller_name, quantity, price,
                            subsidy, total, created_at,
                            bill_no, gst, total_amount, final_amount, bill_status
                        ) VALUES (
                            '".$order['order_id']."',
                            '".$order['user_id']."',
                            '".$order['customer_name']."',
                            '".$order['product_id']."',
                            '".$order['product_name']."',
                            '".$order['product_image']."',
                            '".$order['seller_id']."',
                            '".$order['seller_name']."',
                            '".$order['quantity']."',
                            '".$order['price']."',
                            '".$order['subsidy']."',
                            '".$order['total']."',
                            NOW(),
                            '".$bill_no."',
                            '".$gst."',
                            '".$total_amount."',
                            '".$final_amount."',
                            '".$delivered."'
                        )
                    ");

                    if(!$insert){
                        die("Insert Error: " . mysqli_error($conn));
                    }
                }
            }

            $popup_msg = "✅ Status Updated Successfully";

        } else {
            $popup_msg = "❌ Update Failed: ".mysqli_error($conn);
        }
    }
}

/* ================= DELETE ORDER ================= */
if (isset($_GET['delete'])) {

    $order_id = intval($_GET['delete']);

    $delete = mysqli_query($conn, "
        DELETE FROM orders 
        WHERE order_id='$order_id' AND seller_id='$seller_id'
    ");

    if($delete){
        $popup_msg = "❌ Order Deleted";
    } else {
        $popup_msg = "❌ Delete Failed: ".mysqli_error($conn);
    }
}

/* ================= FETCH CURRENT ORDERS ================= */
$result = mysqli_query($conn, "
    SELECT * FROM orders 
    WHERE seller_id='$seller_id' 
    AND status IN ('Pending','Confirmed','Shipped','Out for Delivery')
    ORDER BY order_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Current Orders</title>

<style>
body{font-family:Arial;background:#f5f7f6;}
.main-content{margin-left:260px;padding:20px;}
h2{margin-bottom:15px;}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;}
th{background:#1f2937;color:#22c55e;padding:12px;text-align:left;}
td{padding:10px;border-bottom:1px solid #eee;}
tr:hover{background:#f9fafb;}

.status{font-weight:bold;}
.pending{color:#b45309;}
.confirmed{color:#1e40af;}
.shipped{color:#f59e0b;}
.out-for-delivery{color:#9333ea;} /* ✅ FIXED */
.delivered{color:#166534;}
.cancelled{color:#991b1b;}

.action-btn{padding:5px 10px;border-radius:5px;text-decoration:none;font-size:12px;color:white;}
.delete-btn{background:#ef4444;}
.delete-btn:hover{background:#dc2626;}
.update-btn{background:#22c55e;border:none;color:white;padding:5px 8px;cursor:pointer;}
select{padding:5px;}

.popup{
    position:fixed;
    top:20px;
    right:20px;
    padding:12px 20px;
    background:#22c55e;
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

<h2>⏳ Current Orders</h2>

<?php if(!empty($popup_msg)): ?>
<div class="popup"><?php echo $popup_msg; ?></div>
<meta http-equiv="refresh" content="3;url=current_orders.php">
<?php endif; ?>

<table>
<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Product</th>
<th>Qty</th>
<th>Status</th>
<th>Date</th>
<th>Update</th>
<th>Actions</th>
</tr>

<?php
if(mysqli_num_rows($result)>0){
while($row=mysqli_fetch_assoc($result)){

// ✅ FIX: Convert status to CSS class safely
$status_class = strtolower(str_replace(' ', '-', $row['status']));
?>

<tr>

<td>#<?php echo $row['order_id']; ?></td>
<td><?php echo htmlspecialchars($row['customer_name']); ?></td>
<td><?php echo htmlspecialchars($row['product_name']); ?></td>
<td><?php echo $row['quantity']; ?></td>

<td class="status <?php echo $status_class; ?>">
<?php echo $row['status']; ?>
</td>

<td><?php echo date('d M Y',strtotime($row['created_at'])); ?></td>

<td>
<form method="POST">
<input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">

<select name="new_status">
<option value="Pending" <?php if($row['status']=="Pending") echo "selected"; ?>>Pending</option>
<option value="Confirmed" <?php if($row['status']=="Confirmed") echo "selected"; ?>>Processing</option>
<option value="Shipped" <?php if($row['status']=="Shipped") echo "selected"; ?>>Shipped</option>
<option value="Out for Delivery" <?php if($row['status']=="Out for Delivery") echo "selected"; ?>>Out for Delivery</option>
<option value="Delivered" <?php if($row['status']=="Delivered") echo "selected"; ?>>Delivered</option>
<option value="Cancelled" <?php if($row['status']=="Cancelled") echo "selected"; ?>>Cancelled</option>
</select>

<button name="update_status" class="update-btn">Update</button>
</form>
</td>

<td>
<a class="action-btn delete-btn"
href="?delete=<?php echo $row['order_id']; ?>"
onclick="return confirm('Delete this order?');">
Delete
</a>
</td>

</tr>

<?php
}
}else{
echo "<tr><td colspan='8'>No Orders Found</td></tr>";
}
?>

</table>

</div>

</body>
</html>