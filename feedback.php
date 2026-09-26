<?php
include "db/db.php";

$success = false;

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $type = $_POST['type'];
    $opinion = $_POST['opinion'];
    $rating = $_POST['rating'];

    $query = "INSERT INTO feedback(name,email,type,opinion,rating)
              VALUES('$name','$email','$type','$opinion','$rating')";

    $result = mysqli_query($conn,$query);

    if($result){
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback | Krushi Saarthi</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

/* BODY */
body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background: url('images/feed.jpg') no-repeat center center fixed;
    background-size: cover;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

body::before {
    content:"";
    position: absolute;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.45);
    z-index:0;
}

/* FEEDBACK CARD */
.feedback-box{
    position: relative;
    z-index:1;
    width:90%;
    max-width:350px;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(15px);
    border:1px solid rgba(255,215,0,0.25);
    padding:30px 45px;
    border-radius:15px;
    box-shadow:0 15px 40px rgba(0,0,0,0.6);
    color:#f1f5f9;
    transition: transform 0.3s ease;
}

.feedback-box:hover {
    transform: translateY(-3px);
}

/* TITLE */
.feedback-box h2{
    text-align:center;
    margin-bottom:15px;
    color:#facc15;
    font-size:22px;
}

/* INPUT GROUP */
.input-group{
    margin-bottom:12px;
}

.input-group label{
    font-size:13px;
    font-weight:600;
    color:#facc15;
    margin-bottom:4px;
    display:block;
}

/* INPUTS & TEXTAREA */
.input-group input,
.input-group textarea,
.input-group select{
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background: rgba(255,255,255,0.18);
    color:white;
    font-size:13px;
    outline:none;
    transition:0.3s;
}

::placeholder{
    color:white;
}

.input-group input:focus,
.input-group textarea:focus,
.input-group select:focus{
    background:rgba(255,255,255,0.25);
    box-shadow:0 0 0 2px #22c55e;
}

textarea{
    resize:none;
    height:70px;
}

.input-group select{
    appearance:none;
    background-image:url("data:image/svg+xml;utf8,<svg fill='white' height='16' viewBox='0 0 20 20' width='16'><path d='M5 7l5 5 5-5z'/></svg>");
    background-repeat:no-repeat;
    background-position:right 10px center;
    background-size:12px;
}

.input-group select option{
    background:#065f46;
    color:#ffffff;
}

/* STAR RATING */
.star-rating{
    display:flex;
    justify-content:center;
    gap:8px;
    font-size:22px;
    margin-top:5px;
    cursor:pointer;
}

.star{
    color:#9ca3af;
    transition:0.3s;
}

.star:hover,
.star.active{
    color:#fde047;
    transform:scale(1.2);
}

/* BUTTON */
.submit-btn{
    width:100%;
    padding:10px;
    border:none;
    border-radius:20px;
    background:linear-gradient(135deg,#facc15,#eab308);
    color:#022c22;
    font-weight:700;
    font-size:14px;
    cursor:pointer;
    margin-top:10px;
    transition:0.3s;
    box-shadow:0 4px 12px rgba(0,0,0,0.35);
}

.submit-btn:hover{
    background:linear-gradient(135deg,#fde047,#facc15);
    transform:translateY(-2px);
    box-shadow:0 6px 18px rgba(0,0,0,0.45);
}
</style>
</head>

<body>

<div class="feedback-box">
<h2>Customer Feedback ⭐</h2>

<form method="POST">

<div class="input-group">
<label>Name</label>
<input type="text" name="name" placeholder="Enter your name" required>
</div>

<div class="input-group">
<label>Email</label>
<input type="email" name="email" placeholder="Enter your email" required>
</div>

<div class="input-group">
<label>Feedback Type</label>
<select name="type" required>
<option value="">Select</option>
<option value="Equipment">Equipment</option>
<option value="Owner">Owner</option>
</select>
</div>

<div class="input-group">
<label>Your Opinion</label>
<textarea name="opinion" placeholder="Write feedback..." required></textarea>
</div>

<div class="input-group">
<label>Rating</label>
<input type="hidden" name="rating" id="rating">
<div class="star-rating">
<span class="star">&#9733;</span>
<span class="star">&#9733;</span>
<span class="star">&#9733;</span>
<span class="star">&#9733;</span>
<span class="star">&#9733;</span>
</div>
</div>

<button type="submit" class="submit-btn" name="submit">Submit Feedback</button>
</form>
</div>

<script>
const stars = document.querySelectorAll(".star");
const ratingInput = document.getElementById("rating");

stars.forEach((star, index)=>{
    star.addEventListener("click", ()=>{
        let rating = index+1;
        ratingInput.value = rating;

        stars.forEach((s,i)=>{
            s.classList.toggle("active", i < rating);
        });
    });
});

/* SUCCESS ALERT + REDIRECT */
<?php if($success){ ?>
alert("Feedback Submitted Successfully!");
window.location.href = "index.php";
<?php } ?>
</script>

</body>
</html>ß