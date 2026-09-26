<?php
session_start();
include "db/db.php";

// Check admin login
if(!isset($_SESSION['mail'])){
    header("Location: adminlogin.php");
    exit();
}

// Total Sales
$sales_query = mysqli_query($conn,"SELECT SUM(final_amount) as total FROM bills");
$sales = mysqli_fetch_assoc($sales_query);
$total_sales = $sales['total'] ?? 0;

//Total GST
$gst_query = mysqli_query($conn,"SELECT SUM(gst) as total FROM bills");
$gst = mysqli_fetch_assoc($gst_query);
$total_gst = $gst['total'] ?? 0;

// Total Orders
$order_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM orders");
$orders = mysqli_fetch_assoc($order_query);
$total_orders = $orders['total'] ?? 0;

// Fetch bills with order details
$sql = "SELECT bills.*, orders.customer_name, orders.product_name 
        FROM bills 
        JOIN orders ON bills.order_id = orders.id
        ORDER BY bills.id DESC";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Reports & Bills</title>

<link rel="stylesheet" href="style.css">

</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main-content">

    <h2 style="margin-top:5px;">Total Orders & Sales</h2>

    <!-- Summary Cards -->
    <div class="cards">
        <div class="card">
            <h3>Total Sales Amount</h3>
            <p>₹ <?php echo $total_sales; ?></p>
        </div>
		<div class="card">
            <h3>Total GST Amount</h3>
            <p>₹ <?php echo $total_gst; ?></p>
        </div>

        <div class="card">
            <h3>Total Orders</h3>
            <p><?php echo $total_orders; ?></p>
        </div>
    </div>

    <!-- Bills Table -->
    <h3>All Bills</h3>
<br>
    <table>
        <tr>
            <th>Bill No</th>
            <th>Customer</th>
            <th>Product</th>
            <th>GST</th>
            <th>Final Amount</th>
            <th>Date & Time</th>
        </tr>

        <?php if(mysqli_num_rows($result) > 0){ ?>
            <?php while($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?php echo $row['bill_no']; ?></td>
                <td><?php echo $row['customer_name']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td>₹ <?php echo $row['gst']; ?></td>
                <td>₹ <?php echo $row['final_amount']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6" style="text-align:center;">No bills found</td>
            </tr>
        <?php } ?>

    </table>

</div>
<?php include "footer.php"; ?>

</body>
</html>