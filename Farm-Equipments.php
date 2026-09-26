<?php
session_start();
include "db/db.php";

/* FILTER VALUES */
$category = isset($_GET['category']) ? trim(urldecode($_GET['category'])) : '';
$brand    = $_GET['brand'] ?? '';
$search   = $_GET['search'] ?? '';
$min      = $_GET['min'] ?? '';
$max      = $_GET['max'] ?? '';
$hp       = $_GET['hp'] ?? '';

/* ✅ CATEGORY */
$cat_query = mysqli_query($conn,
"SELECT DISTINCT category FROM products 
 WHERE TRIM(LOWER(status))='active'");

/* ✅ BRAND */
$brand_sql = "SELECT DISTINCT brand FROM products 
              WHERE TRIM(LOWER(status))='active'";
if($category){
    $cat = mysqli_real_escape_string($conn, $category);
    $brand_sql .= " AND LOWER(TRIM(category))='".strtolower($cat)."'";
}
$brand_query = mysqli_query($conn,$brand_sql);

/* ✅ HP */
$hp_sql = "SELECT DISTINCT hp FROM products 
           WHERE TRIM(LOWER(status))='active' 
           AND hp IS NOT NULL";
if($category){
    $cat = mysqli_real_escape_string($conn, $category);
    $hp_sql .= " AND LOWER(TRIM(category))='".strtolower($cat)."'";
}
$hp_query = mysqli_query($conn,$hp_sql);

/* ✅ MAIN QUERY */
$sql = "SELECT * FROM products 
        WHERE TRIM(LOWER(status))='active'";

/* ✅ FIXED CATEGORY FILTER */
if($category){
    $cat = mysqli_real_escape_string($conn, $category);
    $sql .= " AND LOWER(TRIM(category))='".strtolower($cat)."'";
}

if($brand)    $sql .= " AND brand='".mysqli_real_escape_string($conn,$brand)."'";
if($hp)       $sql .= " AND hp='".(int)$hp."'";
if($search)   $sql .= " AND product_name LIKE '%".mysqli_real_escape_string($conn,$search)."%'";
if($min)      $sql .= " AND price >= ".(int)$min;
if($max)      $sql .= " AND price <= ".(int)$max;

$sql .= " ORDER BY created_at DESC";
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Krushi Saarthi</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
/* SAME CSS (UNCHANGED) */
:root{
  --green-dark:#064e3b;
  --green:#10b981;
  --light:#ecfdf5;
  --bg:#f0fdf4;
}

body{
  margin:0;
  font-family:Inter;
  background:var(--bg);
  padding-top:170px; /* increased for fixed bars */
}

/* HEADER */
header{
  display:flex;align-items:center;justify-content:center;
  gap:12px;padding:15px;background:white;
  box-shadow:0 2px 10px rgba(0,0,0,.05);
  position:fixed;top:0;width:100%;z-index:1000;
}
header img{height:50px}

/* HOME BUTTON */
.home-btn{
  position:absolute;left:20px;top:50%;
  transform:translateY(-50%);
  padding:10px 16px;border-radius:10px;
  background:radial-gradient(circle,#22c55e55,transparent);
  color:black;font-weight:600;text-decoration:none;
}

/* ✅ CATEGORY BAR FIXED */
.category-bar{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
  justify-content:center;
  padding:15px;
  background:white;

  position:fixed;
  top:80px; /* below header */
  width:100%;
  z-index:999;
  box-shadow:0 2px 8px rgba(0,0,0,.05);
}

.category-btn{
  padding:10px 18px;
  border:none;
  border-radius:30px;
  background:#e6f4f1;
  cursor:pointer;
  font-weight:600;
}
.category-btn.active{
  background:linear-gradient(135deg,#059669,#065f46);
  color:white;
}

/* ✅ SEARCH BAR FIXED */
.search-bar{
  text-align:center;
  padding:10px;
  background:white;

  position:fixed;
  top:140px; /* below category */
  width:100%;
  z-index:998;
  box-shadow:0 2px 8px rgba(0,0,0,.05);
}

.search-bar input{
  width:60%;
  padding:12px;
  border-radius:25px;
  border:1px solid #ccc;
}

/* MAIN */
.container{
  display:flex;
  gap:20px;
  padding:20px;
}

/* SIDEBAR (scrollable) */
.sidebar{
  width:260px;
  padding:20px;
  border-radius:20px;
  background:rgba(255,255,255,0.8);
  backdrop-filter:blur(12px);
  box-shadow:0 10px 25px rgba(0,0,0,.1);

  position:sticky;
  top:200px;

  max-height:calc(100vh - 220px);
  overflow-y:auto;
}

/* PRICE BOX */
.price-box {
  display:flex;
  flex-direction:column;
  gap:10px;
  margin-top:15px;
  padding:5px;
  background:rgba(255,255,255,0.9);
  border-radius:20px;
  box-shadow:0 8px 20px rgba(0,0,0,0.1);
}

.price-box input{
  width:88%;
  padding:12px;
  border-radius:12px;
  border:1px solid #ccc;
}

.price-btn{
  padding:12px;
  border:none;
  border-radius:12px;
  background:linear-gradient(135deg,#10b981,#065f46);
  color:white;
  font-weight:600;
  cursor:pointer;
}

/* FILTER CHIPS */
.chip{
  display:inline-block;
  padding:8px 14px;
  margin:5px;
  border-radius:20px;
  background:#e6f4f1;
  color:#065f46;
  text-decoration:none;
}
.chip.active{
  background:#059669;
  color:white;
}

/* GRID */
.grid{
  flex:1;
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
  gap:20px;
}

/* CARD */
.card{
  background:white;
  border-radius:18px;
  overflow:hidden;
  box-shadow:0 8px 20px rgba(0,0,0,.1);
  display:flex;
  flex-direction:column;
  height:100%;
}

.card img{
  width:100%;
  height:200px;
  object-fit:cover;
}

.card-content{
  padding:15px;
  display:flex;
  flex-direction:column;
  flex-grow:1;
}

/* BUTTON */
.buy-btn{
  display:block;
  margin-top:auto;
  padding:10px;
  text-align:center;
  background:linear-gradient(135deg,#10b981,#065f46);
  color:white;
  border-radius:10px;
  text-decoration:none;
  font-weight:600;
}

.disabled{
  background:gray !important;
  pointer-events:none;
}

/* FOOTER */
footer{
  position:fixed;
  bottom:0;
  width:100%;
  height:8px;
  background:linear-gradient(to right,#ffffff,#a0f9a0);
  text-align:center;
  padding:10px;
}</style>
</head>

<body>

<header>
<a href="index.php" class="home-btn">← Home</a>
<img src="images/h1logo.png" style="width:190px; height:60px;">
</header>

<!-- CATEGORY -->
<div class="category-bar">
<?php while($cat = mysqli_fetch_assoc($cat_query)){ ?>
<a href="?category=<?= urlencode($cat['category']) ?>">
<button class="category-btn <?= ($category==$cat['category'])?'active':'' ?>">
<?= $cat['category'] ?>
</button>
</a>
<?php } ?>
</div>

<!-- SEARCH -->
<div class="search-bar">
<form>
<input type="text" name="search" placeholder="Search..." value="<?= $search ?>">
<input type="hidden" name="category" value="<?= $category ?>">
</form>
</div>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">
<h3>Filters</h3>

<h4>Brand</h4>
<a href="?category=<?= urlencode($category) ?>" class="chip <?= ($brand=='')?'active':'' ?>">All</a>

<?php while($b=mysqli_fetch_assoc($brand_query)){ ?>
<a href="?category=<?= urlencode($category) ?>&brand=<?= urlencode($b['brand']) ?>" 
class="chip <?= ($brand==$b['brand'])?'active':'' ?>">
<?= $b['brand'] ?>
</a>
<?php } ?>

<h4>Price</h4>
<form class="price-box" method="get">
<input type="number" name="min" value="<?= $min ?>" placeholder="Min">
<input type="number" name="max" value="<?= $max ?>" placeholder="Max">

<input type="hidden" name="category" value="<?= $category ?>">
<input type="hidden" name="brand" value="<?= $brand ?>">
<input type="hidden" name="hp" value="<?= $hp ?>">
<input type="hidden" name="search" value="<?= $search ?>">

<button class="price-btn">Apply Filter</button>
</form>
</div>

<!-- PRODUCTS -->
<div class="grid">

<?php 
if(mysqli_num_rows($result)>0){
while($row=mysqli_fetch_assoc($result)){ 

$imagePath = "images/no-image.png";
if(!empty($row['image'])){
    $fullPath = "/Applications/xampp/htdocs/Krushi Saarthi/".$row['image'];
    if(file_exists($fullPath)){
        $imagePath = $row['image'];
    }
}

$stock = 0;
if(isset($row['equipment'])) $stock = (int)$row['equipment'];
if(isset($row['quantity']))  $stock = (int)$row['quantity'];
?>

<div class="card">
<img src="<?= $imagePath ?>">

<div class="card-content">

<div style="flex-grow:1;">
<h4><?= $row['product_name'] ?></h4>
<p><b>Brand:</b> <?= $row['brand'] ?></p>
<p><b>Category:</b> <?= $row['category'] ?></p>

<?php if(!empty($row['hp'])){ ?>
<p><b>HP:</b> <?= $row['hp'] ?></p>
<?php } ?>

<p><b>Price:</b> ₹<?= number_format($row['price']) ?></p>
</div>

<?php
if(isset($_SESSION['user_id'])){
    $buyLink = "buy.php?id=".$row['id'];
} else {
    $_SESSION['redirect_url'] = "buy.php?id=".$row['id'];
    $buyLink = "userlogin.php";
}
?>

<?php if($stock <= 0){ ?>
    <p style="color:red; font-weight:bold;">Out of Stock</p>
    <a class="buy-btn disabled">Not Available</a>
<?php } else { ?>
    <a href="<?= $buyLink ?>" class="buy-btn">Buy Now</a>
<?php } ?>

</div>
</div>

<?php }} else { ?>
<p>No products found</p>
<?php } ?>

</div>
</div>

<footer>
© 2026 Krushi Saarthi 🌱
</footer>

</body>
</html>