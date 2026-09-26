<?php
// sellersidebar.php

// ✅ SAFE SESSION (prevents "already started" error)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --sidebar-width: 265px;
    --accent: #22c55e;
    --accent-light: #4ade80;
    --text: #f0fdf4;
    --muted: #94a3b8;
}

/* BODY FIX */
body{
    margin:0;
    font-family:'Nunito',sans-serif;
    display:flex;
}

/* SIDEBAR FIXED */
.seller-sidebar{
    width:var(--sidebar-width);
    min-width:var(--sidebar-width);
    max-width:var(--sidebar-width);
    flex-shrink:0;
    min-height:100vh;
    position:fixed;
    color:var(--text);
    display:flex;
    flex-direction:column;
    box-shadow:5px 0 5px rgba(0,0,0,0.6);
    border-right:1px solid rgba(255,255,255,0.05);
    overflow:hidden;
}

/* GLOW */
.seller-sidebar::before{
    content:'';
    position:absolute;
    width:250px;
    height:250px;
    background:radial-gradient(circle,#22c55e55,transparent);
    top:-80px;
    left:-80px;
    filter:blur(80px);
}

.seller-sidebar::after{
    content:'';
    position:absolute;
    width:200px;
    height:200px;
    background:radial-gradient(circle,#4ade8055,transparent);
    bottom:-60px;
    right:-60px;
    filter:blur(70px);
}

/* HEADER */
.seller-sidebar-header{
    padding:25px;
}

/* LOGO FIX */
.logo{
    display:flex;
    justify-content:center;
    align-items:center;
}

.logo img{
    width:150px;
    height:60px;
    object-fit:contain;
    display:block;
    margin:auto;
}

/* WELCOME */
.seller-welcome{
    margin-top:15px;
    background:rgba(34,197,94,0.1);
    padding:12px;
    border-radius:10px;
    position:relative;
}

.seller-welcome::after{
    content:'';
    width:8px;
    height:8px;
    background:#22c55e;
    position:absolute;
    top:10px;
    right:10px;
    border-radius:50%;
    box-shadow:0 0 8px #22c55e;
}

.greeting{
    font-size:10px;
    color:black;
    text-transform:uppercase;
}

.seller-name{
    font-weight:700;
    font-size:14px;
    color:black;
}

/* NAV */
.seller-nav{
    flex:1;
    padding:15px;
}

.seller-nav-label{
    font-size:12px;
    color:black;
    margin:10px 0 5px;
}

.seller-nav a,
.menu-parent{
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px;
    border-radius:10px;
    color:black;
    text-decoration:none;
    font-size:14px;
    transition:0.3s;
    cursor:pointer;
}

/* HOVER */
.seller-nav a:hover,
.menu-parent:hover{
    background:rgba(34,197,94,0.15);
    color:#4ade80;
    transform:translateX(5px);
}

/* ACTIVE */
.seller-nav a.active{
    background:rgba(34,197,94,0.25);
    color:#22c55e;
    box-shadow:inset 3px 0 0 #22c55e;
}

/* ICON */
.nav-icon{
    width:20px;
    text-align:center;
}

/* SUB MENU */
.sub-links{
    display:none;
    padding-left:15px;
}

.nav-group.open .sub-links{
    display:block;
}

.sub-links a{
    font-size:13px;
    padding:8px;
}

/* FOOTER */
.seller-sidebar-footer{
    padding:15px;
}

.seller-logout-btn{
    display:block;
    text-align:center;
    padding:8px 12px;
    font-size:13px;
    background:rgba(239,68,68,0.1);
    color:red;
    border-radius:8px;
    text-decoration:none;
    transition:0.3s;
    width:60%;
}

.seller-logout-btn:hover{
    background:rgba(239,68,68,0.2);
}

/* MAIN CONTENT FIX */
.main-content{
    margin-left:var(--sidebar-width);
    padding:30px;
    width:calc(100% - var(--sidebar-width));
    box-sizing:border-box;
}
</style>

<div class="seller-sidebar">

    <div class="seller-sidebar-header">
        <div class="logo">
            <img src="images/h1logo.png" alt="Logo">
        </div>

        <div class="seller-welcome">
            <div class="greeting">🌾 Seller Portal</div>
            <div class="seller-name">
                <?= isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Seller' ?>
            </div>
        </div>
    </div>

    <div class="seller-nav">

        <div class="seller-nav-label">Main</div>
        <a href="sellerdashboard.php"
           class="<?= $current_page=='sellerdashboard.php' ? 'active' : '' ?>">
           <span class="nav-icon">🏠</span> Dashboard
        </a>

        <div class="seller-nav-label">Catalogue</div>
        <div class="nav-group">
            <div class="menu-parent">
                <span class="nav-icon">📦</span> Products
            </div>
            <div class="sub-links">
                <a href="seller_addproduct.php">➕ Add Product</a>
                <a href="seller_viewproducts.php">📋 View Products</a>
            </div>
        </div>

        <div class="seller-nav-label">Orders</div>
        <div class="nav-group">
            <div class="menu-parent">
                <span class="nav-icon">🛒</span> Orders
            </div>
            <div class="sub-links">
                <a href="current_orders.php">⏳ Current Orders</a>
                <a href="completed_orders.php">✅ Completed Orders</a>
				<a href="cancleorder.php">❌ Cancelld Orders</a>
            </div>
        </div>

        <div class="seller-nav-label">Finance</div>
        <a href="sellerbillsandreport.php"
           class="<?= $current_page=='sellerbillsandreport.php' ? 'active' : '' ?>">
            <span class="nav-icon">🧾</span> Reports & Bills
        </a>

    </div>

    <div class="seller-sidebar-footer">
        <a href="logout.php" class="seller-logout-btn">🚪 Logout</a>
    </div>

</div>

<script>
// submenu stability
document.querySelectorAll('.menu-parent').forEach(menu=>{
    menu.addEventListener('click',()=>{
        document.querySelectorAll('.nav-group').forEach(g=>{
            if(g !== menu.parentElement){
                g.classList.remove('open');
            }
        });
        menu.parentElement.classList.toggle('open');
    });
});
</script>