<?php
session_start();
require "../config/config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$action  = $_GET['action'] ?? 'liste';
$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$error   = '';

// ─── SUPPRIMER ───────────────────────────────────────────
if ($action === 'supprimer' && $id) {
    $livre = $pdo->prepare("SELECT image, fichier_pdf FROM livres WHERE id = ?");
    $livre->execute([$id]);
    $row = $livre->fetch();

    if ($row) {
        // Supprimer les fichiers physiques
        if ($row['image']    && file_exists("../uploads/images/" . $row['image']))
            @unlink("../uploads/images/" . $row['image']);
        if ($row['fichier_pdf'] && file_exists("../uploads/pdf/" . $row['fichier_pdf']))
            @unlink("../uploads/pdf/" . $row['fichier_pdf']);

        $pdo->prepare("DELETE FROM livres WHERE id = ?")->execute([$id]);
        $message = "Livre supprimé avec succès.";
    }
    $action = 'liste';
}

// ─── AJOUTER / MODIFIER — traitement POST ────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre       = trim($_POST['titre'] ?? '');
    $auteur      = trim($_POST['auteur'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $id_categorie = (int)($_POST['id_categorie'] ?? 0);
    $edit_id     = (int)($_POST['edit_id'] ?? 0);

    if (!$titre || !$auteur) {
        $error = "Le titre et l'auteur sont obligatoires.";
    } else {
        // ── Upload image ──
        $image_name = $_POST['old_image'] ?? '';
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
                $image_name = time() . '_' . preg_replace('/\s+/', '_', $_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/images/" . $image_name);
            } else {
                $error = "Format image non supporté (jpg, png, webp, gif).";
            }
        }

        // ── Upload PDF ──
        $pdf_name = $_POST['old_pdf'] ?? '';
        if (!empty($_FILES['pdf']['name'])) {
            $ext_pdf = strtolower(pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION));
            if ($ext_pdf === 'pdf') {
                $pdf_name = time() . '_' . preg_replace('/\s+/', '_', $_FILES['pdf']['name']);
                move_uploaded_file($_FILES['pdf']['tmp_name'], "../uploads/pdf/" . $pdf_name);
            } else {
                $error = "Le fichier doit être un PDF.";
            }
        }

        if (!$error) {
            if ($edit_id) {
                // Modifier
                $stmt = $pdo->prepare("
                    UPDATE livres
                    SET titre=?, auteur=?, description=?, id_categorie=?, image=?, fichier_pdf=?
                    WHERE id=?
                ");
                $stmt->execute([$titre, $auteur, $description, $id_categorie ?: null, $image_name, $pdf_name, $edit_id]);
                $message = "Livre modifié avec succès.";
            } else {
                // Ajouter
                if (!$pdf_name) {
                    $error = "Le fichier PDF est obligatoire.";
                } else {
                    $stmt = $pdo->prepare("
                        INSERT INTO livres (titre, auteur, description, id_categorie, image, fichier_pdf)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$titre, $auteur, $description, $id_categorie ?: null, $image_name, $pdf_name]);
                    $message = "Livre ajouté avec succès.";
                }
            }
            if (!$error) $action = 'liste';
        }
    }
}

// ─── DATA ────────────────────────────────────────────────
$categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();

// Livre à modifier
$livre_edit = null;
if ($action === 'modifier' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
    $stmt->execute([$id]);
    $livre_edit = $stmt->fetch();
    if (!$livre_edit) { $action = 'liste'; }
}

// Liste avec recherche + filtre
$search    = trim($_GET['q'] ?? '');
$filtre_cat = (int)($_GET['cat'] ?? 0);
$page_num   = max(1, (int)($_GET['p'] ?? 1));
$per_page   = 10;
$offset     = ($page_num - 1) * $per_page;

$where  = "WHERE 1=1";
$params = [];
if ($search) {
    $where  .= " AND (l.titre LIKE ? OR l.auteur LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($filtre_cat) {
    $where  .= " AND l.id_categorie = ?";
    $params[] = $filtre_cat;
}

$total_rows = $pdo->prepare("SELECT COUNT(*) FROM livres l $where");
$total_rows->execute($params);
$total_rows = (int)$total_rows->fetchColumn();
$total_pages = max(1, ceil($total_rows / $per_page));

$stmt = $pdo->prepare("
    SELECT l.*, c.nom AS categorie
    FROM livres l
    LEFT JOIN categories c ON l.id_categorie = c.id
    $where
    ORDER BY l.date_ajout DESC
    LIMIT $per_page OFFSET $offset
");
$stmt->execute($params);
$livres = $stmt->fetchAll();

// ─── helpers ─────────────────────────────────────────────
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

$active_page = 'livres';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Livres</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include '../admin/admin_sidebar.php'; ?>

<div class="main">
    <div class="topbar">
        <div class="topbar-title">
            <?= $action === 'liste' ? 'Livres' : ($action === 'ajouter' ? 'Ajouter un livre' : 'Modifier le livre') ?>
        </div>
        <div class="topbar-right">
            <?php if ($action === 'liste'): ?>
                <a href="?action=ajouter" class="btn btn-primary"><i class="fas fa-plus"></i> Nouveau livre</a>
            <?php else: ?>
                <a href="livres.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
            <?php endif; ?>
            <div class="avatar"><?= strtoupper(substr($_SESSION['prenom'] ?? 'A', 0, 1)) ?></div>
        </div>
    </div>

    <div class="content">

        <?php if ($message): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>


        <?php if ($action === 'liste'): ?>
        <!-- ════════════════ LISTE ════════════════ -->

        <!-- Filtres -->
        <form method="GET" action="">
            <input type="hidden" name="action" value="liste">
            <div class="filters" style="margin-bottom:1.25rem">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" name="q" placeholder="Titre, auteur…" value="<?= htmlspecialchars($search) ?>">
                </div>
                <select name="cat" class="filter-select">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $filtre_cat == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filtrer</button>
                <?php if ($search || $filtre_cat): ?>
                    <a href="livres.php" class="btn btn-secondary"><i class="fas fa-times"></i> Réinitialiser</a>
                <?php endif; ?>
                <span style="margin-left:auto;font-size:13px;color:var(--muted)"><?= $total_rows ?> livre(s)</span>
            </div>
        </form>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Couv.</th>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Catégorie</th>
                        <th>PDF</th>
                        <th>Ajouté le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($livres)): ?>
                    <tr><td colspan="7"><div class="empty-state"><i class="fas fa-book-open"></i><p>Aucun livre trouvé.</p></div></td></tr>
                <?php else: ?>
                    <?php foreach ($livres as $l): ?>
                    <tr>
                        <td>
                            <?php if ($l['image']): ?>
                                <img src="../uploads/images/<?= htmlspecialchars($l['image']) ?>" class="book-img" alt="">
                            <?php else: ?>
                                <div class="book-img-placeholder"><i class="fas fa-image"></i></div>
                            <?php endif; ?>
                        </td>
                        <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:500">
                            <?= htmlspecialchars(trim($l['titre'])) ?>
                        </td>
                        <td style="color:var(--muted);font-size:12px"><?= htmlspecialchars(trim($l['auteur'])) ?></td>
                        <td><?= $l['categorie'] ? badge_cat($l['categorie']) : '<span class="badge badge-default">—</span>' ?></td>
                        <td>
                            <?php if ($l['fichier_pdf']): ?>
                                <a href="../uploads/pdf/<?= htmlspecialchars($l['fichier_pdf']) ?>" target="_blank"
                                   style="color:var(--success);font-size:12px;text-decoration:none">
                                   <i class="fas fa-file-pdf"></i> Voir
                                </a>
                            <?php else: ?>
                                <span style="color:var(--danger);font-size:12px"><i class="fas fa-times"></i> Manquant</span>
                            <?php endif; ?>
                        </td>
                        <td style="color:var(--muted);font-size:12px;white-space:nowrap">
                            <?= date('d/m/Y', strtotime($l['date_ajout'])) ?>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="?action=modifier&id=<?= $l['id'] ?>" class="btn-icon" title="Modifier">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="?action=supprimer&id=<?= $l['id'] ?>"
                                   class="btn-icon del"
                                   onclick="return confirm('Supprimer « <?= htmlspecialchars(addslashes($l['titre'])) ?> » ?')"
                                   title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php
                $url = "livres.php?action=liste&p=$i";
                if ($search)    $url .= "&q=" . urlencode($search);
                if ($filtre_cat) $url .= "&cat=$filtre_cat";
                ?>
                <?php if ($i == $page_num): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= $url ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <?php endif; ?>


        <?php else: ?>
        <!-- ════════════════ FORMULAIRE AJOUTER / MODIFIER ════════════════ -->

        <div class="form-card">
            <form method="POST" enctype="multipart/form-data">
                <?php if ($livre_edit): ?>
                    <input type="hidden" name="edit_id" value="<?= $livre_edit['id'] ?>">
                    <input type="hidden" name="old_image" value="<?= htmlspecialchars($livre_edit['image'] ?? '') ?>">
                    <input type="hidden" name="old_pdf" value="<?= htmlspecialchars($livre_edit['fichier_pdf'] ?? '') ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Titre *</label>
                        <input type="text" name="titre" required
                               value="<?= htmlspecialchars(trim($livre_edit['titre'] ?? '')) ?>">
                    </div>
                    <div class="form-group">
                        <label>Auteur *</label>
                        <input type="text" name="auteur" required
                               value="<?= htmlspecialchars(trim($livre_edit['auteur'] ?? '')) ?>">
                    </div>
                    <div class="form-group">
                        <label>Catégorie</label>
                        <select name="id_categorie">
                            <option value="">— Aucune —</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>"
                                    <?= isset($livre_edit['id_categorie']) && $livre_edit['id_categorie'] == $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Image de couverture</label>
                        <input type="file" name="image" accept="image/*">
                        <?php if (!empty($livre_edit['image'])): ?>
                            <div style="margin-top:6px;display:flex;align-items:center;gap:8px">
                                <img src="../uploads/images/<?= htmlspecialchars($livre_edit['image']) ?>"
                                     style="height:50px;border-radius:4px">
                                <span style="font-size:11px;color:var(--muted)">Image actuelle</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group full">
                        <label>Description</label>
                        <textarea name="description" rows="4"><?= htmlspecialchars($livre_edit['description'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group full">
                        <label>Fichier PDF <?= $livre_edit ? '' : '*' ?></label>
                        <input type="file" name="pdf" accept="application/pdf" <?= $livre_edit ? '' : 'required' ?>>
                        <?php if (!empty($livre_edit['fichier_pdf'])): ?>
                            <span style="font-size:11px;color:var(--muted);margin-top:4px">
                                <i class="fas fa-file-pdf" style="color:var(--danger)"></i>
                                Fichier actuel : <?= htmlspecialchars($livre_edit['fichier_pdf']) ?>
                                (laisser vide pour garder)
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <?= $livre_edit ? 'Enregistrer les modifications' : 'Ajouter le livre' ?>
                    </button>
                    <a href="livres.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>

        <?php endif; ?>

    </div>
</div>
</body>
</html>