<?php
require "../config/config.php";

/* Vérifier ID */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID manquant");
}

$id = (int) $_GET['id'];

/* Supprimer le livre */
$stmt = $pdo->prepare("DELETE FROM livres WHERE id = ?");
$stmt->execute([$id]);

/* Retour à la liste */
header("Location: liste.php");
exit;
?>