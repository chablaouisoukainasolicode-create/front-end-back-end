<?php
// Connexion à la base de données
require "../config/config.php";

// Initialiser la variable de recherche
$search = "";

// Vérifier si une recherche est effectuée
if(isset($_GET['search']) && !empty($_GET['search'])){
    $search = $_GET['search'];

    // Rechercher les livres par titre
    $stmt = $pdo->prepare("SELECT * FROM livres WHERE titre LIKE ?");
    $stmt->execute(["%$search%"]);
    $livres = $stmt->fetchAll();
} else {
    // Récupérer tous les livres
    $stmt = $pdo->query("SELECT * FROM livres");
    $livres = $stmt->fetchAll();
}

// Vérifier si l'identifiant du livre est fourni dans l'URL
if (!isset($_GET['id'])) {
    die("Livre introuvable");
}

// Récupérer et sécuriser l'identifiant du livre
$id = (int) $_GET['id'];

// Rechercher le livre dans la base de données
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$id]);

// Récupérer les données du livre
$livre = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si le livre existe dans la base de données
if (!$livre) {
    die("Livre introuvable");
}

// Construire le chemin vers le fichier PDF
$pdf = "../uploads/pdf/" . $livre['fichier_pdf'];

// Vérifier si le fichier PDF existe sur le serveur
if (!file_exists($pdf)) {
    die("PDF introuvable : " . htmlspecialchars($livre['fichier_pdf']));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Titre de la page = titre du livre -->
    <title><?= htmlspecialchars($livre['titre']) ?></title>
    <style>
        /* Style général de la page */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Titre du livre */
        h2 {
            text-align: center;
            padding: 15px;
        }

        /* Visionneuse PDF plein écran */
        iframe {
            width: 100%;
            height: 90vh;
            border: none;
        }
    </style>
</head>
<body>

    <!-- Afficher le titre du livre -->
    <h2><?= htmlspecialchars($livre['titre']) ?></h2>

    <!-- Afficher le PDF dans une visionneuse intégrée -->
    <iframe src="<?= $pdf ?>"></iframe>

</body>
</html>