<?php
session_start();
require "../config/config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$message = '';
$error   = '';

// ─── SUPPRIMER ───
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $nb = $pdo->prepare("SELECT COUNT(*) FROM livres WHERE id_categorie = ?");
    $nb->execute([$id]);
    if ($nb->fetchColumn() > 0) {
        $error = "Impossible de supprimer : des livres utilisent cette catégorie.";
    } else {
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
        $message = "Catégorie supprimée.";
    }
}

// ─── AJOUTER ───
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $nom = trim($_POST['nom'] ?? '');
    if (!$nom) {
        $error = "Le nom est obligatoire.";
    } else {
        $exists = $pdo->prepare("SELECT id FROM categories WHERE nom = ?");
        $exists->execute([$nom]);
        if ($exists->fetch()) {
            $error = "Cette catégorie existe déjà.";
        } else {
            $pdo->prepare("INSERT INTO categories (nom) VALUES (?)")->execute([$nom]);
            $message = "Catégorie « $nom » ajoutée.";
        }
    }
}

// ─── MODIFIER ───
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'modifier') {
    $id  = (int)$_POST['id'];
    $nom = trim($_POST['nom'] ?? '');
    if (!$nom) {
        $error = "Le nom est obligatoire.";
    } else {
        $pdo->prepare("UPDATE categories SET nom = ? WHERE id = ?")->execute([$nom, $id]);
        $message = "Catégorie modifiée.";
    }
}

// ─── DATA ───
$categories = $pdo->query("
    SELECT c.*, COUNT(l.id) AS nb_livres
    FROM categories c
    LEFT JOIN livres l ON l.id_categorie = c.id
    GROUP BY c.id
    ORDER BY c.nom
")->fetchAll();

$edit_id = isset($_GET['action']) && $_GET['action'] === 'modifier' ? (int)$_GET['id'] : 0;

$active_page = 'categories';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Catégories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include '../admin/admin_sidebar.php'; ?>

<div class="main">
    <div class="topbar">
        <div class="topbar-title">Catégories</div>
        <div class="avatar"><?= strtoupper(substr($_SESSION['prenom'] ?? 'A', 0, 1)) ?></div>
    </div>

    <div class="content">

        <?php if ($message): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="two-col">

            <!-- TABLE -->
            <div class="table-card">
                <div class="table-card-header">
                    <h3>Toutes les catégories (<?= count($categories) ?>)</h3>
                </div>
                <table>
                    <thead>
                        <tr><th>#</th><th>Nom</th><th style="text-align:right">Livres</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $c): ?>
                        <tr style="<?= $edit_id == $c['id'] ? 'background:var(--purple-light)' : '' ?>">
                            <td style="color:var(--muted);font-size:12px"><?= $c['id'] ?></td>
                            <td style="font-weight:500"><?= htmlspecialchars($c['nom']) ?></td>
                            <td style="text-align:right">
                                <a href="../admin/livres.php?cat=<?= $c['id'] ?>" style="color:var(--purple);font-size:13px;text-decoration:none">
                                    <?= $c['nb_livres'] ?> livre(s)
                                </a>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="?action=modifier&id=<?= $c['id'] ?>" class="btn-icon" title="Modifier">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="?action=supprimer&id=<?= $c['id'] ?>"
                                       class="btn-icon del"
                                       onclick="return confirm('Supprimer la catégorie « <?= htmlspecialchars(addslashes($c['nom'])) ?> » ?')"
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

            <!-- FORMULAIRE -->
            <div>
                <div class="form-card">
                    <?php
                    $edit_cat = null;
                    if ($edit_id) {
                        foreach ($categories as $c) {
                            if ($c['id'] == $edit_id) { $edit_cat = $c; break; }
                        }
                    }
                    ?>
                    <h3 style="font-size:14px;font-weight:600;margin-bottom:1rem">
                        <?= $edit_cat ? 'Modifier la catégorie' : 'Nouvelle catégorie' ?>
                    </h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="<?= $edit_cat ? 'modifier' : 'ajouter' ?>">
                        <?php if ($edit_cat): ?>
                            <input type="hidden" name="id" value="<?= $edit_cat['id'] ?>">
                        <?php endif; ?>
                        <div class="form-group" style="margin-bottom:1rem">
                            <label>Nom de la catégorie *</label>
                            <input type="text" name="nom" required
                                   value="<?= htmlspecialchars($edit_cat['nom'] ?? '') ?>"
                                   placeholder="Ex: Science-fiction">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                <?= $edit_cat ? 'Modifier' : 'Ajouter' ?>
                            </button>
                            <?php if ($edit_cat): ?>
                                <a href="categories.php" class="btn btn-secondary">Annuler</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>