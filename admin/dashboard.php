<?php
session_start();
require "../config/config.php";

// ── حماية: admin فقط ──
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ── Stats ──
$total_livres      = $pdo->query("SELECT COUNT(*) FROM livres")->fetchColumn();
$total_categories  = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$total_users       = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'user'")->fetchColumn();
$total_downloads   = $pdo->query("SELECT COUNT(*) FROM telechargements")->fetchColumn();

// ── Livres récents ──
$recents = $pdo->query("
    SELECT l.*, c.nom AS categorie
    FROM livres l
    LEFT JOIN categories c ON l.id_categorie = c.id
    ORDER BY l.date_ajout DESC
    LIMIT 8
")->fetchAll();

// ── Top catégories ──
$top_cats = $pdo->query("
    SELECT c.nom, COUNT(l.id) AS nb
    FROM categories c
    LEFT JOIN livres l ON l.id_categorie = c.id
    GROUP BY c.id, c.nom
    ORDER BY nb DESC
")->fetchAll();

$active_page = 'dashboard';

// helper badge
function badge_cat(string $nom): string {
    $map = [
        'aventure'     => 'badge-aventure',
        'informatique' => 'badge-informatique',
        "d'affaires"   => 'badge-affaires',
        'histoire'     => 'badge-histoire',
        'architecture' => 'badge-architecture',
        'mode'         => 'badge-mode',
    ];
    $key = strtolower(trim($nom));
    $cls = $map[$key] ?? 'badge-default';
    return "<span class=\"badge $cls\">" . htmlspecialchars($nom) . "</span>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Tableau de bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include '../admin/admin_sidebar.php'; ?>

<div class="main">
    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-title">Tableau de bord</div>
        <div class="topbar-right">
            <a href="livres.php?action=ajouter" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un livre
            </a>
            <div class="avatar"><?= strtoupper(substr($_SESSION['prenom'] ?? 'A', 0, 1)) ?></div>
        </div>
    </div>

    <div class="content">

        <!-- STATS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-book"></i></div>
                <div class="stat-label">Total livres</div>
                <div class="stat-value"><?= $total_livres ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teal"><i class="fas fa-folder"></i></div>
                <div class="stat-label">Catégories</div>
                <div class="stat-value"><?= $total_categories ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon amber"><i class="fas fa-users"></i></div>
                <div class="stat-label">Utilisateurs</div>
                <div class="stat-value"><?= $total_users ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon coral"><i class="fas fa-download"></i></div>
                <div class="stat-label">Téléchargements</div>
                <div class="stat-value"><?= $total_downloads ?></div>
            </div>
        </div>

        <div class="two-col">

            <!-- LIVRES RÉCENTS -->
            <div class="table-card">
                <div class="table-card-header">
                    <h3>Derniers livres ajoutés</h3>
                    <a href="livres.php" class="btn btn-secondary" style="font-size:12px;padding:6px 12px">Voir tout</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Couv.</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Catégorie</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($recents as $l): ?>
                        <tr>
                            <td>
                                <?php if ($l['image']): ?>
                                    <img src="../uploads/images/<?= htmlspecialchars($l['image']) ?>" class="book-img" alt="">
                                <?php else: ?>
                                    <div class="book-img-placeholder"><i class="fas fa-image"></i></div>
                                <?php endif; ?>
                            </td>
                            <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                <?= htmlspecialchars($l['titre']) ?>
                            </td>
                            <td style="color:var(--muted);font-size:12px"><?= htmlspecialchars(trim($l['auteur'])) ?></td>
                            <td><?= badge_cat($l['categorie'] ?? '—') ?></td>
                            <td style="color:var(--muted);font-size:12px;white-space:nowrap">
                                <?= date('d/m/Y', strtotime($l['date_ajout'])) ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="livres.php?action=modifier&id=<?= $l['id'] ?>" class="btn-icon" title="Modifier">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="livres.php?action=supprimer&id=<?= $l['id'] ?>"
                                       class="btn-icon del"
                                       onclick="return confirm('Supprimer ce livre ?')"
                                       title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- TOP CATÉGORIES -->
            <div>
                <div class="table-card">
                    <div class="table-card-header">
                        <h3>Livres par catégorie</h3>
                    </div>
                    <table>
                        <thead>
                            <tr><th>Catégorie</th><th style="text-align:right">Livres</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($top_cats as $c): ?>
                            <tr>
                                <td><?= badge_cat($c['nom']) ?></td>
                                <td style="text-align:right;font-weight:600"><?= $c['nb'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>