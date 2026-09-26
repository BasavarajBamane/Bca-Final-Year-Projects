```html
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>About Us | Krushi Saasthi</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

/* FIXED BACKGROUND */
.bg-image{
    position:fixed;
    top:70px;
    left:0;
    width:100%;
    height:100%;
    background:url("images/mast.jpg") no-repeat center center;
    background-size:cover;
    z-index:-2;
}

/* DARK OVERLAY */
.overlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    z-index:-1;
}

/* BODY */
body{
    color:#fff;
}

/* HEADER (FIXED) */
header{
    position:fixed;
    top:0;
    width:100%;
    height:70px;
    z-index:1000;
    background:rgba(0,0,0,0.7);
    backdrop-filter: blur(10px);
    display:flex;
    align-items:center;
    justify-content:center;
}

/* BACK BUTTON */
.back-btn{
    position:absolute;
    left:20px;
    color:#fff;
    text-decoration:none;
    font-size:15px;
    padding:6px 12px;
    border-radius:20px;
    background:rgba(255,255,255,0.1);
    transition:0.3s;
}

.back-btn:hover{
    background:#c9a227;
    color:#000;
}

/* LOGO */
.logo img{
    height:45px;
}

/* MAIN CONTENT WRAPPER */
.content-wrapper{
    margin-top:70px;
    min-height:100vh;
    overflow-y:auto;
}

/* SECTION */
section{
    padding:80px 20px;
    text-align:center;
}

/* TEXT */
h1{
    font-size:48px;
    margin-bottom:20px;
}
h2{
    font-size:34px;
    margin-bottom:20px;
}
p{
    max-width:800px;
    margin:auto;
    line-height:1.8;
    font-size:18px;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:30px;
    margin-top:40px;
}

.card{
    padding:20px;
}

/* IMPACT */
.impact h3{
    font-size:50px;
    margin-bottom:10px;
}

/* FOOTER */
footer{
    position:fixed;
    bottom:0;
    left:0;
    width:100%;
    height:40px;
    display:flex;
    justify-content:center;
    align-items:center;
    background:rgba(0,0,0,0.8);
    backdrop-filter:blur(10px);
    color:#fff;
    font-size:14px;
    z-index:1000;
}

/* RESPONSIVE */
@media(max-width:768px){
    h1{font-size:34px;}
    h2{font-size:26px;}
}
</style>
</head>

<body>

<!-- BACKGROUND -->
<div class="bg-image"></div>
<div class="overlay"></div>

<!-- HEADER -->
<header>
    <a href="index.php" class="back-btn">⬅ Home</a>

    <div class="logo">
        <img src="images/h1logo.png" alt="Logo">
    </div>
</header>

<!-- CONTENT -->
<div class="content-wrapper">

<br><br><br>

<section>
    <h1>About Krushi Saarthi</h1>
    <p>Krushi Saarthi is an online farm equipment platform designed to make agricultural equipments and tools easily accessible to farmers.<br>
    Building a strong digital bridge between farmers, technology, and fair markets across India.</p>
</section>

<section>
    <h2>Who We Are</h2>
    <p>
        Krushi Saasthi is an agriculture-focused digital platform dedicated to empowering farmers 
        with modern solutions, transparent market access, and expert guidance.
    </p>
</section>

<section>
    <div class="grid">
        <div class="card">
            <h3>🌱 Our Mission</h3>
            <p>To provide farmers with technology-driven tools and fair market access.</p>
        </div>
        <div class="card">
            <h3>🚜 Our Vision</h3>
            <p>To become India’s most trusted agricultural digital ecosystem.</p>
        </div>
        <div class="card">
            <h3>🤝 Our Values</h3>
            <p>Transparency, Innovation, Farmer First Approach.</p>
        </div>
    </div>
</section>

<section>
    <h2>Our Journey</h2>
    <p>
        Krushi Saasthi started with a vision to remove middlemen and connect farmers directly to markets.
    </p>
</section>

<section class="impact">
    <h3>10,000+</h3>
    <p>Farmers Connected & Growing Stronger</p>
</section>

<section>
    <h2>Founder & Owner</h2>
    <p><strong>Unkown</strong></p>
    <p>
        BCA Final Year Students (2023–2026 Batch) transforming agriculture through innovation.
    </p>
</section>

<section>
    <h2>What People Say</h2>
    <p>
        "They are deeply committed to farmer welfare. Their vision inspires rural entrepreneurs."
    </p>
</section>

<footer>
    © 2026 Krushi Saasthi | Empowering Indian Farmers 🌾
</footer>

</div>

</body>
</html>
```
