<?php
// Démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Barre de navigation principale -->
<nav class="navbar">

    <!-- Logo du site -->
    <img src="/assets/images/logo.png" alt="Logo" style="width:120px;">

    <!-- Menu de navigation -->
    <ul class="menu">

        <!-- Lien vers la page d'accueil -->
        <li><a href="/index.php">Accueil</a></li>

        <!-- Lien vers la liste des livres -->
        <li><a href="/livres/liste.php">Livres</a></li>
       

        <?php if(isset($_SESSION['user'])) { ?>

            <!-- Afficher le lien Admin uniquement pour les administrateurs -->
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                <li><a href="/admin/dashboard.php">Admin</a></li>
            <?php } ?>

            <!-- Lien vers le profil de l'utilisateur -->
            <li><a href="/profile.php">Profil</a></li>

            <!-- Bouton de déconnexion -->
            <li><a class="logout-btn" href="/logout.php">Déconnexion</a></li>

        <?php } else { ?>

            <!-- Liens affichés aux visiteurs non connectés -->
            <li><a class="login-btn" href="/login.php">Login</a></li>
            <li><a class="login-btn" href="/register.php">Register</a></li>

        <?php } ?>

    </ul>

    <?php if(!isset($show_search) || $show_search) { ?>

        <!-- Formulaire de recherche de livres -->
        <form action="/livres/liste.php" method="GET">

            <div class="search-box">

                <!-- Champ de saisie de la recherche -->
                <input
                    type="text"
                    name="search"
                    placeholder="Rechercher un livre..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                >

                <!-- Bouton de recherche -->
                <button type="submit" style="background:none; border:none; cursor:pointer;">
                    <i class="fas fa-search"></i>
                </button>

            </div>

        </form>

    <?php } ?>

</nav>