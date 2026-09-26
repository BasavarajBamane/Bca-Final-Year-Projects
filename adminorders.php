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
    table { width:100%; border-collapse: collapse; margin-bottom: 30px; }
    th, td { border:1px solid #ccc; padding:8px; text-align:left; }
    th { background:#f4f4f4; }
    .action-btn { padding:5px 10px; text-decoration:none; border-radius:3px; }
    .delete-btn { background:red; color:white; }
    img { max-width:50px; max-height:50px; }
    h2 { margin-top: 30px; }
    .logout-btn { padding: 5px 10px; margin:10px; cursor:pointer; }
</style>
</head>
<body>

<div class="wrapper">

<?php include "sidebar.php"; ?>

<form action="logout.php" method="POST">
    <button class="logout-btn">Logout</button>
</form>

</div>

<!-- Main Content -->
<div class="main-content">

    <h2>Current Orders (Pending)</h2>
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
                    <img src="<?php echo $r['product_image']; ?>" alt="Product Image">
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
                <a class="action-btn delete-btn" href="deleteorders.php?delete=<?php echo $r['order_id']; ?>" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</div> <!-- end main -->

<div class="footer">
© 2026 Krushi Saarthi. All rights reserved.
</div>

</div>

</body>
</html>