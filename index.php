<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Krushi Saarthi</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
/* ===== GLOBAL RESET ===== */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
html{scroll-behavior:smooth}
body{
    background:#000;
    color:#fff;
    line-height:1.7;
}
a{text-decoration:none}

/* ===== FIXED HEADER ===== */
.sticky-wrapper{
    position:fixed;
    top:0;
    width:100%;
    z-index:2000;
}

/* ===== TOP HEADER ===== */
.top-header{
    height:110px;
    background:rgba(255,255,255,0.96);
    display:flex;
    justify-content:center;
    align-items:center;
    border-bottom:2px solid #d6b44c;
    transition:0.3s ease;
}
.logo-section img{
    height:90px;
    width:280px;
    transition:0.3s ease;
}
.top-header.shrink{height:75px}
.top-header.shrink img{height:60px}

/* ===== NAV BAR ===== */
.nav-bar{
    background:#ffffff;
    padding:8px 0;
    box-shadow:0 8px 20px rgba(0,0,0,0.25);
}
.nav-container{
    display:flex;
    align-items:center;
    position:relative;
}

/* SETTINGS */
.nav-settings{
    position:absolute;
    left:20px;
    cursor:pointer;
}

/* 3 DOTS */
.dots span{
    width:6px;height:6px;
    background:#1e6f42;
    border-radius:50%;
    display:block;
    margin:2px 0;
}

/* SETTINGS MENU */
.settings-menu{
    position:absolute;
    top:35px;
    left:0;
    background:#fff;
    border-radius:12px;
    box-shadow:0 18px 40px rgba(0,0,0,0.3);
    min-width:180px;
    padding:8px 0;
    opacity:0;
    visibility:hidden;
    transform:translateY(-10px);
    transition:all 0.3s ease;
    z-index:999;
}

/* SHOW ON HOVER */
.nav-settings:hover .settings-menu{
    opacity:1;
    visibility:visible;
    transform:translateY(0);
}

/* SHOW WHEN ACTIVE (CLICK) */
.settings-menu.active{
    opacity:1;
    visibility:visible;
    transform:translateY(0);
}

.settings-menu a{
    display:flex;
    align-items:center;
    gap:10px;
    padding:13px 18px;
    color:#1e6f42;
    font-weight:600;
}
.settings-menu a:hover{
    background:#f5fbf7;
}

/* NAV MENU */
.nav-menu{
    list-style:none;
    display:flex;
    justify-content:center;
    gap:28px;
    width:100%;
}
.nav-menu a{
    color:#1e6f42;
    font-size:16px;
    font-weight:700;
    position:relative;
}
.nav-menu a::after{
    content:"";
    position:absolute;
    left:0; bottom:-6px;
    width:0; height:2px;
    background:#d6b44c;
    transition:0.3s;
}
.nav-menu a:hover::after{width:100%}

/* DROPDOWN */
.nav-menu li{position:relative}
.dropdown-menu{
    display:none;
    list-style:none;
    position:absolute;
    top:100%; left:0;
    background:#fff;
    border-radius:12px;
    min-width:200px;
    box-shadow:0 15px 35px rgba(0,0,0,0.25);
}
.dropdown-menu li a{
    padding:12px 18px;
    display:block;
    color:#1e6f42;
}
.dropdown-menu li a:hover{background:#f5fbf7}
.nav-menu li:hover > .dropdown-menu{display:block}

/* ===== PAGE OFFSET ===== */
.page-offset{height:160px}

/* ===== HERO SECTION ===== */
.hero{
    position:relative;
    height:100vh;
    overflow:hidden;
}
.hero-slider{
    position:absolute;
    inset:0;
}
.hero-slide{
    position:absolute;
    inset:0;
    background-size:cover;
    background-position:center;
    opacity:0;
    animation:fadeSlide 36s infinite;
}
.hero-slide:nth-child(1){background-image:url("images/Sprayers.jpg");animation-delay:0s;}
.hero-slide:nth-child(2){background-image:url("images/farm1.png");animation-delay:6s;}
.hero-slide:nth-child(3){background-image:url("images/farm2.png");animation-delay:12s;}
.hero-slide:nth-child(4){background-image:url("images/farm3.png");animation-delay:18s;}
.hero-slide:nth-child(5){background-image:url("images/feed.jpg");animation-delay:24s;}
.hero-slide:nth-child(6){background-image:url("images/sub.jpg");animation-delay:30s;}


@keyframes fadeSlide{
    0%{opacity:0; transform:scale(1.05);}
    5%{opacity:1; transform:scale(1);}
    20%{opacity:1;}
    25%{opacity:0;}
    100%{opacity:0;}
}

.hero::after{
    content:"";
    position:absolute;
    inset:0;
    background:rgba(0,0,0,0.55);
    z-index:1;
}

.welcome-hero{
    position:absolute;
    inset:0;
    z-index:2;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
    padding:20px;
}
.welcome-hero h1{font-size:56px;font-weight:800;}
.welcome-hero span{color:#d6b44c}
.welcome-hero p{max-width:750px;margin-top:15px;font-size:20px;}
.hero-btn{
    margin-top:35px;
    padding:14px 34px;
    background:linear-gradient(135deg,#d6b44c,#ead27a);
    color:#1e6f42;
    font-weight:700;
    border-radius:35px;
}

/* ===== PREMIUM SECTION ===== */
.premium-section,
.scheme-section{
    background:#ffffff;
    color:#1e6f42;
    padding:120px 20px;
    text-align:center;
}
.premium-section h2,
.scheme-section h2{font-size:42px;}
.section-sub{max-width:700px;margin:15px auto 60px;color:#555;}

.premium-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:30px;
    max-width:1200px;
    margin:auto;
}
.premium-card{
    background:#fff;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,0.12);
    transition:0.3s;
}
.premium-card img{
    width:100%;
    height:200px;
    object-fit:cover;
}
.premium-card h3{margin:20px 0 10px;}
.premium-card p{padding:0 20px;color:#555;}
.premium-card a{
    display:inline-block;
    margin:18px 0 25px;
    color:#d6b44c;
    font-weight:700;
}
.premium-card:hover{transform:translateY(-10px);}

/* ===== SCHEME SECTION ===== */
.scheme-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:30px;
    max-width:1000px;
    margin:auto;
}
.scheme-card{
    background:linear-gradient(135deg,#ffffff,#fffaf0);
    border:1px solid #f1e2b6;
    border-radius:18px;
    padding:35px 25px;
    box-shadow:0 15px 35px rgba(0,0,0,0.1);
    transition:0.3s;
}
.scheme-card h3{margin-bottom:12px}
.scheme-card p{color:#555}
.scheme-card a{
    display:inline-block;
    margin-top:18px;
    color:#d6b44c;
    font-weight:700;
}
.scheme-card:hover{transform:translateY(-8px);}

/* FOOTER */
footer{
    background:#1e6f42;
    border-top:2px solid #d6b44c;
    padding:25px;
    text-align:center;
    font-size:13px;
}
</style>
</head>

<body>

<div class="sticky-wrapper">
    <div class="top-header" id="topHeader">
        <div class="logo-section">
            <img src="images/h1logo.png" alt="Krushi Saarthi">
        </div>
    </div>

    <nav class="nav-bar">
        <div class="nav-container">

            <div class="nav-settings" id="settingsBtn">
                <div class="dots"><span></span><span></span><span></span></div>

                <div class="settings-menu" id="settingsMenu">
           
                    <a href="feedback.php"><span>⭐</span> FeedBack</a>
                    <a href="contact.php"><span>📞</span> Contact Us</a>
                </div>
            </div>

<ul class="nav-menu">
    <li><a href="About-us.php">About Us</a></li>
    <li>
        <a href="#">Farm Equipment</a>
        <ul class="dropdown-menu">
            <li><a href="Farm-Equipments.php">Farm Machinery</a></li>
            <li><a href="Rent.php">Rent Equipments</a></li>
            <li><a href="Pre-Owned Equipments.php">Pre-Owned Equipments</a></li>
        </ul>
    </li>
    <li><a href="subsidy.php">Subsidy & Loans</a></li>
    <li>
        <a href="#">Login</a>
        <ul class="dropdown-menu">
            <li><a href="Adminlogin.php">Admin</a></li>
            <li><a href="Sellerlogin.php">Seller</a></li>
            <li><a href="userlogin.php">User</a></li>
        </ul>
    </li>
</ul>

        </div>
    </nav>
</div>

<div class="page-offset"></div>

<!-- HERO -->
<section class="hero">
    <div class="hero-slider">
        <div class="hero-slide"></div>
        <div class="hero-slide"></div>
        <div class="hero-slide"></div>
        <div class="hero-slide"></div>
        <div class="hero-slide"></div>
        <div class="hero-slide"></div>
    </div>

    <div class="welcome-hero">
        <h1>Welcome to <span>Krushi Saarthi</span> 🚜</h1>
        <p>Empowering Indian Farmers with Modern Equipment & Government Support</p>
        <a href="Farm-Equipments.php" class="hero-btn">Explore Services</a>
    </div>
</section>

<!-- EQUIPMENT -->
<section class="premium-section">
    <h2>Popular Farming Equipment</h2>
    <p class="section-sub">Premium machines trusted by farmers</p>

    <div class="premium-grid">
        <div class="premium-card">
            <img src="images/kubotalogo.png">
            <h3>Tractors</h3>
            <p>Reliable tractors for all farming needs.</p>
        </div>
        <div class="premium-card">
            <img src="images/Rotavator.jpg">
            <h3>Rotavators</h3>
            <p>Efficient soil preparation machines.</p>
        </div>
        <div class="premium-card">
            <img src="images/sprayers.jpg">
            <h3>Sprayers</h3>
            <p>Advanced crop protection solutions.</p>
        </div>
        <div class="premium-card">
            <img src="images/harvesters.jpeg">
            <h3>Harvesters</h3>
            <p>Fast and efficient harvesting equipment.</p>
        </div>
    </div>
</section>

<!-- SCHEMES -->
<section class="scheme-section">
    <h2>Government Schemes & Subsidies</h2>
    <p class="section-sub">Verified schemes to support Indian farmers</p>

    <div class="scheme-grid">
        <div class="scheme-card">
            <h3>PM-KISAN</h3>
            <p>₹6,000 annual income support.</p>
            <a href="https://pmkisan.gov.in/">Check</a>
        </div>
        <div class="scheme-card">
            <h3>PMFBY</h3>
            <p>Crop insurance protection.</p>
            <a href="https://pmfby.gov.in/">Check</a>
        </div>
        <div class="scheme-card">
            <h3>Equipment Subsidy</h3>
            <p>Subsidy on tractors & tools.</p>
            <a href="https://kkisan.karnataka.gov.in/">Apply</a>
        </div>
        <div class="scheme-card">
            <h3>KCC Loan</h3>
            <p>Easy farm loans at low interest.</p>
            <a href="https://www.myscheme.gov.in/schemes/kcc">Apply</a>
        </div>
        <div class="scheme-card">
            <h3>SMAM</h3>
            <p>Subsidy for agricultural mechanization.</p>
            <a href="https://www.myscheme.gov.in/schemes/smam">Apply</a>
        </div>
    </div>
</section>

<footer>
    © 2026 Krushi Saarthi — Empowering Indian Farmers 🌾 ( Developed by Unkown)
</footer>

<script>
// Shrink Header
window.addEventListener("scroll",()=>{
    document.getElementById("topHeader")
    .classList.toggle("shrink",window.scrollY>80);
});

// Click toggle
const settingsBtn=document.getElementById("settingsBtn");
const settingsMenu=document.getElementById("settingsMenu");

settingsBtn.addEventListener("click",(e)=>{
    e.stopPropagation();
    settingsMenu.classList.toggle("active");
});

// Close when clicking outside
document.addEventListener("click",(e)=>{
    if(!settingsBtn.contains(e.target)){
        settingsMenu.classList.remove("active");
    }
});
</script>

</body>
</html>
