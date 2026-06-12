<?php
// Démarrer la session
session_start();

// Supprimer toutes les variables de session
session_unset();

// Détruire complètement la session
session_destroy();

// Rediriger vers la page de connexion
header("Location: login.php");
exit();
?>