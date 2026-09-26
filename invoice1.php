<?php
include "db/db.php";

if(!isset($_GET['id'])){
    die("Invalid Request");
}

$bill_no = $_GET['id'];

// FETCH BILL
$query = mysqli_query($conn, "SELECT * FROM bills WHERE bill_no='$bill_no'");
if(mysqli_num_rows($query) == 0){
    die("No Bill Found");
}

$b = mysqli_fetch_assoc($query);

// FETCH SELLER
$seller_id = $b['seller_id'];
$seller_q = mysqli_query($conn, "SELECT * FROM sellerregister WHERE seller_id='$seller_id'");
$seller = mysqli_fetch_assoc($seller_q);

// FETCH USER
$user_id = $b['user_id'];
$user_q = mysqli_query($conn, "SELECT * FROM usersregister WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_q);

// SAFE USER DATA
$customer_name   = $user['fullname'] ?? "N/A";
$customer_mobile = $user['mobile'] ?? "N/A";
$customer_email  = $user['email'] ?? "N/A";

// IMAGE FIX
$imageName = trim($b['product_image']);

if(empty($imageName)){
    $imagePath = "images/no-image.png";
}
else{
    if(strpos($imageName, 'uploads/') !== false || strpos($imageName, 'images/') !== false){
        $imagePath = $imageName;
    }
    else{
        if(file_exists("uploads/" . $imageName)){
            $imagePath = "uploads/" . $imageName;
        }
        elseif(file_exists("images/" . $imageName)){
            $imagePath = "images/" . $imageName;
        }
        else{
            $imagePath = "images/no-image.png";
        }
    }
}

// TOTAL CALCULATION
$price = (float)$b['price'];
$qty   = (int)$b['quantity'];
$total = $price * $qty;

// AMOUNT IN WORDS
function amountToWords($num){
    if(!class_exists('NumberFormatter')) return "";
    $fmt = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    return strtoupper($fmt->format($num)) . " RUPEES ONLY";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Invoice</title>

<style>
body{font-family:Arial;background:#f5f5f5;}

.invoice{
width:700px;
margin:20px auto;
border:1px solid #000;
padding:20px;
background:#fff;
}

.top{
display:flex;
justify-content:space-between;
border-bottom:2px solid #000;
}

.logo img{height:50px;}

.title{
text-align:center;
font-size:22px;
font-weight:bold;
margin:10px;
}

.info{
display:flex;
justify-content:space-between;
margin-top:10px;
}

.box{
width:48%;
border:1px solid #000;
padding:10px;
}

.bill-table{
width:100%;
border-collapse:collapse;
margin-top:15px;
}

.bill-table th,
.bill-table td{
border:1px solid #000;
padding:8px;
text-align:center;
}

.amount-words{
margin-top:15px;
font-weight:bold;
font-size:14px;
}

.signature{
text-align:right;
margin-top:50px;
font-family: 'Brush Script MT', cursive;
font-size:32px; /* ✅ BIG SIGNATURE */
color:#000;
}

.sign-line{
margin-top:10px;
border-top:1px solid #000;
width:250px;
float:right;
text-align:center;
font-family:Arial;
font-size:13px;
}

.print-btn{
margin:20px auto;
display:block;
padding:10px 20px;
background:#16a34a;
color:#fff;
border:none;
cursor:pointer;
}

.product-img{
height:60px;
width:60px;
object-fit:cover;
border:1px solid #ccc;
}
</style>

<script>
function printInvoice(){
    window.print();
}
</script>

</head>

<body>

<button class="print-btn" onclick="printInvoice()">Print Invoice</button>

<div class="invoice">

<div class="top">
<div class="logo">
<img src="images/h1logo.png" onerror="this.src='images/no-image.png';">
</div>

<div>
<b>Krushi Saarthi Pvt Ltd</b><br>
Tumkur, Karnataka
</div>
</div>

<div class="title">INVOICE</div>

<p>
<b>Invoice No:</b> <?php echo $b['bill_no']; ?> |
<b>Date:</b> <?php echo date('d-m-Y',strtotime($b['created_at'])); ?>
</p>

<div class="info">

<div class="box">
<b>Seller</b><br>
<?php echo $seller['fullname']; ?><br>
<?php echo $seller['shop_name']; ?><br>
📞 <?php echo $seller['mobile']; ?><br>
📧 <?php echo $seller['gmail']; ?>
</div>

<div class="box">
<b>Customer</b><br>
<?php echo $customer_name; ?><br>
📞 <?php echo $customer_mobile; ?><br>
📧 <?php echo $customer_email; ?>
</div>

</div>

<table class="bill-table">

<tr>
<th>Product</th>
<th>Image</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
</tr>

<tr>
<td><?php echo $b['product_name']; ?></td>

<td>
<img src="<?php echo $imagePath; ?>" 
     class="product-img"
     onerror="this.onerror=null;this.src='images/no-image.png';">
</td>

<td><?php echo $qty; ?></td>
<td>₹<?php echo $price; ?></td>
<td>₹<?php echo $total; ?></td>
</tr>

<tr>
<td colspan="4"><b>Grand Total</b></td>
<td><b>₹<?php echo $total; ?></b></td>
</tr>

</table>

<!-- ✅ AMOUNT IN WORDS -->
<div class="amount-words">
Amount in Words: <?php echo amountToWords($total); ?>
</div>

<!-- ✅ BIG DIGITAL SIGN -->
<div class="signature">
<?php echo $seller['fullname']; ?>
</div>

<div class="sign-line">
Authorized Signatory<br>
Date: <?php echo date('d-m-Y'); ?>
</div>

<p style="text-align:center; margin-top:60px;">
*** Computer Generated Invoice ***
</p>

</div>

</body>
</html>