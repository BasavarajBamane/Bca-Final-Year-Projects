<?php
session_start();
include "db/db.php";

if(!isset($_SESSION['seller_id'])){
    header("Location: sellerlogin.php");
    exit();
}

$id = $_SESSION['seller_id'];

/* TOTAL PRODUCTS */
$product_count = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) AS total FROM products WHERE seller_id='$id'")
)['total'] ?? 0;

/* TOTAL PRICE */
$price = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT SUM(price) AS total FROM products WHERE seller_id='$id'")
)['total'] ?? 0;

/* PRODUCT LIST */
$product_sql = "SELECT * FROM products WHERE seller_id='$id' ORDER BY id DESC";
$product_result = mysqli_query($conn,$product_sql);

/* SELLER NAME */
$product_sql1 = "SELECT fullname FROM sellerregister WHERE seller_id='$id'";
$res = mysqli_query($conn,$product_sql1);
$row = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seller Dashboard</title>

<style>
body{
    font-family:'Nunito',sans-serif;
    margin:0;
    background:#f8fafc;
}

/* MAIN */
.main-content{
    padding:25px;
    margin-left:265px;
}

/* WELCOME */
.welcome-box h2{
    color:#022c22;
}

/* DASHBOARD */
.dashboard{
    display:flex;
    gap:20px;
    margin:20px 0;
}

.card{
    flex:1;
    padding:20px;
    background:#fff;
    border-radius:10px;
    border:1px solid #e5e7eb;
    text-align:center;
}

.card h2{
    color:#022c22;
}

/* SEARCH */
.search-box input{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #ddd;
    margin-bottom:15px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

th{
    background:linear-gradient(160deg,#020617,#052e16,#022c22);
    color:#fff;
}

th,td{
    padding:12px;
    border-bottom:1px solid #eee;
}

tr:nth-child(even){
    background:#fafafa;
}

/* IMAGE */
.product-img{
    width:50px;
    height:50px;
    border-radius:6px;
    object-fit:cover;
}

/* FOOTER */
.fixed-footer{
    position:fixed;
    bottom:0;
    left:265px;
    width:calc(100% - 265px);
    background:radial-gradient(circle,#22c55e55,transparent);
    text-align:center;
    padding:10px;
}

/* MOBILE */
@media(max-width:768px){
    .main-content{margin-left:0;}
    .fixed-footer{left:0;width:100%;}
    .dashboard{flex-direction:column;}
}
</style>
</head>

<body>

<?php include "sellersidebar.php"; ?>

<div class="main-content">

<div class="welcome-box">
    <h2>👋 Hello, <?php echo $row['fullname']; ?></h2>
</div>

<div class="dashboard">
    <div class="card">
        <h2><?php echo $product_count; ?></h2>
        <p>Total Products</p>
    </div>

    <div class="card">
        <h2>₹<?php echo $price; ?></h2>
        <p>Total Price</p>
    </div>
</div>

<div class="search-box">
    <input type="text" id="searchInput" placeholder="Search products...">
</div>

<table id="productTable">
<tr>
<th>ID</th>
<th>Proudct Name</th>
<th>Seller ID</th>
<th>Category</th>
<th>Brand</th>
<th>HP</th> <!-- ✅ NEW -->
<th>Price</th>
<th>Qty</th>
<th>Image</th>
</tr>

<?php
if(mysqli_num_rows($product_result)>0){
while($prod=mysqli_fetch_assoc($product_result)){
?>
<tr>
<td><?php echo $prod['id']; ?></td>
<td><?php echo $prod['product_name']; ?></td>
<td><?php echo $prod['seller_id']; ?></td>
<td><?php echo $prod['category']; ?></td>
<td><?php echo $prod['brand']; ?></td>

<!-- ✅ SHOW HP -->
<td><?php echo $prod['hp']; ?></td>

<td>₹<?php echo $prod['price']; ?></td>
<td><?php echo $prod['quantity']; ?></td>

<td>
<?php
$image = $prod['image'];

if(!empty($image)){
    
    if(strpos($image, 'images/') !== false){
        $path = $image;
    } else {
        $path = "images/" . $image;
    }

    if(file_exists($path)){
        echo "<img class='product-img' src='$path'>";
    } else {
        echo "Image Missing";
    }

} else {
    echo "No Image";
}
?>
</td>
</tr>
<?php
}
}else{
echo "<tr><td colspan='9'>No Products Found</td></tr>";
}
?>

</table>

</div>

<footer class="fixed-footer">
© 2026 Seller Panel | All Rights Reserved
</footer>

<script>
const searchInput = document.getElementById("searchInput");
const rows = document.querySelectorAll("#productTable tr");

searchInput.addEventListener("keyup", function(){
    const filter = this.value.toLowerCase();
    rows.forEach((row,index)=>{
        if(index===0) return;
        row.style.display = row.textContent.toLowerCase().includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>