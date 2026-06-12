<?php
// Connexion à la base de données
require "config/config.php";

// Initialiser les variables
$search = '';
$livres = [];

// Vérifier si une recherche est effectuée
if (isset($_GET['search']) && !empty($_GET['search'])) {

    // Récupérer et nettoyer le mot recherché
    $search = trim($_GET['search']);

    // Préparer le mot clé pour la recherche LIKE
    $like = '%' . $search . '%';

    // Rechercher les livres par titre ou par auteur
    $stmt = $pdo->prepare("
        SELECT livres.*, categories.nom AS categorie
        FROM livres
        LEFT JOIN categories ON livres.id_categorie = categories.id
        WHERE livres.titre LIKE ?
        OR livres.auteur LIKE ?
    ");
    $stmt->execute([$like, $like]);

    // Récupérer tous les résultats
    $livres = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche</title>
    <!-- Feuille de style principale -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<!-- Inclusion de la barre de navigation -->
<?php include "includes/navbar.php"; ?>

<!-- Afficher le mot recherché -->
<div style="text-align:center; margin:30px;">
    <h2> Résultats pour : "<?= htmlspecialchars($search) ?>"</h2>
    <!-- Lien retour vers l'accueil -->
    <a href="index.php">← Retour à l'accueil</a>
</div>

<!-- Conteneur des résultats -->
<div class="books-container">

    <!-- Afficher un message si aucun livre trouvé -->
    <?php if(empty($livres)): ?>
        <p style="text-align:center; margin:40px;">Aucun livre trouvé 😕</p>
    <?php else: ?>

        <!-- Afficher chaque livre trouvé -->
        <?php foreach($livres as $l): ?>
            <div class="book-card">

                <!-- Image de couverture -->
                <img src="uploads/images/<?= htmlspecialchars($l['image']) ?>">

                <!-- Titre du livre -->
                <h3><?= htmlspecialchars($l['titre']) ?></h3>

                <!-- Auteur du livre -->
                <p><b>Auteur :</b> <?= htmlspecialchars($l['auteur']) ?></p>

                <!-- Catégorie du livre -->
                <p><b>Catégorie :</b> <?= htmlspecialchars($l['categorie']) ?></p>

                <!-- Bouton vers la page de détails -->
                <a href="livres/details.php?id=<?= (int)$l['id'] ?>" class="btn-detail">
                    Voir détails
                </a>

                <div class="actions">
                    <!-- Bouton pour lire le livre en ligne -->
                    <a href="uploads/pdf/<?= htmlspecialchars($l['fichier_pdf']) ?>" target="_blank" class="btn-read">
                        Lire
                    </a>
                    <!-- Bouton pour télécharger le livre -->
                    <a href="uploads/pdf/<?= htmlspecialchars($l['fichier_pdf']) ?>" download class="btn-download">
                        Télécharger
                    </a>
                </div>

            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>

<!-- Inclusion du footer -->
<?php include "includes/footer.php"; ?>

</body>
</html>