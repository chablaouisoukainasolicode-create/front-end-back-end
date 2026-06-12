<?php
require "../config/config.php"; // Connexion à la base de données

/* ─────────────────────────────
   VÉRIFICATION DE L'ID
───────────────────────────── */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID manquant"); // Arrêter si aucun ID fourni
}

$id = (int) $_GET['id']; // Sécuriser l'ID


/* ─────────────────────────────
   RÉCUPÉRER LE LIVRE
───────────────────────────── */
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch();

// Vérifier si le livre existe
if (!$livre) {
    die("Livre introuvable");
}


/* ─────────────────────────────
   RÉCUPÉRER LES CATÉGORIES
───────────────────────────── */
$cats = $pdo->query("SELECT * FROM categories")->fetchAll();


/* ─────────────────────────────
   TRAITEMENT DE LA MODIFICATION
───────────────────────────── */
if (isset($_POST['update'])) {

    // Récupération des données du formulaire
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $description = $_POST['description'];
    $id_categorie = $_POST['id_categorie'];

    // Requête de mise à jour
    $stmt = $pdo->prepare("
        UPDATE livres 
        SET titre = ?, auteur = ?, description = ?, id_categorie = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $titre,
        $auteur,
        $description,
        $id_categorie,
        $id
    ]);

    // Redirection après modification
    header("Location: liste.php");
    exit;
}
?>