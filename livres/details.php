<?php
require "../config/config.php"; // Inclure la configuration de la base de données

// Vérifier si l'identifiant du livre est présent dans l'URL
if(!isset($_GET['id'])){
    die("ID manquant");
}

// Récupérer l'identifiant du livre
$id = $_GET['id'];

// Préparer la requête pour récupérer les informations du livre
// ainsi que le nom de sa catégorie
$stmt = $pdo->prepare("
    SELECT livres.*, categories.nom AS categorie
    FROM livres
    LEFT JOIN categories ON livres.id_categorie = categories.id
    WHERE livres.id = ?
");

// Exécuter la requête
$stmt->execute([$id]);

// Récupérer les données du livre
$livre = $stmt->fetch();

// Vérifier si le livre existe
if(!$livre){
    die("Livre introuvable");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    
    <!-- Afficher le titre du livre dans l'onglet du navigateur -->
    <title><?= $livre['titre'] ?></title>
</head>
<body>

<!-- Afficher le titre du livre -->
<h1><?= $livre['titre'] ?></h1>

<!-- Afficher l'image de couverture du livre -->
<img src="../uploads/images/<?= $livre['image'] ?>" width="200">

<!-- Afficher le nom de l'auteur -->
<p><b>Auteur :</b> <?= $livre['auteur'] ?></p>

<!-- Afficher la catégorie du livre -->
<p><b>Catégorie :</b> <?= $livre['categorie'] ?></p>

<!-- Afficher la description du livre -->
<p><?= $livre['description'] ?></p>

<!-- Lien permettant d'ouvrir le PDF dans un nouvel onglet -->
<a href="../uploads/pdf/<?= $livre['fichier_pdf'] ?>" target="_blank">
    Lire PDF
</a>

<br><br>

<!-- Lien permettant de télécharger le fichier PDF -->
<a href="../uploads/pdf/<?= $livre['fichier_pdf'] ?>" download>
    Télécharger
</a>

</body>
</html>