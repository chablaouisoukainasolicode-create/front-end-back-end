<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/style.css">
<title>Mon Profil</title>

<style>
body


{
    font-family: "poppins", sans-serif;
    background:#f8f6f3; 
      box-sizing:border-box;
}
/* NAVBAR */
.navbar{
     width: 100%;
    height: 90px;
    background: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 60px;
    border-radius:15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.navbar img{
    height: 70px;
    width:auto;
    object-fit: contain;
    border-radius:10px;
}

/* MENU */
.menu{
    display: flex;
    align-items: center;
    gap: 30px;
    list-style: none;
}

.menu li{
    list-style: none;
}

.menu li a{
    text-decoration: none;
    color: #3b1a0a;
    font-size: 16px;
    font-weight: 500;
    transition: 0.3s;
    position: relative;
}

/* HOVER */
.menu li a:hover{
    color: #efe8df;
}

.menu li a::after{
    content: '';
    position: absolute;
    left: 0;
    bottom: -5px;
    width: 0%;
    height: 2px;
    background: #efe8df;
    transition: 0.3s;
}

.menu li a:hover::after{
    width: 100%;
}

/* BUTTONS LOGIN / LOGOUT */
.login-btn,
.logout-btn{
    background:  #3b1a0a;
    color: white !important;
    padding: 10px 18px;
    border-radius: 8px;
}

.login-btn:hover,
.logout-btn:hover{
    background: #efe8df;
}

/* RESPONSIVE */
@media(max-width: 900px){

    .navbar{
        flex-direction: column;
        height: auto;
        padding: 20px;
        gap: 20px;
    }

    .menu{
        flex-wrap: wrap;
        justify-content: center;
    }

    .search-box input{
        width: 150px;
    }
}
.profile{
    width:500px;
    margin:50px auto;
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

.profile h2{
    text-align:center;
    color: #3b1a0a;
}

.profile h2{
    text-align:center;
}

.profile .info{
    margin:15px 0;
    font-size:18px;
}

.profile .btn{
    display:inline-block;
    padding:10px 20px;
    background: #3b1a0a;
    color:white;
    text-decoration:none;
    border-radius:5px;
}
.footer {
    background-color: white;
    color: #3b1a0a;
    padding: 30px 20px;
    margin-top: 50px;
    text-align: center; 
    border-radius:15px;
}

.footer-contact h3 {
    margin-bottom: 15px;
    font-size: 22px;
}

.footer-contact p {
    margin: 8px 0;
    font-size: 16px;
}

.footer-bottom {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid rgba(255,255,255,0.2);
}

.footer-bottom p {
    font-size: 14px;
    margin: 0;
}

</style>
</head>
<body>
<?php $show_search = false; ?>
<?php include "includes/navbar.php"; ?>
<div class="profile">

    <h2>Mon Profil</h2>

    <div class="info">
        <strong>Nom :</strong>
        <?= htmlspecialchars($user['nom']) ?>
        
    </div>

    <div class="info">
        <strong>Prénom :</strong>
        <?= htmlspecialchars($user['prenom']) ?>
    </div>

    <div class="info">
        <strong>Email :</strong>
        <?= htmlspecialchars($user['email']) ?>
    </div>

    <div class="info">
        <strong>Rôle :</strong>
        <?= htmlspecialchars($user['role']) ?>
    </div>

    <div class="info">
        <strong>Date de création :</strong>
        <?= htmlspecialchars($user['date_creation']) ?>
    </div>


</div>

<?php include "includes/footer.php"; ?>
</body>
</html>