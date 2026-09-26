<!DOCTYPE html>
<html lang="en">
<head>
<title>AgroTech | Online Tractor Market</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}
body{
    background:#f4f7f5;
	padding-top:90px;
    color:#222;
}

/* ===== HEADER ===== */
header{
  background:white;
  padding:18px 40px;
  display:flex;
  justify-content:center;
  align-items:center;

  position:fixed;   /* makes it fixed */
  top:0;
  left:0;
  width:100%;
  z-index:1000;     /* keeps it above all content */
}

header::after{
  content:"";
  position:absolute;
  bottom:0;
  left:0;
  width:100%;
  height:3px;
  background: linear-gradient(to right, #22c55e, #facc15);
}
.logo img{
    max-width:150px;
}

/* ===== TITLE ===== */
.page-title{
    padding:45px 8% 20px;
    text-align:center;
}
.page-title h1{
    font-size:38px;
}
.page-title p{
    color:#555;
    margin-top:8px;
}

/* ===== FILTER ===== */
.filters{
    padding:20px 8%;
    display:flex;
    gap:15px;
    justify-content:center;
    flex-wrap:wrap;
}
.filters select{
    padding:10px 18px;
    border-radius:12px;
    border:1px solid #ccc;
    font-size:14px;
}

/* ===== BRAND GRID ===== */
.brand-grid{
    padding:40px 8%;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:30px;
}
.brand-card{
    background:#fff;
    border-radius:18px;
    padding:25px;
    text-align:center;
    box-shadow:0 12px 25px rgba(0,0,0,.08);
    transition:.3s;
    cursor:pointer;
}
.brand-card:hover{
    transform:translateY(-8px);
}
.brand-card img{
    width:180px;
    height:100px;
    object-fit:contain;
}
.rating{
    color:#f4b400;
    font-size:14px;
    margin-top:5px;
}

/* ===== POPUP MODAL ===== */
.modal{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    display:none;
    justify-content:center;
    align-items:center;
}
.modal-content{
    background:#fff;
    width:400px;
    padding:30px;
    border-radius:18px;
    text-align:center;
    position:relative;
    animation:fadeIn .3s ease;
}
.modal-content h3{
    margin-bottom:10px;
}
.modal-content p{
    margin:6px 0;
}
.close-btn{
    position:absolute;
    top:10px;
    right:15px;
    font-size:20px;
    cursor:pointer;
    color:red;
}
.contact-box{
    margin-top:20px;
    padding-top:15px;
    border-top:1px solid #ddd;
    font-size:14px;
}
@keyframes fadeIn{
    from{transform:scale(0.8);opacity:0;}
    to{transform:scale(1);opacity:1;}
}

/* ===== FOOTER ===== */
footer{
    position:fixed;
    bottom:0;
    width:100%;
    height:30px;
    display:flex;
    justify-content:center;
    align-items:center;
    background:green;
    color:#fff;
    font-weight:500;
}
</style>
</head>

<body>

<header>
    <div class="logo">
        <img src="images/h1logo.png">
    </div>
</header>

<section class="page-title">
    <h1>Pre-Owned Equipments</h1>
    <p>Click any tractor brand to see details</p>
</section>

<!-- BRAND GRID -->
<section class="brand-grid">

<div class="brand-card" 
     data-brand="Mahindra" 
     data-price="₹7,50,000" 
     data-hp="45 HP"
     data-rating="4.7 ⭐⭐⭐⭐⭐">
    <img src="images/mahindralogoo.jpg">
    <h3>Mahindra</h3>
    <div class="rating">⭐⭐⭐⭐⭐ 4.7</div>
</div>

<div class="brand-card" 
     data-brand="John Deere" 
     data-price="₹12,00,000" 
     data-hp="70 HP"
     data-rating="4.6 ⭐⭐⭐⭐⭐">
    <img src="images/johndeerelogo.jpg">
    <h3>John Deere</h3>
    <div class="rating">⭐⭐⭐⭐⭐ 4.6</div>
</div>

<div class="brand-card" 
     data-brand="Kubota" 
     data-price="₹6,50,000" 
     data-hp="35 HP"
     data-rating="4.4 ⭐⭐⭐⭐">
    <img src="images/kubotalogo.png">
    <h3>Kubota</h3>
    <div class="rating">⭐⭐⭐⭐ 4.4</div>
</div>

<div class="brand-card" 
     data-brand="Kubota" 
     data-price="₹6,50,000" 
     data-hp="35 HP"
     data-rating="4.4 ⭐⭐⭐⭐">
    <img src="images/kubotalogo.png">
    <h3>Kubota</h3>
    <div class="rating">⭐⭐⭐⭐ 4.4</div>
</div>

<div class="brand-card" 
     data-brand="Kubota" 
     data-price="₹6,50,000" 
     data-hp="35 HP"
     data-rating="4.4 ⭐⭐⭐⭐">
    <img src="images/kubotalogo.png">
    <h3>Kubota</h3>
    <div class="rating">⭐⭐⭐⭐ 4.4</div>
</div>

<div class="brand-card" 
     data-brand="Kubota" 
     data-price="₹6,50,000" 
     data-hp="35 HP"
     data-rating="4.4 ⭐⭐⭐⭐">
    <img src="images/kubotalogo.png">
    <h3>Kubota</h3>
    <div class="rating">⭐⭐⭐⭐ 4.4</div>
</div>

<div class="brand-card" 
     data-brand="Kubota" 
     data-price="₹6,50,000" 
     data-hp="35 HP"
     data-rating="4.4 ⭐⭐⭐⭐">
    <img src="images/kubotalogo.png">
    <h3>Kubota</h3>
    <div class="rating">⭐⭐⭐⭐ 4.4</div>
</div>

<div class="brand-card" 
     data-brand="Kubota" 
     data-price="₹6,50,000" 
     data-hp="35 HP"
     data-rating="4.4 ⭐⭐⭐⭐">
    <img src="images/kubotalogo.png">
    <h3>Kubota</h3>
    <div class="rating">⭐⭐⭐⭐ 4.4</div>
</div>

</section>

<!-- POPUP -->
<div class="modal" id="tractorModal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">✖</span>
        <h3 id="modalBrand"></h3>
        <p><strong>Price:</strong> <span id="modalPrice"></span></p>
        <p><strong>Engine Power:</strong> <span id="modalHP"></span></p>
        <p><strong>Rating:</strong> <span id="modalRating"></span></p>

        <div class="contact-box">
            <h4>Contact Information</h4>
            <p>📞 +91 7795104355</p>
            <p>📧 Krushi Saarthi@gmail.com</p>
            <p>🏢 KrushiSaarthi Market, India</p>
        </div>
    </div>
</div>

<footer>
© 2026 AgroTech Market | India’s Tractor Online Marketplace
</footer>

<script>

const cards = document.querySelectorAll(".brand-card");
const modal = document.getElementById("tractorModal");

cards.forEach(card=>{
    card.addEventListener("click", ()=>{
        document.getElementById("modalBrand").innerText = card.dataset.brand;
        document.getElementById("modalPrice").innerText = card.dataset.price;
        document.getElementById("modalHP").innerText = card.dataset.hp;
        document.getElementById("modalRating").innerText = card.dataset.rating;
        modal.style.display = "flex";
    });
});

function closeModal(){
    modal.style.display = "none";
}

window.onclick = function(event){
    if(event.target == modal){
        modal.style.display = "none";
    }
}

</script>

</body>
</html>
