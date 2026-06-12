<?php
require "../config/config.php"; // Inclure la configuration de la base de données

// Récupérer l'identifiant de la catégorie depuis l'URL
$id = $_GET['id'] ?? 0;

// Préparer et exécuter la requête pour récupérer les informations de la catégorie
$stmtCat = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmtCat->execute([$id]);

// Récupérer les données de la catégorie
$categorie = $stmtCat->fetch();

// Préparer et exécuter la requête pour récupérer les livres appartenant à cette catégorie
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id_categorie = ?");
$stmt->execute([$id]);

// Récupérer tous les livres trouvés
$livres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <!-- Afficher le nom de la catégorie dans le titre de la page -->
    <title><?= htmlspecialchars($categorie['nom'] ?? 'Catégorie') ?></title>
</head>
<body>

<!-- Afficher le nom de la catégorie -->
<h1><?= htmlspecialchars($categorie['nom'] ?? 'Catégorie') ?></h1>

<?php if($livres): ?>

    <!-- Parcourir tous les livres de la catégorie -->
    <?php foreach($livres as $livre): ?>

        <!-- Bloc d'affichage d'un livre -->
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:15px;">

            <!-- Titre du livre -->
            <h3><?= htmlspecialchars($livre['titre']) ?></h3>

            <!-- Auteur du livre -->
            <p><b>Auteur :</b> <?= htmlspecialchars($livre['auteur']) ?></p>

            <!-- Description du livre -->
            <p><?= htmlspecialchars($livre['description']) ?></p>

            <!-- Image de couverture du livre -->
            <img src="../uploads/images/<?= htmlspecialchars($livre['image']) ?>" width="150">

            <br><br>

            <!-- Lien pour lire le PDF dans un nouvel onglet -->
            <a href="/livres/lire.php?id=<?= (int)$livre['id'] ?>" target="_blank">
                Lire PDF
            </a>

            |

            <!-- Lien pour télécharger le fichier PDF -->
            <a href="/livres/telecharger.php?id=<?= (int)$livre['id'] ?>">
                Télécharger
            </a>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <!-- Message affiché lorsqu'aucun livre n'est disponible -->
    <p>Aucun livre dans cette catégorie.</p>

<?php endif; ?>

</body>
</html>