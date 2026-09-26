<?php
session_start();
include "db/db.php";

/* ================= CHECK USER LOGIN ================= */
if(!isset($_SESSION['email'])){
    header("Location: userlogin.php");
    exit();
}

$email = $_SESSION['email'];

/* ================= FETCH USER DATA ================= */
$user_query = mysqli_query($conn, "SELECT id, fullname FROM usersregister WHERE email='$email'");
$user = mysqli_fetch_assoc($user_query);

if(!$user){
    die("User not found");
}

$user_id = $user['id'];
$customer_name = $user['fullname'];

/* ================= GET PRODUCT ID ================= */
if(!isset($_GET['id'])){
    die("Product not found");
}

$id = intval($_GET['id']);

/* ================= FETCH PRODUCT ================= */
$product_query = mysqli_query($conn,"SELECT * FROM products WHERE id='$id'");
$product = mysqli_fetch_assoc($product_query);

if(!$product){
    die("Invalid product");
}

/* ================= PLACE ORDER ================= */
if(isset($_POST['place_order'])){

    $quantity = (int)$_POST['quantity'];
    $address  = mysqli_real_escape_string($conn,$_POST['address']);

    if($quantity < 1) $quantity = 1;

    $available_qty = (int)$product['quantity'];

    if($quantity > $available_qty){
        echo "<script>alert('❌ Only $available_qty items available in stock');</script>";
    } else {

        $product_id    = $id;
        $product_name  = $product['product_name'];
        $product_image = $product['image'];
        $seller_id     = $product['seller_id'];
        $seller_name   = $product['seller_name'];

        $price   = $product['price'];
        $subsidy = $product['subsidy_percentage'];

        $discount = ($price * $subsidy) / 100;
        $final_price = $price - $discount;
        $total = $final_price * $quantity;

        $status = "Pending";

        mysqli_begin_transaction($conn);

        try{

            mysqli_query($conn,"INSERT INTO orders 
            (user_id, customer_name, product_id, product_name, product_image, seller_id, seller_name, quantity, price, subsidy, total, status, created_at)
            VALUES ('$user_id','$customer_name','$product_id','$product_name','$product_image','$seller_id','$seller_name','$quantity','$final_price','$subsidy','$total','$status',NOW())");

            $new_qty = $available_qty - $quantity;
            mysqli_query($conn,"UPDATE products SET quantity='$new_qty' WHERE id='$product_id'");

            if($new_qty == 0){
                mysqli_query($conn,"UPDATE products SET status='Inactive' WHERE id='$product_id'");
            }

            mysqli_commit($conn);

            echo "<script>
                alert('🎉 Order Placed Successfully!');
                window.location.href='userdashboard.php';
            </script>";
            exit();

        } catch(Exception $e){
            mysqli_rollback($conn);
            echo "Error: ".$e->getMessage();
        }
    }
}

/* ================= DISPLAY DATA ================= */
$name        = htmlspecialchars($product['product_name']);
$category    = htmlspecialchars($product['category']);
$brand       = htmlspecialchars($product['brand']);
$model       = htmlspecialchars($product['model']);
$desc        = htmlspecialchars($product['description']);
$price       = (int)$product['price'];
$subsidy     = (int)$product['subsidy_percentage'];
$status      = htmlspecialchars($product['status']);
$seller_name = htmlspecialchars($product['seller_name']);
$img         = $product['image'];

$discount_amount = ($price * $subsidy) / 100;
$final_price = $price - $discount_amount;
$old_price = $price;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Buy Product</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* YOUR ORIGINAL CSS */
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:linear-gradient(135deg,#e6f4ea,#f4f9ff);}
.header{position:fixed;top:0;width:100%;height:65px;background:rgba(255,255,255,0.85);backdrop-filter:blur(10px);display:flex;align-items:center;padding:0 30px;box-shadow:0 4px 20px rgba(0,0,0,0.1);z-index:1000;}
.logo img{width:150px;height:60px;object-fit:contain;}
.footer{position:fixed;bottom:0;width:100%;height:35px;background:#2c7a2c;color:#fff;display:flex;justify-content:center;align-items:center;font-size:12px;}
.container{margin-top:90px;margin-bottom:50px;display:flex;justify-content:center;}
.product{display:flex;width:1000px;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 20px 40px rgba(0,0,0,0.15);}
.left{width:50%;background:#f8fafc;display:flex;align-items:center;justify-content:center;padding:30px;}
.left img{width:100%;max-height:350px;object-fit:cover;border-radius:12px;}
.right{width:50%;padding:30px;display:flex;flex-direction:column;justify-content:space-between;}
.title{font-size:24px;font-weight:600;}
.price span{font-size:22px;font-weight:600;color:#2c7a2c;}
.old{text-decoration:line-through;color:#999;margin-left:10px;}
.discount{color:red;margin-left:10px;}
.rating{color:#facc15;font-size:14px;margin-bottom:10px;}
.desc{font-size:14px;color:#555;margin-bottom:15px;}
.details{font-size:13px;color:#444;margin-bottom:15px;}
.details p{margin:4px 0;}
.qty-box{display:flex;align-items:center;margin-bottom:15px;}
.qty-box button{width:35px;height:35px;border:none;background:#2c7a2c;color:#fff;font-size:18px;cursor:pointer;}
.qty-box input{width:60px;text-align:center;border:1px solid #ccc;height:35px;}
textarea{width:100%;padding:10px;margin-bottom:12px;border-radius:8px;border:1px solid #ccc;}
.cod{background:#f1fdf4;padding:10px;border-radius:8px;margin-bottom:12px;color:#2c7a2c;}
.buy-btn{background:linear-gradient(135deg,#2c7a2c,#4ade80);color:#fff;padding:14px;border:none;border-radius:10px;font-size:16px;cursor:pointer;}
.back{display:inline-block;margin-top:10px;font-size:14px;color:#555;text-decoration:none;cursor:pointer;}

/* MODAL */
.modal{
display:none;
position:fixed;
z-index:999;
left:0; top:0;
width:100%; height:100%;
background:rgba(0,0,0,0.8);
}
.modal-content{
background:#fff;
color:#000;
margin:5% auto;
padding:20px;
width:60%;
border-radius:10px;
}
.close{
float:right;
font-size:22px;
cursor:pointer;
color:red;
}
</style>
</head>

<body>

<div class="header">
<a href="dashboard.php" class="logo">
<img src="images/h1logo.png">
</a>
</div>

<div class="container">
<div class="product">

<div class="left">
<img src="<?php echo !empty($img) ? $img : 'images/no-image.png'; ?>">
</div>

<div class="right">

<div>
<div class="title"><?php echo $name; ?></div>
<div class="rating">★★★★★ (4.8)</div>

<div class="price">
<span id="totalPrice">₹<?php echo $final_price; ?></span>
<span class="old">₹<?php echo $old_price; ?></span>
<span class="discount"><?php echo $subsidy; ?>% Subsidy</span>
</div>

<p class="desc"><?php echo $desc; ?></p>

<div class="details">
<p><b>Category:</b> <?php echo $category; ?></p>
<p><b>Brand:</b> <?php echo $brand; ?></p>
<p><b>Model:</b> <?php echo $model; ?></p>
<p><b>Seller:</b> <?php echo $seller_name; ?></p>
<p><b>Status:</b> <?php echo $status; ?></p>
<p><b>Available Qty:</b> <?php echo $product['quantity']; ?></p>
</div>
</div>

<div>
<form method="POST" id="orderForm">

<div class="qty-box">
<button type="button" onclick="dec()">-</button>
<input type="number" name="quantity" id="qty" value="1" min="1">
<button type="button" onclick="inc()">+</button>
</div>

<textarea name="address" placeholder="Enter Delivery Address" required></textarea>

<div class="cod">💰 Cash on Delivery Available</div>

<button type="button" onclick="openModal()" class="buy-btn">
Place Order
</button>

<input type="hidden" name="place_order" value="1">

</form>

<a class="back" onclick="window.history.back()">⬅ Back</a>

</div>

</div>
</div>
</div>

<div class="footer">
© 2026 Krushi Saarthi
</div>

<!-- TERMS MODAL -->
<div id="termsModal" class="modal">
<div class="modal-content">

<span class="close" onclick="closeModal()">&times;</span>

<h2>Terms & Conditions</h2>

<p>Orders cannot be cancelled after dispatch.</p>
<p>Out for Delivery orders cannot be cancelled.</p>
<p>Refusal may lead to account restriction.</p>

<br>

<input type="checkbox" id="agree"> I Agree

<br><br>

<button onclick="confirmOrder()">Confirm & Place Order</button>

</div>
</div>

<script>
function openModal(){
document.getElementById("termsModal").style.display="block";
}
function closeModal(){
document.getElementById("termsModal").style.display="none";
}
function confirmOrder(){
if(!document.getElementById("agree").checked){
alert("Please accept Terms & Conditions");
return;
}
document.getElementById("orderForm").submit();
}

let basePrice = <?php echo $final_price; ?>;
function updatePrice(){
let qty = document.getElementById("qty").value;
document.getElementById("totalPrice").innerText = "₹" + (basePrice * qty);
}
function inc(){let q=document.getElementById("qty");q.value++;updatePrice();}
function dec(){let q=document.getElementById("qty");if(q.value>1){q.value--;updatePrice();}}
</script>

</body>
</html>