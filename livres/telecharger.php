<?php
session_start();
require "../config/config.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id_livre = (int)($_GET['id'] ?? 0);
if (!$id_livre) {
    header("Location: pages/liste.php");
    exit();
}

// Récupérer le livre
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
$stmt->execute([$id_livre]);
$livre = $stmt->fetch();

if (!$livre || !$livre['fichier_pdf']) {
    die("Livre introuvable.");
}

// Enregistrer le téléchargement
$pdo->prepare("
    INSERT INTO telechargements (id_utilisateur, id_livre)
    VALUES (?, ?)
")->execute([$_SESSION['id'], $id_livre]);

// Télécharger le fichier
$file = "../uploads/pdf/" . $livre['fichier_pdf'];
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $livre['fichier_pdf'] . '"');
header('Content-Length: ' . filesize($file));
readfile($file);
exit();
?>