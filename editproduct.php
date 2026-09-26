<?php
session_start();
include "db/db.php";

/* Check admin session */
if(!isset($_SESSION['mail'])){
    header("Location: adminlogin.php");
    exit();
}

$mail = $_SESSION['mail'];
$sql = "SELECT * FROM adminlogin WHERE mail='$mail'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

/* Get product ID */
if(!isset($_GET['id'])){
    header("Location: addproduct.php");
    exit();
}

$product_id = intval($_GET['id']);

/* Fetch product */
$product_sql = "SELECT * FROM products WHERE id='$product_id'";
$product_result = mysqli_query($conn,$product_sql);

if(mysqli_num_rows($product_result)==0){
    die("Product not found");
}

$product = mysqli_fetch_assoc($product_result);

/* Category Fields */
$fieldsByCategory = [
"Tractor"=>["brand","model","price","quantity","subsidy_percentage","description"],
"Plough"=>["brand","model","price","quantity","subsidy_percentage","description"],
"Harvester"=>["brand","model","price","quantity","subsidy_percentage","description"],
"Seeder"=>["brand","model","price","quantity","subsidy_percentage","description"],
"Sprayer"=>["brand","model","price","quantity","subsidy_percentage","description"],
"Irrigation Equipment"=>["brand","model","price","quantity","subsidy_percentage","description"],
"Fertilizer & Tools"=>["brand","model","price","quantity","subsidy_percentage","description"],
"Livestock Equipment"=>["brand","model","price","quantity","subsidy_percentage","description"],
"Storage & Handling"=>["brand","model","price","quantity","subsidy_percentage","description"],
"Accessories"=>["brand","model","price","quantity","subsidy_percentage","description"]
];

/* Update Product */
if(isset($_POST['submit'])){

$product_name = mysqli_real_escape_string($conn,$_POST['product_name']);
$category = mysqli_real_escape_string($conn,$_POST['category']);

$update_fields=[];

if(isset($fieldsByCategory[$category])){
foreach($fieldsByCategory[$category] as $field){
$value=mysqli_real_escape_string($conn,$_POST[$field]);
$update_fields[]="$field='$value'";
}
}

/* Image upload */
if(!empty($_FILES['image']['tmp_name'])){
$image=addslashes(file_get_contents($_FILES['image']['tmp_name']));
$update_fields[]="image='$image'";
}

$update_sql="UPDATE products SET 
product_name='$product_name',
category='$category',
".implode(",",$update_fields)."
WHERE id='$product_id'";

if(mysqli_query($conn,$update_sql)){
$success="Product Updated Successfully";
$product_result=mysqli_query($conn,$product_sql);
$product=mysqli_fetch_assoc($product_result);
}
else{
$error="Error : ".mysqli_error($conn);
}

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Product</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial;
}

body{
display:flex;
background:#f4f6f9;
height:100vh;
}

/* SIDEBAR */

.sidebar{
width:250px;
background:#2e7d32;
color:white;
display:flex;
flex-direction:column;
padding:20px;
}

.logo{
text-align:center;
margin-bottom:20px;
}

.logo img{
width:150px;
}

.sidebar ul{
list-style:none;
}

summary{
padding:12px;
cursor:pointer;
background:#2e7d32;
border-radius:5px;
}

summary:hover{
background:#388e3c;
}

.submenu{
margin-left:15px;
margin-top:5px;
}

.submenu li{
background:#388e3c;
margin-top:5px;
border-radius:5px;
}

.submenu li a{
display:block;
padding:8px;
color:white;
text-decoration:none;
}

.submenu li:hover{
background:#4caf50;
}

.sidebar a{
display:block;
padding:12px;
color:white;
text-decoration:none;
}

.sidebar a:hover{
background:#388e3c;
border-radius:5px;
}

/* LOGOUT */

.logout-container{
margin-top:auto;
}

.logout-btn{
width:100%;
padding:10px;
background:#d32f2f;
border:none;
color:white;
border-radius:5px;
cursor:pointer;
}

.logout-btn:hover{
background:#b71c1c;
}

/* MAIN */

.main-content{
flex:1;
padding:30px;
overflow:auto;
}

.header{
margin-bottom:20px;
}

.form-card{
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
max-width:700px;
margin:auto;
}

.form-card h2{
text-align:center;
margin-bottom:20px;
color:#2e7d32;
}

.form-card input,
.form-card select,
.form-card textarea{
width:100%;
padding:10px;
margin-bottom:15px;
border-radius:5px;
border:1px solid #ccc;
}

.form-card button{
padding:10px 20px;
background:#2e7d32;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

.form-card button:hover{
background:#388e3c;
}

.success{
color:green;
text-align:center;
margin-bottom:10px;
}

.error{
color:red;
text-align:center;
margin-bottom:10px;
}

.product-img{
width:150px;
margin-bottom:10px;
border-radius:5px;
}

</style>
</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<div class="logo">
<img src="images/h1logo.png">
</div>

<ul>

<li>
<details>
<summary>Products</summary>
<ul class="submenu">
<li><a href="addproduct.php">Add Product</a></li>
<li><a href="editproduct.php">Edit Product</a></li>
</ul>
</details>
</li>

<li>
<details>
<summary>Orders</summary>
<ul class="submenu">
<li><a href="adminorders.php">View Orders</a></li>
</ul>
</details>
</li>

<li><a href="#">Sellers</a></li>
<li><a href="#">Maintenance</a></li>
<li><a href="#">Reports and Bills</a></li>

</ul>

<div class="logout-container">
<form method="POST" action="logout.php">
<button class="logout-btn">Logout</button>
</form>
</div>

</div>

<!-- MAIN CONTENT -->

<div class="main-content">

<div class="header">
<h2>WELCOME ADMIN : <?php echo $row['username']; ?></h2>
</div>

<div class="form-card">

<h2>Edit Product</h2>

<?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="product_name" value="<?php echo $product['product_name']; ?>" placeholder="Product Name" required>

<select name="category" required>

<option value="">Select Category</option>

<?php
foreach(array_keys($fieldsByCategory) as $cat){
$selected = $product['category']==$cat ? "selected":"";
echo "<option value='$cat' $selected>$cat</option>";
}
?>

</select>

<?php
if($product['image']){
echo '<img class="product-img" src="data:image/jpeg;base64,'.base64_encode($product['image']).'">';
}
?>

<input type="file" name="image">

<?php

$category=$product['category'];

if(isset($fieldsByCategory[$category])){

foreach($fieldsByCategory[$category] as $field){

$value=isset($product[$field])?$product[$field]:"";

$label=ucwords(str_replace("_"," ",$field));

echo "<input type='text' name='$field' value='$value' placeholder='$label' required>";

}

}

?>

<button type="submit" name="submit">Update Product</button>

</form>

</div>

</div>

</body>
</html>