<?php
session_start();
include "db/db.php";

/* Check admin session */
if(!isset($_SESSION['mail'])){
    header("Location: adminlogin.php");
    exit();
}

$mail = $_SESSION['mail'];

/* Admin Info */
$sql = "SELECT * FROM adminlogin WHERE mail='$mail'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

/* Dashboard Counts */
$product_count = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT COUNT(*) AS total FROM products")
)['total'];

$user_count = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT COUNT(*) AS total FROM usersregister")
)['total'] ?? 0;

$seller_count = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT COUNT(*) AS total FROM sellerregister")
)['total'] ?? 0;

/* View Logic */
$view = isset($_GET['view']) ? $_GET['view'] : 'products';

/* Fetch Data Based on Click */
if($view == 'users'){
    $product_sql = "
    SELECT 
        id,
        fullname AS product_name,
        email AS category,
        mobile AS brand,
        created_at AS price,
        '' AS quantity,
        '' AS hp,
        profile_img AS image
    FROM usersregister 
    ORDER BY id DESC";
}
elseif($view == 'sellers'){
    $product_sql = "
    SELECT 
        seller_id AS id,
        fullname AS product_name,
        gmail AS category,
        shop_name AS brand,
        status AS price,
        mobile AS quantity,
        '' AS hp,
        panphoto AS image
    FROM sellerregister 
    ORDER BY seller_id DESC";
}
else{
    $product_sql = "SELECT * FROM products ORDER BY id DESC";
}

$product_result = mysqli_query($conn,$product_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<link rel="stylesheet" href="style.css">

<style>
:root{
    --sidebar-color:#11998e;
}

body{
    font-family: Arial, sans-serif;
    margin:0;
    padding:0;
    background:#f4f4f4;
}

.main-content{
    margin-left:260px;
    padding:20px;
    min-height: calc(100vh - 60px);
}

.header{
    background:#fff;
    padding:15px 20px;
    margin-bottom:20px;
    border-radius:8px;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

.dashboard{
    display:flex;
    gap:30px;
    margin-bottom:25px;
}

.card-link{
    flex:1;
    text-decoration:none;
    color:inherit;
}

.card{
    background:white;
    color:#fff;
    padding:35px;
    border-radius:12px;
    text-align:center;
    transition:0.3s;
    cursor:pointer;
    min-height:140px;
    width:300px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.card h2{
    font-size:32px;
    margin-bottom:10px;
}

.card p{
    font-size:16px;
    font-weight:500;
}

.card:hover{
    transform:scale(1.07);
    box-shadow:0 6px 20px rgba(0,0,0,0.2);
}

.search-box{
    margin-bottom:15px;
}

#searchInput{
    width:100%;
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
}

/* TABLE (COMPACT VERSION) */
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:6px;
    overflow:hidden;
    font-size:13px; /* smaller text */
}

table th, table td{
    padding:8px 10px; /* reduced padding */
    border-bottom:1px solid #ddd;
    text-align:center;
}

table th{
    color:#fff;
    font-size:13px;
}

/* ROW HEIGHT */
tr{
    height:45px;
}

/* IMAGE SMALL */
.product-img{
    width:45px;
    height:45px;
    object-fit:cover;
    border-radius:6px;
}
/* FOOTER */
.footer{
    position: fixed;
    bottom: 0;
    left: 270px;
    width: calc(98.5% - 260px);
    height: 40px;
    background:lightgreen;
    color:#000;
    display:flex;
    align-items:center;
    justify-content:center;
}
</style>
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="main-content">

<div class="header">
    <h1>
        👋 Welcome, 
        <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : $row['username']; ?>
    </h1>
    <p>Manage your platform efficiently 🚀</p>
</div>

<h2>Admin Dashboard</h2>

<!-- CARDS -->
<div class="dashboard">

    <a href="?view=products" class="card-link">
        <div class="card">
            <h2><?php echo $product_count; ?></h2>
            <p>Total Products</p>
        </div>
    </a>

    <a href="?view=users" class="card-link">
        <div class="card">
            <h2><?php echo $user_count; ?></h2>
            <p>Total Users</p>
        </div>
    </a>

    <a href="?view=sellers" class="card-link">
        <div class="card">
            <h2><?php echo $seller_count; ?></h2>
            <p>Total Sellers</p>
        </div>
    </a>

</div>

<!-- SEARCH -->
<div class="search-box">
    <input type="text" id="searchInput" placeholder="Search...">
</div>

<!-- TABLE -->
<!-- TABLE -->
<table id="productTable">

<tr>
    <th>ID</th>

    <?php if($view=='products'){ ?>
    <th>Name</th>
    <th>Seller Name</th>
    <th>Category</th>
    <th>Brand</th>
    <th>HP</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Image</th>

    <?php } elseif($view=='users'){ ?>
    <th>Name</th>
    <th>Email</th>
    <th>Mobile</th>
    <th>Created</th>
    <th>-</th>
    <th>Profile</th>

    <?php } else { ?>
    <th>Name</th>
    <th>Email</th>
    <th>Shop</th>
    <th>Status</th>
    <th>Mobile</th>
    <th>Pan</th>
    <?php } ?>

</tr>

<?php
if(mysqli_num_rows($product_result) > 0){
    while($prod = mysqli_fetch_assoc($product_result)){
		
        echo "<tr>";
		echo"<td>".$prod['id']."</td>";
		
        echo "<td>".$prod['id']."</td>";
        echo "<td>".$prod['product_name']."</td>";
        echo "<td>".$prod['seller_name']."</td>";
        echo "<td>".$prod['category']."</td>";
        echo "<td>".$prod['brand']."</td>";

        if($view=='products'){
            echo "<td>".$prod['hp']."</td>";
        }

        echo "<td>".$prod['price']."</td>";
        echo "<td>".$prod['quantity']."</td>";

        // IMAGE HANDLING
        $image_field = '';
        if($view=='products'){
            $image_field = $prod['image'];
        } elseif($view=='users'){
            $image_field = $prod['image']; // profile_img alias in your query
        } else {
            $image_field = $prod['image']; // panphoto alias in your query
        }

        $img_path = '';

        if(!empty($image_field)){
            // Check if image already has uploads/
            if(strpos($image_field,'uploads/') !== false){
                $img_path = $image_field;
            } else {
                $img_path = "uploads/".$image_field;
            }

            // If file does not exist, fallback
            if(!file_exists($img_path)){
                $img_path = $image_field; // try original
            }

            if(file_exists($img_path)){
                echo "<td><img class='product-img' src='$img_path'></td>";
            } else {
                echo "<td>Image Missing</td>";
            }

        } else {
            echo "<td>No Image</td>";
        }

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No Data Found</td></tr>";
}
?>
</table>
</div>

<!-- FOOTER -->
<div class="footer">
    &copy; <?php echo date("Y"); ?> Admin Dashboard
</div>

<?php include "footer.php"; ?>

<script>
const searchInput = document.getElementById("searchInput");
const rows = document.querySelectorAll("#productTable tr");

searchInput.addEventListener("keyup", function() {
    const filter = this.value.toLowerCase();

    rows.forEach((row, index) => {
        if(index === 0) return;
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>