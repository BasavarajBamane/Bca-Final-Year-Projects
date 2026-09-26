<?php
session_start();
include "db/db.php";

// ✅ Check admin login
if(!isset($_SESSION['mail'])){
    header("Location: adminlogin.php");
    exit();
}

$mail = $_SESSION['mail'];

// ✅ Secure admin fetch
$stmt = mysqli_prepare($conn, "SELECT * FROM adminlogin WHERE mail=?");
mysqli_stmt_bind_param($stmt, "s", $mail);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

// ✅ Fetch ALL cancelled orders
$stmt2 = mysqli_prepare($conn, "SELECT * FROM orders WHERE status=? ORDER BY created_at DESC");
$status = "Cancelled";
mysqli_stmt_bind_param($stmt2, "s", $status);
mysqli_stmt_execute($stmt2);
$cancelled = mysqli_stmt_get_result($stmt2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Cancelled Orders | Admin</title>
<link rel="stylesheet" href="style.css">

<style>
body {
    margin:0;
    font-family:Arial, sans-serif;
    background:#f1f5f9;
}

.wrapper{
    display:flex;
}

.main-content{
    margin-left:200px;
    padding:20px;
    flex:1;
    padding-bottom:90px;
}

h2 { 
    margin-top:20px; 
    margin-bottom:15px; 
    color:#0f172a; 
}

.table-container {
    width:100%;
    overflow-x:auto;
}

table {
    width:100%;
    border-collapse: collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

th, td {
    padding:12px;
    text-align:center;
}

th {
    background:#ef4444;
    color:#fff;
}

tr:nth-child(even){background:#f9fafb;}
tr:hover { background:#fee2e2; }

img.product-img {
    width:60px;
    height:60px;
    object-fit:cover;
    border-radius:6px;
}

.logout-btn { 
    padding:6px 12px; 
    margin:10px 0; 
    cursor:pointer; 
    background:#ef4444;
    color:#fff;
    border:none;
    border-radius:5px;
}

.footer{
    position: fixed;
    bottom:0;
    left:270px;
    width: calc(98.5% - 260px);
    background:#0f172a;
    color:#fff;
    text-align:center;
    padding:15px;
}

.reason{
    color:#991b1b;
    font-size:13px;
    max-width:200px;
    word-wrap:break-word;
}

@media(max-width:768px){
    table th, table td { font-size:13px; padding:8px; }
    img.product-img { width:50px; height:50px; }
}
</style>
</head>

<body>

<div class="wrapper">

<?php include "sidebar.php"; ?>

<form action="logout.php" method="POST">
    <button class="logout-btn">Logout</button>
</form>

<div class="main-content">

<h2>❌ All Users Cancelled Orders</h2>

<div class="table-container">
<table>
<thead>
<tr>
    <th>Order ID</th>
    <th>User ID</th>
    <th>Customer Name</th>
    <th>Product ID</th>
    <th>Product Name</th>
    <th>Product Image</th>
    <th>Seller ID</th>
    <th>Seller Name</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Subsidy</th>
    <th>Total</th>
    <th>Status</th>
    <th>Cancel Reason</th>
    <th>Created At</th>
</tr>
</thead>

<tbody>
<?php if(mysqli_num_rows($cancelled) > 0){ ?>
    <?php while($r = mysqli_fetch_assoc($cancelled)){ ?>
    <tr>
        <td><?php echo $r['order_id']; ?></td>
        <td><?php echo $r['user_id']; ?></td>
        <td><?php echo htmlspecialchars($r['customer_name']); ?></td>
        <td><?php echo $r['product_id']; ?></td>
        <td><?php echo htmlspecialchars($r['product_name']); ?></td>

        <td>
        <?php if(!empty($r['product_image'])){ ?>
            <img src="<?php echo htmlspecialchars($r['product_image']); ?>" class="product-img">
        <?php } else { echo "N/A"; } ?>
        </td>

        <td><?php echo $r['seller_id']; ?></td>
        <td><?php echo htmlspecialchars($r['seller_name']); ?></td>
        <td><?php echo $r['quantity']; ?></td>
        <td><?php echo $r['price']; ?></td>
        <td><?php echo $r['subsidy']; ?></td>
        <td><?php echo $r['total']; ?></td>

        <td style="color:#dc2626;font-weight:bold;">
            <?php echo $r['status']; ?>
        </td>

        <!-- CANCEL REASON -->
        <td class="reason">
        <?php 
        if(!empty($r['cancel_reason'])){
            echo htmlspecialchars($r['cancel_reason']);
        } else {
            echo "—";
        }
        ?>
        </td>

        <td><?php echo $r['created_at']; ?></td>
    </tr>
    <?php } ?>
<?php } else { ?>
<tr>
    <td colspan="15">No cancelled orders found</td>
</tr>
<?php } ?>
</tbody>

</table>
</div>

</div>

<div class="footer">
© 2026 Krushi Saarthi. All rights reserved.
</div>

</div>

</body>
</html>