<?php
// Connexion à la base de données
require "../config/config.php";

/* Récupérer les livres selon le filtre appliqué */

// Cas 1 : Recherche par titre ou auteur
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $stmt = $pdo->prepare("
        SELECT livres.*, categories.nom AS categorie
        FROM livres
        LEFT JOIN categories ON livres.id_categorie = categories.id
        WHERE livres.titre LIKE ?
        OR livres.auteur LIKE ?
    ");
    $stmt->execute([$search, $search]);
    $livres = $stmt->fetchAll();

// Cas 2 : Filtrer par catégorie
} elseif (isset($_GET['categorie'])) {
    $stmt = $pdo->prepare("
        SELECT livres.*, categories.nom AS categorie
        FROM livres
        LEFT JOIN categories ON livres.id_categorie = categories.id
        WHERE livres.id_categorie = ?
    ");
    $stmt->execute([(int)$_GET['categorie']]);
    $livres = $stmt->fetchAll();

// Cas 3 : Afficher tous les livres
} else {
    $livres = $pdo->query("
        SELECT livres.*, categories.nom AS categorie
        FROM livres
        LEFT JOIN categories ON livres.id_categorie = categories.id
    ")->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des livres</title>
    <!-- Feuille de style de la liste -->
    <link rel="stylesheet" href="../assets/css/liste.css">
    <!-- Icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

<!-- Inclusion de la barre de navigation -->
<?php include "../includes/navbar.php"; ?>
<div class="titre">
<h1>Explorez notre catalogue</h1>
<p>Une sélection rigoureuse d'ouvrages académiques, historiques et artistiques pour les esprits
curieux et les chercheurs passionnés.</p>
</div>
<!-- Afficher le titre selon le filtre actif -->
<?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
    <!-- Résultats de recherche -->
    <div style="text-align:center; margin:20px;">
        <h2>🔍 Résultats pour : "<?= htmlspecialchars($_GET['search']) ?>"</h2>
        <a href="liste.php">← Voir tous les livres</a>
    </div>
<?php elseif(isset($_GET['categorie'])): ?>
    <!-- Titre de la catégorie filtrée -->
    <div style="text-align:center; margin:20px;">
        <h2>📚 <?= htmlspecialchars($livres[0]['categorie'] ?? 'Catégorie') ?></h2>
        <a href="liste.php">← Voir tous les livres</a>
    </div>
<?php endif; ?>

<!-- Conteneur des livres -->
<div class="books-container">

    <!-- Message si aucun livre trouvé -->
    <?php if(empty($livres)): ?>
        <p style="text-align:center; margin:40px;">Aucun livre trouvé </p>
    <?php endif; ?>

    <!-- Afficher chaque livre sous forme de carte -->
    <?php foreach($livres as $l): ?>

        <div class="book-card">

            <!-- Image de couverture du livre -->
            <img src="../uploads/images/<?= htmlspecialchars($l['image']) ?>">

            <!-- Titre du livre -->
            <h3><?= htmlspecialchars($l['titre']) ?></h3>

            <!-- Auteur du livre -->
            <p><b>Auteur :</b> <?= htmlspecialchars($l['auteur']) ?></p>

            <!-- Catégorie du livre -->
            <p><b>Catégorie :</b> <?= htmlspecialchars($l['categorie']) ?></p>

            <!-- Bouton vers la page de détails -->
            <a href="details.php?id=<?= (int)$l['id'] ?>" class="btn-detail">
                Voir détails
            </a>

            <div class="actions">

                <!-- Bouton pour lire le livre en ligne -->
                <a href="lire.php?id=<?= (int)$l['id'] ?>" target="_blank" class="btn-read">
    Lire
</a>

                <!-- Bouton pour télécharger le livre -->
               <a href="telecharger.php?id=<?= (int)$l['id'] ?>" class="btn-download">
    Télécharger
</a>

            </div>

        </div>

    <?php endforeach; ?>

</div>

<!-- Inclusion du footer -->
<?php include "../includes/footer.php"; ?>

</body>
</html>