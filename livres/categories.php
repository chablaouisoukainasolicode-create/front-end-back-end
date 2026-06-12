<?php
require "../config/config.php"; // Inclure la configuration de la base de données

// Démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupérer toutes les catégories depuis la base de données
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <!-- Titre de la page -->
    <title>Catégories</title>

    <!-- Feuille de style principale -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Bibliothèque d'icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<!-- Inclusion de la barre de navigation -->
<?php include "../includes/navbar.php"; ?>

<section class="categories-page">

    <!-- Titre principal de la page -->
    <div class="title">
        <h2>Toutes les Catégories</h2>
    </div>

    <!-- Conteneur affichant les catégories sous forme de cartes -->
    <div class="cat-grid">

        <?php foreach($categories as $cat): ?>

            <!-- Carte représentant une catégorie -->
            <div class="cat-card">

                <!-- Lien vers la liste des livres de la catégorie sélectionnée -->
                <a href="liste.php?categorie=<?= (int)$cat['id'] ?>" style="text-decoration:none; color:inherit;">

                    <!-- Icône de dossier -->
                    <i class="fas fa-folder"></i>

                    <!-- Nom de la catégorie -->
                    <p><?= htmlspecialchars($cat['nom']) ?></p>

                </a>

            </div>

        <?php endforeach; ?>

    </div>

</section>

<!-- Inclusion du pied de page -->
<?php include "../includes/footer.php"; ?>

</body>
</html>