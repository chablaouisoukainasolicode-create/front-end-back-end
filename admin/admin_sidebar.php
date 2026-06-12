<?php
$active_page = $active_page ?? 'dashboard';
$admin_name  = $_SESSION['prenom'] ?? 'Admin';
$admin_init  = strtoupper(substr($admin_name, 0, 1));
?>

<div class="sidebar">
    <div class="sidebar-logo">
        <img src="../assets/images/logo.png" alt="Logo" style="width:120px";>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Principal</div>
        <a href="dashboard.php" class="nav-item <?= $active_page === 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-chart-pie"></i> Tableau de bord
        </a>
        <a href="livres.php" class="nav-item <?= $active_page === 'livres' ? 'active' : '' ?>">
            <i class="fas fa-book"></i> Livres
        </a>
        <a href="categories.php" class="nav-item <?= $active_page === 'categories' ? 'active' : '' ?>">
            <i class="fas fa-folder"></i> Catégories
        </a>
        <a href="utilisateurs.php" class="nav-item <?= $active_page === 'utilisateurs' ? 'active' : '' ?>">
            <i class="fas fa-users"></i> Utilisateurs
        </a>

        <div class="nav-section" style="margin-top:1rem">Accès rapide</div>
        <a href="../index.php" class="nav-item" target="_blank">
            <i class="fas fa-external-link-alt"></i> Voir le site
        </a>
        <a href="../logout.php" class="nav-item" style="color:#E24B4A">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </nav>

    <div class="sidebar-footer">
        Connecté : <strong><?= htmlspecialchars($admin_name) ?></strong>
    </div>
</div>