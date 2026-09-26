<?php
include "db/db.php"; // DB connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rentify – Premium Equipment Rental</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root{
    --primary:#16a085;
    --primary-dark:#138f75;
    --secondary:#ecf0f1;
    --accent:#f39c12;
    --soft:#f8f9fa;
    --dark:#2c3e50;
    --light-text:#555;
    --white:#fff;
}

/* GLOBAL */
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:var(--soft); color:var(--dark);}

/* HEADER */
.site-header{
    position:fixed; top:0; width:100%; height:70px;
    background:var(--primary); display:flex;
    align-items:center; justify-content:space-between; padding:0 30px; color:var(--white); z-index:1000;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
}
.logo img{height:50px;}
.site-header h2{font-weight:600; font-size:24px;}
.header-right{
    display:flex; align-items:center; gap:10px;
}
/* Add to Home Button in header */
.add-home{
    display:inline-block; padding:6px 14px; background:var(--accent);
    color:#fff; font-weight:500; border-radius:20px; text-decoration:none; transition:0.3s; font-size:13px;
}
.add-home:hover{background:#d35400;}

/* PRODUCTS GRID */
.products{max-width:1200px; margin:90px auto 20px; display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:25px; padding:0 20px;}

/* CARD */
.card{position:relative; border-radius:15px; background:var(--white); padding:15px; box-shadow:0 6px 18px rgba(0,0,0,0.08); transition:0.3s; text-align:center;}
.card:hover{transform:translateY(-5px); box-shadow:0 12px 25px rgba(0,0,0,0.15);}
.badge{position:absolute; top:10px; left:10px; padding:5px 12px; border-radius:20px; font-size:12px; font-weight:500; color:#fff;}
.available{background:#16a085;}
.unavailable{background:#c0392b;}
.rented{background:#f39c12;}
.card img{width:100%; height:320px; border-radius:12px; object-fit:cover;}
.card h3{margin:10px 0; font-size:16px; color:var(--dark);}
.card .price{margin:5px 0; font-weight:600; color:#34495e;}
.card button{padding:8px 16px; border:none; border-radius:25px; background:var(--primary); color:#fff; cursor:pointer; font-weight:500; transition:0.3s;}
.card button:hover{background:var(--primary-dark); transform:scale(1.05);}

/* MODAL */
.modal{display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:2000;}
.modal.show{display:flex;}
.modal-content{
    background:var(--white); padding:15px 20px; border-radius:12px;
    width:90%; max-width:400px; position:relative; animation:fadeIn 0.3s;
    box-shadow:0 6px 20px rgba(0,0,0,0.2); text-align:center;
}
.modal-content span.close{
    position:absolute; top:10px; right:15px; font-size:22px; cursor:pointer; font-weight:bold;
    color:var(--dark); transition:0.3s;
}
.modal-content span.close:hover{color:var(--primary);}

/* Status badges */
.status{padding:4px 10px; border-radius:20px; display:inline-block; font-weight:500; margin-top:5px; font-size:12px;}
.status.available{background:#d4f7dc;color:#16a085;}
.status.unavailable{background:#ffd6d6;color:#c0392b;}
.status.rented{background:#ffe6c2;color:#f39c12;}
table{width:100%; margin-top:8px; border-collapse:collapse; font-size:13px;}
td,th{padding:6px 8px; border:1px solid #ddd; text-align:left; color:var(--light-text);}
.contact{margin-top:8px; font-size:14px; font-weight:500; color:var(--dark);}
.contact span{display:block; margin:3px 0;}
.contact a{color:var(--primary); font-weight:600; transition:0.3s;}
.contact a:hover{color:var(--primary-dark); text-decoration:underline;}

/* ANIMATION */
@keyframes fadeIn{from{opacity:0; transform:scale(0.95);} to{opacity:1; transform:scale(1);}}

/* FOOTER */
footer{position:fixed; bottom:0; width:100%; height:40px; display:flex; justify-content:center; align-items:center; background:var(--primary); color:#fff; font-weight:500;}

/* RESPONSIVE */
@media(max-width:768px){
    .products{grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:15px;}
    .header-right{flex-direction:column; gap:5px;}
}
</style>
</head>
<body>

<header class="site-header">
<div class="logo"><img src="images/h1logo.png" alt="Rentify Logo"></div>
<h2>Rentify – Premium Rentals</h2>
<div class="header-right">
    <a href="index.php" class="add-home"><i class="fas fa-home"></i> Add to Home</a>
</div>
</header>

<section class="products" id="products">
<?php
$query = "SELECT * FROM equipments ORDER BY id DESC";
$result = $conn->query($query);
while($row = $result->fetch_assoc()):
?>
<div class="card">
    <div class="badge <?= strtolower($row['status']) ?>"><?= $row['status'] ?></div>
    <img src="<?= $row['img'] ?>" alt="<?= $row['name'] ?>">
    <h3><?= $row['name'] ?></h3>
    <div class="price">Hourly: ₹<?= $row['hourly'] ?> | Daily: ₹<?= $row['daily'] ?></div>
    <button onclick="openModal('<?= $row['name'] ?>','<?= $row['model'] ?>','<?= $row['hourly'] ?>','<?= $row['daily'] ?>','<?= $row['status'] ?>','<?= $row['img'] ?>')">View</button>
</div>
<?php endwhile; ?>
</section>

<!-- MODAL -->
<div class="modal" id="modal">
<div class="modal-content">
<span class="close" onclick="closeModal()">✕</span>
<img id="equipImage" src="" alt="" style="width:100%; height:150px; border-radius:10px; object-fit:cover; margin-bottom:8px;">
<h2 id="equipTitle"></h2>
<p><strong>Rent Price:</strong> <span id="price"></span></p>
<p><strong>Status:</strong> <span id="status" class="status"></span></p>
<table>
<tr><th>Specification</th><th>Details</th></tr>
<tr><td>Equipment Name</td><td id="equipName"></td></tr>
<tr><td>Model</td><td id="equipModel"></td></tr>
<tr><td>Hourly Rate</td><td id="hourlyRate"></td></tr>
<tr><td>Daily Rate</td><td id="dailyRate"></td></tr>
</table>
<hr>
<h3>Owner Contact</h3>
<div class="contact">
<span><strong>Name:</strong> Krushi Saarthi</span>
<span><strong>Mobile:</strong> <a href="#" id="mobileLink" target="_blank"></a></span>
<span><strong>Email:</strong> <a href="#" id="emailLink" target="_blank"></a></span>
</div>
</div>
</div>

<footer>
© 2026 Rentify | All Rights Reserved
</footer>

<script>
function openModal(name, model, hourly, daily, status, img){
    document.getElementById("modal").classList.add("show");
    document.getElementById("equipTitle").innerText = name + " Rental Details";
    document.getElementById("equipName").innerText = name;
    document.getElementById("equipModel").innerText = model;
    document.getElementById("hourlyRate").innerText = hourly;
    document.getElementById("dailyRate").innerText = daily;
    document.getElementById("status").innerText = status;
    document.getElementById("status").className = "status " + status.toLowerCase();
    document.getElementById("equipImage").src = img;

    // WhatsApp link
    const phone = "7795104355";
    const msg = encodeURIComponent(`Hello! I am interested in renting the equipment: ${name} (${model}). Please provide more details.`);
    document.getElementById("mobileLink").href = "https://wa.me/" + phone + "?text=" + msg;
    document.getElementById("mobileLink").innerText = phone;

    // Email link
    const email = "basuhunnur002@gmail.com";
    const subject = encodeURIComponent(`Inquiry: ${name} Rental`);
    const body = encodeURIComponent(`Hello,\n\nI am interested in renting the equipment: ${name} (${model}).\nPlease provide more details.\n\nThanks!`);
    document.getElementById("emailLink").href = "mailto:" + email + "?subject=" + subject + "&body=" + body;
    document.getElementById("emailLink").innerText = email;
}

function closeModal(){
    document.getElementById("modal").classList.remove("show");
}
</script>

</body>
</html>