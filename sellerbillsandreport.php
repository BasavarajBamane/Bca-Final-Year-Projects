<?php
session_start();
include "db/db.php";

// LOGIN CHECK
if(!isset($_SESSION['seller_id'])){
    header("Location: sellerlogin.php");
    exit();
}

$seller_id = $_SESSION['seller_id'];

// FETCH BILLS
$bills = mysqli_query($conn, "SELECT * FROM bills WHERE seller_id='$seller_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Seller Bills</title>

<style>
body{margin:0;font-family:Arial;background:#f5f7f6;}
.main-content{margin-left:260px;padding:30px;}
h2{color:#16a34a;}

table{width:100%;border-collapse:collapse;background:#fff;}
th{background:#1f2937;color:#22c55e;padding:10px;}
td{padding:8px;border-bottom:1px solid #eee;text-align:center;}

img.product{
height:60px;
width:60px;
object-fit:cover;
border-radius:5px;
border:1px solid #ccc;
}

.btn{
padding:6px 12px;
border:none;
border-radius:5px;
color:#fff;
cursor:pointer;
}

.view{background:#3b82f6;}
</style>

</head>

<body>

<?php include "sellersidebar.php"; ?>

<div class="main-content">

<h2>🧾 Seller Bills (No GST)</h2>

<table>
<tr>
<th>S.No</th>
<th>Bill No</th>
<th>Product Image</th>
<th>Customer</th>
<th>Product</th>
<th>Total Amount</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php 
$i=1;
while($b = mysqli_fetch_assoc($bills)): 

$image = trim($b['product_image']);

// IMAGE PATH FIX
if(empty($image)){
    $imgPath = "images/no-image.png";
}else{
    if(strpos($image, 'images/') !== false){
        $imgPath = $image;
    }else{
        $imgPath = "images/" . $image;
    }
}
?>

<tr>
<td><?php echo $i++; ?></td>
<td><?php echo $b['bill_no']; ?></td>

<td>
<img src="<?php echo $imgPath; ?>" 
     class="product"
     onerror="this.onerror=null;this.src='images/no-image.png';">
</td>

<td><?php echo $b['customer_name']; ?></td>
<td><?php echo $b['product_name']; ?></td>

<!-- ✅ ONLY FINAL AMOUNT (NO GST) -->
<td><b>₹<?php echo $b['total_amount']; ?></b></td>

<td><?php echo date('d M Y',strtotime($b['created_at'])); ?></td>

<td>
<a href="invoice1.php?id=<?php echo $b['bill_no']; ?>" target="_blank">
<button class="btn view">View</button>
</a>
</td>
</tr>

<?php endwhile; ?>

</table>

</div>

</body>
</html>