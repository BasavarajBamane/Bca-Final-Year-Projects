<?php
session_start();
include "db/db.php";

/* Check admin login */
if(!isset($_SESSION['mail'])){
    header("Location: adminlogin.php");
    exit();
}

$mail = $_SESSION['mail'];

$sql = "SELECT * FROM adminlogin WHERE mail='$mail'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

/* Add Product */

if(isset($_POST['submit'])){

$product_name = $_POST['product_name'];
$category = $_POST['category'];
$brand = $_POST['brand'];
$model = $_POST['model'];
$description = $_POST['description'];
$price = $_POST['price'];
$subsidy = $_POST['subsidy_percentage'];
$quantity = $_POST['quantity'];
$status = $_POST['status'];

/* Image */

$image = NULL;

if(!empty($_FILES['image']['tmp_name'])){
$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
}

/* Insert Query */

$insert = "INSERT INTO products
(product_name,category,brand,model,description,price,subsidy_percentage,quantity,image,status)
VALUES
('$product_name','$category','$brand','$model','$description','$price','$subsidy','$quantity','$image','$status')";

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
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Product</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include "sidebar.php"; ?>

<div class="main-content">


<div class="form-card">

<h2>Add New Product</h2>

<?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="product_name" placeholder="Product Name" required>

<select name="category" required>
<option value="">Select Category</option>
<option>Tractor</option>
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

<input type="text" name="brand" placeholder="Brand">

<input type="text" name="model" placeholder="Model">

<textarea name="description" placeholder="Product Description"></textarea>

<input type="number" name="price" placeholder="Price" required>

<input type="number" name="subsidy_percentage" placeholder="Subsidy %" value="0">

<input type="number" name="quantity" placeholder="Quantity" required>

<input type="file" name="image" accept="image/*">

<select name="status">
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>

<button type="submit" name="submit">Add Product</button>

</form>

</div>

<?php include "footer.php"; ?>

</div>

</body>
</html>