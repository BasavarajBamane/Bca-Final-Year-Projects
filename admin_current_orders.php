<?php
session_start();
include "db/db.php";

// ✅ Check admin login
if(!isset($_SESSION['mail'])){
    header("Location: adminlogin.php");
    exit();
}

$mail = $_SESSION['mail'];
$sql = "SELECT * FROM adminlogin WHERE mail='$mail'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

// Fetch Current Orders (Pending)
$current = mysqli_query($conn,"SELECT * FROM orders WHERE status='Pending' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Current Orders | Admin</title>

<link rel="stylesheet" href="style.css">

<style>
body {
    margin:0;
    font-family:Arial, sans-serif;
    background:#f1f5f9;
}

/* Sidebar wrapper */
.wrapper{
    display:flex;
}

/* Main Content */
.main-content{
    margin-left:260px;
    padding:20px;
    flex:1;
    padding-bottom:80px;
}

/* 🔥 Scrollable Table Container */
.table-container{
    width:100%;
    max-height:500px;   /* height control */
    overflow:auto;      /* enables scroll */
    background:#fff;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

/* Table styling */
table { 
    width:100%; 
    border-collapse: collapse; 
    min-width:1200px; /* forces horizontal scroll if needed */
}

th, td { 
    border:1px solid #ccc; 
    padding:8px; 
    text-align:left; 
    white-space:nowrap; /* prevents breaking */
}

th { 
    background:#f4f4f4; 
    position:sticky;   /* 🔥 sticky header */
    top:0;
    z-index:10;
}

tr:hover { background:#e9ecef; }

.action-btn { 
    padding:5px 10px; 
    text-decoration:none; 
    border-radius:3px; 
}

.delete-btn { 
    background:red; 
    color:white; 
}

/* Images in table */
.img123 
{ 
max-width:150px; max-height:50px; 
}

/* Headings */
h2 { margin-top: 30px; color:#0f172a; }

/* Footer */
.footer{
    position: fixed;
    bottom:0;
    left:270px;
    width: calc(98.5% - 260px);
    background:#0f172a;
    color:#fff;
    text-align:center;
    padding:15px;
    box-shadow:0 -2px 6px rgba(0,0,0,0.2);
    z-index:100;
}
</style>
</head>

<body>

<?php include "sidebar.php"; ?>
<div class="wrapper">





<!-- Main Content -->
<div class="main-content">

    <h2>Current Orders (Pending)</h2>

    <!-- 🔥 Scroll Wrapper -->
    <div class="table-container">
        <table>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Customer Name</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Image</th>
                <th>Seller ID</th>
                <th>Seller Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subsidy</th>
                <th>Total</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>

            <?php while($r = mysqli_fetch_assoc($current)){ ?>
            <tr>
                <td><?php echo $r['order_id']; ?></td>
                <td><?php echo $r['user_id']; ?></td>
                <td><?php echo $r['customer_name']; ?></td>
                <td><?php echo $r['product_id']; ?></td>
                <td><?php echo $r['product_name']; ?></td>
                <td>
                    <?php if($r['product_image'] != ''): ?>
                        <img class="img123" src="<?php echo $r['product_image']; ?>" alt="Product Image">
                    <?php else: echo 'N/A'; endif; ?>
                </td>
                <td><?php echo $r['seller_id']; ?></td>
                <td><?php echo $r['seller_name']; ?></td>
                <td><?php echo $r['quantity']; ?></td>
                <td><?php echo $r['price']; ?></td>
                <td><?php echo $r['subsidy']; ?></td>
                <td><?php echo $r['total']; ?></td>
                <td><?php echo $r['status']; ?></td>
                <td><?php echo $r['created_at']; ?></td>
                <td>
                    <a class="action-btn delete-btn" 
                       href="deleteorders.php?delete=<?php echo $r['order_id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this order?');">
                       Delete
                    </a>
                </td>
            </tr>
            <?php } ?>

        </table>
    </div>

</div>

<div class="footer">
© 2026 Krushi Saarthi. All rights reserved.
</div>

</div>

</body>
</html>