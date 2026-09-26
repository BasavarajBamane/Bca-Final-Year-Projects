<?php
session_start();
include "db/db.php";

if(!isset($_SESSION['seller_id'])){
    header("Location: sellerlogin.php");
    exit();
}

$seller_id = $_SESSION['seller_id'];

/* ✅ FETCH SELLER FULLNAME */
$seller_query = mysqli_query($conn, "SELECT fullname FROM sellerregister WHERE seller_id='$seller_id'");
$seller_data = mysqli_fetch_assoc($seller_query);
$seller_name_default = $seller_data['fullname'] ?? '';

if(isset($_POST['submit'])){

/* ✅ ALWAYS USE DB VALUE */
$seller_name = $seller_name_default;

$product_name = mysqli_real_escape_string($conn,$_POST['product_name']);
$category = mysqli_real_escape_string($conn,$_POST['category']);
$brand = mysqli_real_escape_string($conn,$_POST['brand']);
$model = mysqli_real_escape_string($conn,$_POST['model']);
$description = mysqli_real_escape_string($conn,$_POST['description']);
$price = $_POST['price'];
$subsidy = $_POST['subsidy_percentage'];
$quantity = $_POST['quantity'];
$status = $_POST['status'];
$hp = $_POST['hp'];

$image = "";

/* IMAGE UPLOAD */
if(!empty($_FILES['image']['name'])){

    $folder = "C:/xampp/htdocs/Krushi Saarthi/images/";

    if(!is_dir($folder)){
        mkdir($folder,0777,true);
    }

    $file_tmp = $_FILES['image']['tmp_name'];
    $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $file_name = time();

    if(function_exists('imagecreatefromjpeg')){

        $target = $folder.$file_name.".webp";
        $image_info = getimagesize($file_tmp);

        if($image_info){

            $mime = $image_info['mime'];

            if($mime == "image/jpeg"){
                $src = imagecreatefromjpeg($file_tmp);
            }elseif($mime == "image/png"){
                $src = imagecreatefrompng($file_tmp);
            }elseif($mime == "image/gif"){
                $src = imagecreatefromgif($file_tmp);
            }elseif($mime == "image/webp"){
                $src = imagecreatefromwebp($file_tmp);
            }else{
                $src = null;
            }

            if($src){
                imagewebp($src, $target, 80);
                imagedestroy($src);
                $image = "images/".$file_name.".webp";
            }
        }

    } else {
        $target = $folder.$file_name.".".$file_ext;
        move_uploaded_file($file_tmp, $target);
        $image = "images/".$file_name.".".$file_ext;
    }
}

/* INSERT */
$insert = "INSERT INTO products
(product_name, category, brand, model, description, price, subsidy_percentage, quantity, hp, image, status, created_at, seller_id, seller_name)
VALUES
('$product_name','$category','$brand','$model','$description','$price','$subsidy','$quantity','$hp','$image','$status', NOW(), '$seller_id','$seller_name');";

if(mysqli_query($conn,$insert)){
    $success = "Product Added Successfully!";
}else{
    $error = "Error : ".mysqli_error($conn);
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product</title>

<style>
body{
background:#f5f7f6;
font-family:Arial;
margin:0;
}

.main-content{
margin-left:260px;
padding:30px;
padding-bottom:70px;
}

.form-card{
max-width:700px;
margin:auto;
background:#fff;
padding:25px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
text-align:center;
margin-bottom:20px;
color:#22c55e;
}

.form-group{
margin-bottom:15px;
}

label{
display:block;
margin-bottom:5px;
font-weight:bold;
font-size:14px;
}

input,select,textarea{
width:100%;
padding:10px;
border-radius:6px;
border:1px solid #ccc;
outline:none;
}

textarea{
height:80px;
}

button{
width:100%;
padding:12px;
background:#22c55e;
border:none;
border-radius:6px;
color:white;
font-weight:bold;
cursor:pointer;
}

button:hover{
background:#16a34a;
}

.success{color:green;text-align:center;}
.error{color:red;text-align:center;}

.footer{
    position:fixed;
    bottom:0;
    left:270px;
    width:calc(100% - 260px);
    background:radial-gradient(circle,#22c55e55,transparent);
    padding:12px;
    text-align:center;
    font-size:13px;
    color:black;
    box-shadow:0 -5px 20px rgba(0,0,0,0.4);
}
</style>

</head>

<body>

<?php include "sellersidebar.php"; ?>

<div class="main-content">

<div class="form-card">

<h2>Add Product</h2>

<?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST" enctype="multipart/form-data">

<!-- ✅ AUTO FILLED SELLER NAME -->
<div class="form-group">
<label>Seller Name</label>
<input type="text" value="<?php echo $seller_name_default; ?>" readonly>
</div>

<div class="form-group">
<label>Product Name</label>
<input type="text" name="product_name" required>
</div>


<div class="form-group">
<label>Category</label>
<select name="category" required>
<option value="">Select Category</option>
<option>Tractor</option>
<option>Rotavator</option>
<option>Plough</option>
<option>Harvester</option>
<option>Seeder</option>
<option>Sprayer</option>
<option>Irrigation Equipment</option>
<option>Fertilizer & Tools</option>
<option>Livestock Equipment</option>
<option>Storage & Handling</option>
<option>Accessories</option>
</select>
</div>

<div class="form-group">
<label>Brand</label>
<input type="text" name="brand">
</div>

<div class="form-group">
<label>Model</label>
<input type="text" name="model">
</div>

<div class="form-group">
<label>Description</label>
<textarea name="description"></textarea>
</div>

<div class="form-group">
<label>Price</label>
<input type="number" name="price" required>
</div>

<div class="form-group">
<label>Subsidy %</label>
<input type="number" name="subsidy_percentage" value="0">
</div>

<div class="form-group">
<label>Quantity</label>
<input type="number" name="quantity" required>
</div>

<div class="form-group">
<label>HP (Horsepower)</label>
<input type="number" name="hp" placeholder="Enter HP">
</div>

<div class="form-group">
<label>Product Image</label>
<input type="file" name="image" accept="image/*">
</div>

<div class="form-group">
<label>Status</label>
<select name="status">
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>
</div>

<button type="submit" name="submit">Add Product</button>

</form>

</div>

</div>

<div class="footer">
© 2026 Krushi Saarthi | All Rights Reserved 🌾
</div>

</body>
</html>