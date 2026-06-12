<?php
// Démarrer la session
session_start();

// Connexion à la base de données
require __DIR__ . "/config/config.php";

/* Récupérer toutes les catégories depuis la base de données */
$categories = $pdo->query("
    SELECT *
    FROM categories
")->fetchAll();

/* Récupérer les 8 livres les plus récents avec leur catégorie */
$livres = $pdo->query("
    SELECT l.*, c.nom AS categorie
    FROM livres l
    LEFT JOIN categories c ON l.id_categorie = c.id
    LIMIT 4
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>online library</title>

    <!-- Police Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Feuille de style principale -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- Inclusion de la barre de navigation -->
<?php include 'includes/navbar.php'; ?>

<!-- Section header / bannière -->
<section class="header">

    <div class="header-content">
        <h1>Bienvenue dans votre Bibliothèque</h1>
        <p>Découvrez des milliers de livres en ligne</p>
    </div>
</section>
</section>

<!-- Section des catégories -->
<section class="categories">

    <div class="title">
        <h2>Catégories</h2>
        <!-- Lien vers toutes les catégories -->
        <a href="livres/categories.php">Voir toutes les catégories</a>
    </div>

    <div class="cat-grid">

        <!-- Afficher chaque catégorie sous forme de carte cliquable -->
        <?php foreach($categories as $c): ?>

            <div class="cat-card">
                <!-- Cliquer sur une catégorie filtre les livres -->
                <a href="livres/liste.php?categorie=<?= (int)$c['id'] ?>" style="text-decoration:none; color:inherit;">
                    <p><?= htmlspecialchars($c['nom']) ?></p>
                </a>
            </div>

        <?php endforeach; ?>

    </div>

</section>

<!-- Section des livres populaires -->
<section class="books">

    <div class="title">
        <h2>Livres populaires</h2>
        <!-- Lien vers tous les livres -->
        <a href="livres/liste.php">Voir tous les livres</a>
    </div>

    <div class="book-grid">

        <!-- Afficher chaque livre sous forme de carte -->
        <?php foreach($livres as $l): ?>

            <div class="book-card">

        <!-- Image de couverture du livre -->

        <div class="book-image">
        <?php if(!empty($l['image'])): ?>
        <img src="uploads/images/<?= htmlspecialchars($l['image']) ?>" alt="">
        <?php else: ?>

        <!-- Image par défaut si aucune image disponible -->

        <img src="https://via.placeholder.com/150x220" alt="">
        <?php endif; ?>
        </div>

        <!-- Titre du livre -->
        <h3><?= htmlspecialchars($l['titre']) ?></h3>

                <!-- Auteur du livre -->
                <p><?= htmlspecialchars($l['auteur']) ?></p>

                <!-- Catégorie du livre -->
                <p><?= htmlspecialchars($l['categorie']) ?></p>

                <!-- Boutons Lire et Télécharger -->
                <div class="book-buttons">

                    <!-- Bouton pour lire le livre en ligne -->
                    <a class="read-btn" href="livres/lire.php?id=<?= (int)$l['id'] ?>">
                        Lire
                    </a>

                    <!-- Bouton pour télécharger le livre -->
                    <a class="download-btn" href="livres/telecharger.php?id=<?= (int)$l['id'] ?>">
                        Télécharger
                    </a>

                </div>

                </div>

             <?php endforeach; ?>

           </div>

        </section>

      <!-- Inclusion du footer -->
      <?php include 'includes/footer.php'; ?>

</body>
</html>