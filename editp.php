<?php
session_start();
include "db/db.php";

/* CHECK LOGIN */
if(!isset($_SESSION['seller_id'])){
    header("Location: sellerlogin.php");
    exit();
}

$seller_id = $_SESSION['seller_id'];

/* GET PRODUCT ID */
if(!isset($_GET['id'])){
    header("Location: seller_viewproducts.php");
    exit();
}

$id = intval($_GET['id']);

/* FETCH PRODUCT */
$query = "SELECT * FROM products WHERE id='$id' AND seller_id='$seller_id'";
$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result) == 0){
    echo "Product not found";
    exit();
}

$row = mysqli_fetch_assoc($result);

/* UPDATE PRODUCT */
if(isset($_POST['update'])){

    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $category = mysqli_real_escape_string($conn,$_POST['category']);
    $price = $_POST['price'];
    $qty = $_POST['quantity'];
    $status = $_POST['status'];
    $hp = $_POST['hp'];

    /* ❌ IMAGE UPDATE REMOVED */

    $update = "UPDATE products SET 
                product_name='$name',
                category='$category',
                price='$price',
                quantity='$qty',
                hp='$hp',
                status='$status'
               WHERE id='$id' AND seller_id='$seller_id'";

    if(mysqli_query($conn,$update)){
        echo "<script>alert('Product Updated Successfully'); window.location='seller_viewproducts.php';</script>";
        exit();
    }else{
        echo "Update Failed";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f5f7f6;
}

/* MAIN */
.main-content{
    margin-left:265px;
    padding:30px;
    padding-bottom:80px;
}

/* TITLE */
h2{
    color:#16a34a;
    margin-bottom:20px;
}

/* FORM */
.form-box{
    background:#fff;
    padding:50px;
    border-radius:12px;
    width:850px;
    margin:auto;
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
}

/* GRID */
.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:40px;
}

.full{
    grid-column:1 / -1;
}

/* INPUTS */
input, select{
    width:100%;
    padding:12px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:14px;
}

/* BUTTON */
button{
    background:#22c55e;
    color:white;
    padding:12px;
    border:none;
    width:100%;
    border-radius:6px;
    font-size:15px;
    cursor:pointer;
}

button:hover{
    background:#16a34a;
}

/* IMAGE */
.preview{
    margin-top:10px;
}

.preview img{
    width:120px;
    height:120px;
    object-fit:cover;
    border-radius:8px;
    border:1px solid #ddd;
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
}

/* RESPONSIVE */
@media(max-width:900px){
    .form-box{
        width:100%;
    }
    .form-grid{
        grid-template-columns:1fr;
    }
}
</style>

</head>

<body>

<?php include "sellersidebar.php"; ?>

<div class="main-content">

<h2>✏️ Edit Product</h2>

<div class="form-box">

<form method="POST">

<div class="form-grid">

<div>
<label>Product Name</label>
<input type="text" name="name" value="<?php echo htmlspecialchars($row['product_name']); ?>" required>
</div>

<div>
<label>Category</label>
<input type="text" name="category" value="<?php echo htmlspecialchars($row['category']); ?>" required>
</div>

<div>
<label>Price</label>
<input type="number" name="price" value="<?php echo $row['price']; ?>" required>
</div>

<div>
<label>Quantity</label>
<input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" required>
</div>

<div>
<label>HP (Horsepower)</label>
<input type="number" name="hp" value="<?php echo $row['hp']; ?>">
</div>

<div class="full">
<label>Status</label>
<select name="status">
    <option value="Active" <?php if($row['status']=="Active") echo "selected"; ?>>Active</option>
    <option value="Inactive" <?php if($row['status']=="Inactive") echo "selected"; ?>>Inactive</option>
</select>
</div>

<!-- ✅ ONLY IMAGE PREVIEW (NO UPLOAD) -->


<div class="full">
<button type="submit" name="update">Update Product</button>
</div>

</div>

</form>

</div>

</div>

<div class="footer">
© 2026 Krushi Saarthi | All Rights Reserved 🌾
</div>

</body>
</html>