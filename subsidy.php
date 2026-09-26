<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>AgroTech | Subsidy & Tractor Loans</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

/* ===== BODY WITH RESPONSIVE BACKGROUND ===== */
body{
    color:#222;

    background-image: url('images/sub.jpg');
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    background-attachment: fixed;

    min-height:100vh;
	

    padding-top:120px;
    padding-bottom:80px;
    transition: padding 0.3s;
}

/* Overlay for better readability */
body::before{
    content:"";
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.4);
    z-index:-1;
}

/* Responsive background fixes */
@media (max-width:1024px){
    body{
        background-attachment: scroll;
    }
}

@media (max-width:768px){
    body{
        background-position:center;
        background-attachment: scroll;
    }
}

@media (max-width:480px){
    body{
        background-position: top;
    }
}

/* ===== HEADER ===== */
header{
    position: fixed;
    top:0;
    left:0;
    width:100%;
    z-index:1000;
    padding:18px 8%;
    transition: all 0.3s ease;
}

.header-container{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:15px;
}

.logo-img{
    width:250px;
    height:100px;
    object-fit:contain;
    transition: all 0.3s ease;
}

/* ===== HERO ===== */
.hero{
    text-align:center;
    padding:60px 20px;
    background: rgba(255,255,255,0.85);
    border-radius:12px;
    margin:20px auto;
    max-width:900px;
}

.hero h1{
    font-size:36px;
    color:#065f46;
}

.hero p{
    margin-top:15px;
    font-size:18px;
}

/* ===== SECTION ===== */
.section{
    padding:60px 8%;
    border-radius:12px;
    margin:20px auto;
    max-width:1200px;
}

.section h2{
    text-align:center;
    margin-bottom:40px;
    font-size:28px;
    color:white;
}

/* ===== CARDS ===== */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:25px;
}

.card{
    background:#ffffff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
    transition:0.3s;
    border:1px solid #facc15;
}

.card:hover{
    transform:translateY(-5px);
}

.card h3{
    margin-bottom:10px;
    color:#065f46;
}

.card p{
    font-size:14px;
    margin-bottom:15px;
}

/* ===== BUTTON ===== */
.apply-btn{
    display:inline-block;
    margin-top:10px;
    padding:8px 15px;
    background:#facc15;
    color:#065f46;
    text-decoration:none;
    border-radius:6px;
    font-weight:600;
}

.apply-btn:hover{
    background:#eab308;
}

/* ===== CALCULATOR ===== */
.calculator{
    max-width:500px;
    margin:0 auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
    border:2px solid #facc15;
}

.calculator input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
}

.calculator button{
    width:100%;
    padding:12px;
    background:#facc15;
    border:none;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
}

.calculator button:hover{
    background:#eab308;
}

.result{
    margin-top:15px;
    font-weight:600;
    color:#065f46;
}

/* ===== TABLE ===== */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

table th, table td{
    padding:15px;
    text-align:left;
    border-bottom:1px solid #ddd;
}

table th{
    background:#bbf7d0;
    color:#065f46;
}

/* ===== FOOTER ===== */
footer{
    position: fixed;
    bottom:0;
    left:0;
    width:100%;
    height:40px;
    padding:10px;
    text-align:center;
    background: rgba(255,255,255,0.2);
    color:black;
    backdrop-filter: blur(10px);
    box-shadow:0 -4px 10px rgba(0,0,0,0.1);
    z-index:1000;
    transition: all 0.3s ease;
}
</style>
</head>

<body>

<!-- HEADER -->
<header id="header">
    <div class="header-container">
        <img src="images/h1logo.png" alt="AgroTech Logo" class="logo-img" id="logo">
    </div>
</header>

<!-- HERO -->
<section class="hero">
    <h1>Government Subsidy & Easy Tractor Loans</h1>
    <p>Calculate your subsidy and explore bank loan options easily.</p>
</section>

<!-- CALCULATOR -->
<section class="section">
    <h2>Subsidy Calculator</h2>

    <div class="calculator">
        <label>Tractor Price (₹)</label>
        <input type="number" id="price" placeholder="Enter Tractor Price">

        <label>Subsidy Percentage (%)</label>
        <input type="number" id="percent" placeholder="Enter Subsidy %">

        <button onclick="calculateSubsidy()">Calculate Subsidy</button>

        <div class="result" id="result"></div>
    </div>
</section>

<!-- SCHEMES -->
<section class="section">
    <h2>Government Subsidy Schemes</h2>

    <div class="grid">
        <div class="card">
            <h3>PM-KISAN</h3>
            <p>20% to 50% subsidy on tractor purchase.</p>
            <a href="https://pmkisan.gov.in/" class="apply-btn">Apply Now</a>
        </div>

        <div class="card">
            <h3>SMAM Scheme</h3>
            <p>Financial assistance for farm equipment.</p>
            <a href="https://www.myscheme.gov.in/schemes/smam" class="apply-btn">Apply Now</a>
        </div>

        <div class="card">
            <h3>KCC Loan</h3>
            <p>Support for agricultural machinery loans.</p>
            <a href="https://www.myscheme.gov.in/schemes/kcc" class="apply-btn">Apply Now</a>
        </div>
    </div>
</section>

<!-- BANK TABLE -->
<section class="section">
    <h2>Partner Banks & Loan Details</h2>

    <table>
        <tr>
            <th>Bank Name</th>
            <th>Interest Rate</th>
            <th>Loan Tenure</th>
            <th>Contact</th>
        </tr>
        <tr>
            <td>State Bank of India</td>
            <td>7% - 9%</td>
            <td>Up to 7 Years</td>
            <td>1800-123-4567</td>
        </tr>
        <tr>
            <td>Bank of Baroda</td>
            <td>8% - 10%</td>
            <td>Up to 5 Years</td>
            <td>1800-258-4455</td>
        </tr>
        <tr>
            <td>Punjab National Bank</td>
            <td>7.5% - 9%</td>
            <td>Up to 6 Years</td>
            <td>1800-180-2222</td>
        </tr>
    </table>
</section>

<footer id="footer">
    © 2026 AgroTech | Supporting Indian Farmers
</footer>

<script>
function calculateSubsidy(){
    var price = document.getElementById("price").value;
    var percent = document.getElementById("percent").value;

    if(price && percent){
        var subsidy = (price * percent) / 100;
        var finalAmount = price - subsidy;

        document.getElementById("result").innerHTML =
        "Subsidy Amount: ₹" + subsidy.toFixed(2) +
        "<br>Final Amount: ₹" + finalAmount.toFixed(2);
    } else {
        document.getElementById("result").innerHTML = "Please enter valid details.";
    }
}

/* Scroll effect */
window.addEventListener('scroll', function(){
    var header = document.getElementById('header');
    var logo = document.getElementById('logo');
    var footer = document.getElementById('footer');

    if(window.scrollY > 50){
        header.style.padding = "8px 8%";
        logo.style.height = "60px";
        footer.style.padding = "10px";
        document.body.style.paddingTop = "80px";
        document.body.style.paddingBottom = "50px";
    } else {
        header.style.padding = "18px 8%";
        logo.style.height = "100px";
        footer.style.padding = "20px";
        document.body.style.paddingTop = "120px";
        document.body.style.paddingBottom = "80px";
    }
});
</script>

</body>
</html>