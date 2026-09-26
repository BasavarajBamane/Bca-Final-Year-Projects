<?php
include "db/db.php";

$order_id = $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE order_id=?");
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if(!$row){
    die("Order not found");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Invoice</title>

<style>
body{
    font-family:Arial;
    background:#f4f4f4;
    padding:20px;
}

/* INVOICE BOX */
.invoice-box{
    max-width:700px;
    margin:auto;
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:2px solid #eee;
    padding-bottom:10px;
}

.header img{
    height:50px;
}

.title{
    font-size:22px;
    font-weight:bold;
    color:#2c7a2c;
}

/* DETAILS */
.details{
    margin-top:20px;
}

.details p{
    margin:5px 0;
}

/* STATUS */
.status{
    margin-top:20px;
    padding:10px;
    border-radius:8px;
    background:#dff0d8;
    color:#3c763d;
    font-weight:bold;
    text-align:center;
}

/* TABLE */
table{
    width:100%;
    margin-top:20px;
    border-collapse:collapse;
}

table th, table td{
    border:1px solid #ddd;
    padding:10px;
    text-align:left;
}

table th{
    background:#f4f4f4;
}

/* TOTAL */
.total{
    text-align:right;
    font-size:18px;
    margin-top:10px;
}

/* DIGITAL SIGNATURE */
.signature{
    margin-top:40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.signature div{
    text-align:center;
}

.signature-line{
    margin-top:40px;
    border-top:1px solid #000;
    width:200px;
    margin-left:auto;
    margin-right:auto;
}

/* BUTTON */
.print-btn{
    margin-top:20px;
    display:block;
    text-align:center;
}

.print-btn button{
    padding:10px 20px;
    background:#2c7a2c;
    color:#fff;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

/* PRINT STYLE */
@media print{
    .print-btn{
        display:none;
    }
}
</style>
</head>

<body>

<div class="invoice-box">

<div class="header">
    <img src="images/h1logo.png">
    <div class="title">Invoice</div>
</div>

<div class="details">
    <p><b>Order ID:</b> <?php echo $row['order_id']; ?></p>
    <p><b>Customer:</b> <?php echo $row['customer_name']; ?></p>
    <p><b>Date:</b> <?php echo date("d M Y", strtotime($row['created_at'])); ?></p>
</div>

<!-- ORDER STATUS MESSAGE -->
<div class="status">
<?php 
$status_msg = "";
switch($row['status']){
    case "Pending":
        $status_msg = "Your order is pending. Please wait for confirmation.";
        break;
    case "Confirmed":
        $status_msg = "Your order is confirmed and being processed.";
        break;
    case "Shipped":
        $status_msg = "Your order has been shipped.";
        break;
    case "Out for Delivery":
        $status_msg = "Your order is out for delivery.";
        break;
    case "Delivered":
        $status_msg = "Your order is delivered successfully. Visit again!";
        break;
    case "Cancelled":
        $status_msg = "Your order was cancelled.";
        break;
    default:
        $status_msg = "Order status: " . $row['status'];
}
echo $status_msg;
?>
</div>

<table>
<tr>
    <th>Product</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Total</th>
</tr>

<tr>
    <td><?php echo $row['product_name']; ?></td>
    <td>₹<?php echo $row['price']; ?></td>
    <td><?php echo $row['quantity']; ?></td>
    <td>₹<?php echo $row['total']; ?></td>
</tr>
</table>

<div class="total">
    <b>Grand Total: ₹<?php echo $row['total']; ?></b>
</div>

<p style="margin-top:20px;">Thank you for shopping with Krushi Saarthi 🌾</p>

<!-- DIGITAL SIGNATURE -->
<!-- DIGITAL SIGNATURE -->
<div class="signature">
    <div>
        <p style="font-family: 'Brush Script MT', cursive; font-size:24px; margin:0;">Basavaraj</p>
        <p style="margin-top:5px; font-size:12px;">Authorized Signature</p>
    </div>
</div>

<div class="print-btn">
    <button onclick="window.print()">Print / Save PDF</button>
</div>

</div>

</body>
</html>