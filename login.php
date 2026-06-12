<?php
session_start();
require "config/config.php";

if (isset($_POST['login'])) {

    $email    = trim($_POST['email']);
    $password = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $password === $user['mot_de_passe']) {

        $_SESSION['id']     = $user['id'];
        $_SESSION['nom']    = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['email']  = $user['email'];
        $_SESSION['role']   = $user['role'];
        $_SESSION['user']   = [
            'id'            => $user['id'],
            'nom'           => $user['nom'],
            'prenom'        => $user['prenom'],
            'email'         => $user['email'],
            'role'          => $user['role'],
            'date_creation' => $user['date_creation']
        ];

        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();

    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/form.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>

    <?php if (!empty($erreur)): ?>
        <p style="color:red; font-size:13px;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="POST">

        <div class="input">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="input">
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" placeholder="Enter your password" required>
        </div>

        <button class="button" name="login">Login</button><br><br>

        <p class="text">
            Vous n'avez pas de compte ?
            <a href="register.php">Register</a>
        </p>

    </form>
</div>
</body>
</html>