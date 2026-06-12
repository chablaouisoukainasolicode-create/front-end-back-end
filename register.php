<?php

session_start();

require "config/config.php";

// Vérifier si le formulaire est soumis
if(isset($_POST['register'])) {

    // Récupérer et nettoyer les données du formulaire
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['mot_de_passe']), PASSWORD_DEFAULT); // Chiffrer le mot de passe

    // Vérifier si l'email existe déjà dans la base de données
    $check = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $check->execute([$email]);

    if($check->rowCount() > 0) {

        // Email déjà utilisé
        $erreur = "Cet email existe déjà";

    } else {

        // Insérer le nouvel utilisateur dans la base de données
        $sql = "INSERT INTO utilisateurs(nom, prenom, email, mot_de_passe)
                VALUES(?,?,?,?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $nom,
            $prenom,
            $email,
            $password
        ]);

        // Sauvegarder le nom dans la session
        $_SESSION['user'] = $nom;

        // Rediriger vers la page de connexion
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="assets/css/form.css">
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>

        <!-- Formulaire d'inscription -->
        <form method="post">

            <!-- Champ Nom -->
            <div class="input">
                <label>Nom</label>
                <input type="text" name="nom" placeholder="Entrez votre nom" required><br><br>
            </div>

            <!-- Champ Prénom -->
            <div class="input">
                <label>Prénom</label>
                <input type="text" name="prenom" placeholder="Entrez votre prénom" required><br><br>
            </div>

            <!-- Champ Email -->
            <div class="input">
                <label>Email</label>
                <input type="email" name="email" placeholder="Entrez votre email" required><br><br>
            </div>

            <!-- Champ Mot de passe -->
            <div class="input">
                <label>Mot de passe</label>
                <input type="password" name="mot_de_passe" placeholder="Entrez votre mot de passe" required><br><br>
            </div>

            <!-- Afficher le message d'erreur si email déjà utilisé -->
            <?php if(isset($erreur)) { ?>
                <p class="error"><?php echo $erreur; ?></p>
            <?php } ?>

            <!-- Bouton de soumission -->
            <button class="button" type="submit" name="register">S'inscrire</button><br><br>

            <!-- Lien vers la page de connexion -->
            <p class="text">Vous avez déjà un compte ?
                <a href="login.php">Connectez-vous</a>
            </p>

        </form>
    </div>
</body>
</html>