<?php
session_start();
include "db/db.php";

/* LOGIN CHECK */
if(!isset($_SESSION['email'])){
    header("Location: userlogin.php");
    exit();
}

$email = $_SESSION['email'];
$success = "";

/* FETCH USER */
$stmt = mysqli_prepare($conn, "SELECT * FROM usersregister WHERE email=?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$user_id = $user['id'];

/* CANCEL ORDER */
if(isset($_POST['cancel_order'])){
    $order_id = intval($_POST['order_id']);
    $reason = trim($_POST['reason']);
    $status_check = trim($_POST['status']);

    if($status_check == "Out for Delivery"){
        $success = "You are not allowed to cancel orders that are Out for Delivery!";
    } else {
        $stmt_cancel = mysqli_prepare($conn, 
            "UPDATE orders 
             SET status='Cancelled', cancel_reason=? 
             WHERE order_id=? 
             AND user_id=? 
             AND status != 'Delivered'"
        );
        mysqli_stmt_bind_param($stmt_cancel, "sii", $reason, $order_id, $user_id);
        mysqli_stmt_execute($stmt_cancel);

        $success = "Order cancelled successfully!";
    }
}

/* FETCH ORDERS WITH PRODUCT INFO */
$stmt = mysqli_prepare($conn, "
    SELECT o.*, p.brand, p.category, p.hp, p.image, p.product_name
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$order_result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My Orders</title>

<style>
body{margin:0;font-family:Poppins;background:#f4f7fb;}
.header{display:flex;justify-content:space-between;align-items:center;padding:12px 20px;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,0.1);}
.logo img{height:45px;width:170px;}
.header-right{display:flex;align-items:center;gap:15px;}
.home-btn{background:#2c7a2c;color:#fff;padding:8px 15px;border-radius:20px;text-decoration:none;}
.profile{display:flex;align-items:center;gap:8px;cursor:pointer;position:relative;}
.profile img{width:40px;height:40px;border-radius:50%;}
.grid{padding:20px;display:flex;flex-direction:column;gap:15px;}
.card{display:flex;gap:15px;background:#fff;padding:15px;border-radius:12px;box-shadow:0 3px 10px rgba(0,0,0,0.1);}
.card img{width:300px;height:250px;object-fit:cover;border-radius:8px;}
.details{flex:1;}
.details p{margin:3px 0;font-size:14px;}
.badge{padding:5px 10px;border-radius:20px;color:#fff;font-size:12px;}
.Pending{background:#f39c12;}
.Confirmed{background:#3498db;}
.Shipped{background:#8e44ad;}
.Out-for-Delivery{background:#e67e22;}
.Delivered{background:#2ecc71;}
.Cancelled{background:#e74c3c;}
.track-container{margin-top:15px;position:relative;}
.track-line{position:relative;height:6px;background:#ddd;border-radius:10px;}
.progress{position:absolute;height:6px;background:#2ecc71;border-radius:10px;}
.vehicle{position:absolute;top:-12px;font-size:20px;}
.steps{display:flex;justify-content:space-between;margin-top:8px;}
.step{text-align:center;font-size:11px;}
.circle{width:14px;height:14px;background:#ddd;border-radius:50%;margin:auto;}
.active .circle{background:#2ecc71;}
.invoice-btn,.cancel-btn{
    margin-top:5px;
    display:inline-block;
    padding:5px 10px;
    border-radius:20px;
    text-decoration:none;
    color:#fff;
}
.invoice-btn{background:#3498db;}
.cancel-btn{background:#e74c3c;border:none;cursor:pointer;}
.footer{position:fixed;bottom:0;width:100%;background:#2c7a2c;color:#fff;text-align:center;padding:8px;}
.btn-group{display:flex;gap:10px;align-items:center;margin-top:5px;}

/* CANCEL MODAL */
.modal{
    display:none;
    position:fixed;
    top:0;left:0;width:100%;height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:1000;
}
.modal-content{
    background:#fff;
    padding:20px;
    border-radius:10px;
    width:300px;
    text-align:center;
}
.modal-content textarea{
    width:100%;
    height:70px;
    margin-bottom:10px;
    padding:5px;
    border-radius:5px;
    border:1px solid #ccc;
}
.modal-content button{
    padding:6px 12px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}
.submit-btn{background:#e74c3c;color:#fff;}
.close-btn{background:#3498db;color:#fff;margin-left:10px;}
</style>
</head>

<body>

<div class="header">
    <div class="logo">
        <img src="images/h1logo.png">
    </div>
    <div class="header-right">
        <a href="userdashboard.php" class="home-btn">Dashboard</a>
        <div class="profile">
            <img src="<?php echo $user['profile_img']; ?>">
            <span><?php echo $user['fullname']; ?></span>
        </div>
    </div>
</div>

<?php if($success) echo "<p style='color:green;text-align:center;'>$success</p>"; ?>

<div class="grid">

<?php while($row=mysqli_fetch_assoc($order_result)): 
$steps=["Pending","Confirmed","Shipped","Out for Delivery","Delivered"];
$current=array_search($row['status'],$steps);
$progress = ($row['status']=="Delivered") ? 100 : (($current!==false) ? ($current/(count($steps)-1))*100 : 0);
?>

<div class="card">
    <img src="<?php echo $row['image']; ?>">

    <div class="details">
        <h4><?php echo $row['product_name']; ?></h4>

        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
            <span class="badge <?php echo str_replace(" ","-",$row['status']); ?>">
                <?php echo $row['status']; ?>
            </span>
            <span style="font-weight:bold;color:#2c7a2c;">₹<?php echo $row['total']; ?></span>
        </div>

        <p><strong>Brand:</strong> <?php echo $row['brand']; ?></p>
        <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
        <p><strong>HP:</strong> <?php echo $row['hp']; ?></p>

        <?php if($row['status']=="Cancelled"): ?>
            <p style="color:red;">Reason: <?php echo $row['cancel_reason']; ?></p>
        <?php else: ?>
            <div class="track-container">
                <div class="track-line">
                    <div class="progress" style="width:<?php echo $progress; ?>%"></div>
                    <div class="vehicle" style="left:<?php echo $progress; ?>%">🚚</div>
                </div>
                <div class="steps">
                    <?php foreach($steps as $i=>$s): ?>
                        <div class="step <?php if($i <= $current) echo 'active'; ?>">
                            <div class="circle"></div>
                            <small><?php echo $s; ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
                <br>
                <!-- Buttons placed inline with the track line -->
                <div class="btn-inline">
                    <button class="cancel-btn" onclick="openModal(<?php echo $row['order_id']; ?>,'<?php echo $row['status']; ?>')">Cancel Order</button>
                    <a href="invoice.php?id=<?php echo $row['order_id']; ?>" target="_blank" class="invoice-btn">Download Invoice</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php endwhile; ?>

</div>

<!-- CANCEL MODAL -->
<div class="modal" id="cancelModal">
    <div class="modal-content" id="modalContent">
        <span class="close-modal-x" onclick="closeModal()">&times;</span>
        <h3>Cancel Order</h3>
        <form method="POST" id="cancelForm">
            <input type="hidden" name="order_id" id="modal_order_id">
            <input type="hidden" name="status" id="modal_status">
            <textarea name="reason" id="modal_reason" placeholder="Enter cancellation reason" required></textarea><br>
            <button type="submit" name="cancel_order" class="submit-btn">Submit</button>
            <button type="button" class="close-btn" onclick="closeModal()">Close</button>
        </form>
    </div>
</div>

<div class="footer">© 2026 Krushi Saarthi</div>

<script>
function openModal(orderId, status){
    document.getElementById("modal_order_id").value = orderId;
    document.getElementById("modal_status").value = status;

    if(status == "Out for Delivery"){
        document.getElementById("modalContent").innerHTML = `
            <span class="close-modal-x" onclick="closeModal()">&times;</span>
            <h3>Cancel Order</h3>
            <p style="color:red;">You are not allowed to cancel orders that are Out for Delivery!</p>
            <button type="button" class="close-btn" onclick="closeModal()">Close</button>
        `;
    } else {
        // restore original modal content for allowed orders
        document.getElementById("modalContent").innerHTML = `
            <span class="close-modal-x" onclick="closeModal()">&times;</span>
            <h3>Cancel Order</h3>
            <form method="POST" id="cancelForm">
                <input type="hidden" name="order_id" id="modal_order_id" value="${orderId}">
                <input type="hidden" name="status" id="modal_status" value="${status}">
                <textarea name="reason" id="modal_reason" placeholder="Enter cancellation reason" required></textarea><br>
                <button type="submit" name="cancel_order" class="submit-btn">Submit</button>
                <button type="button" class="close-btn" onclick="closeModal()">Close</button>
            </form>
        `;
    }

    document.getElementById("cancelModal").style.display = "flex";
}

function closeModal(){
    document.getElementById("cancelModal").style.display = "none";
}
</script>

</body>
</html>