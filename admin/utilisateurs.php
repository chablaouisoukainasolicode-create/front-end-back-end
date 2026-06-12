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
    if ($id == $_SESSION['id']) {
        $error = "Vous ne pouvez pas supprimer votre propre compte.";
    } else {
        $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?")->execute([$id]);
        $message = "Utilisateur supprimé.";
    }
}

// ─── CHANGER RÔLE ───
if (isset($_GET['action']) && $_GET['action'] === 'toggle_role' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $row = $pdo->prepare("SELECT role FROM utilisateurs WHERE id = ?");
    $row->execute([$id]);
    $row = $row->fetch();
    if ($row) {
        $new_role = $row['role'] === 'admin' ? 'user' : 'admin';
        $pdo->prepare("UPDATE utilisateurs SET role = ? WHERE id = ?")->execute([$new_role, $id]);
        $message = "Rôle mis à jour.";
    }
}

// ─── DATA ───
$search = trim($_GET['q'] ?? '');
$filtre = $_GET['role'] ?? '';

$where  = "WHERE 1=1";
$params = [];
if ($search) {
    $where  .= " AND (nom LIKE ? OR prenom LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($filtre === 'admin' || $filtre === 'user') {
    $where  .= " AND role = ?";
    $params[] = $filtre;
}

$stmt = $pdo->prepare("SELECT * FROM utilisateurs $where ORDER BY date_creation DESC");
$stmt->execute($params);
$users = $stmt->fetchAll();

$colors = ['#EEEDFE:#3C3489','#E1F5EE:#085041','#FAEEDA:#633806','#FBEAF0:#72243E','#E6F1FB:#0C447C'];

function avatar_color(int $id): array {
    global $colors;
    [$bg, $fg] = explode(':', $colors[$id % count($colors)]);
    return [$bg, $fg];
}

$active_page = 'utilisateurs';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin — Utilisateurs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include '../admin/admin_sidebar.php'; ?>

<div class="main">
    <div class="topbar">
        <div class="topbar-title">Utilisateurs</div>
        <div class="avatar"><?= strtoupper(substr($_SESSION['prenom'] ?? 'A', 0, 1)) ?></div>
    </div>

    <div class="content">

        <?php if ($message): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Filtres -->
        <form method="GET">
            <div class="filters" style="margin-bottom:1.25rem">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" name="q" placeholder="Nom, prénom, email…" value="<?= htmlspecialchars($search) ?>">
                </div>
                <select name="role" class="filter-select">
                    <option value="">Tous les rôles</option>
                    <option value="admin" <?= $filtre === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="user"  <?= $filtre === 'user'  ? 'selected' : '' ?>>Utilisateur</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filtrer</button>
                <?php if ($search || $filtre): ?>
                    <a href="utilisateurs.php" class="btn btn-secondary"><i class="fas fa-times"></i> Réinitialiser</a>
                <?php endif; ?>
                <span style="margin-left:auto;font-size:13px;color:var(--muted)"><?= count($users) ?> utilisateur(s)</span>
            </div>
        </form>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-users"></i><p>Aucun utilisateur trouvé.</p></div></td></tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                    <?php [$bg, $fg] = avatar_color($u['id']); ?>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar" style="background:<?= $bg ?>;color:<?= $fg ?>">
                                    <?= strtoupper(substr($u['prenom'], 0, 1) . substr($u['nom'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="user-name"><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></div>
                                    <div class="user-email">ID #<?= $u['id'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px;color:var(--muted)"><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge <?= $u['role'] === 'admin' ? 'role-admin' : 'role-user' ?>">
                                <?= $u['role'] === 'admin' ? '<i class="fas fa-shield-alt"></i> Admin' : 'Utilisateur' ?>
                            </span>
                        </td>
                        <td style="font-size:12px;color:var(--muted);white-space:nowrap">
                            <?= date('d/m/Y', strtotime($u['date_creation'])) ?>
                        </td>
                        <td>
                            <div class="actions">
                                <?php if ($u['id'] != $_SESSION['id']): ?>
                                    <a href="?action=toggle_role&id=<?= $u['id'] ?>"
                                       class="btn-icon"
                                       title="<?= $u['role'] === 'admin' ? 'Rétrograder' : 'Promouvoir admin' ?>"
                                       onclick="return confirm('Changer le rôle de cet utilisateur ?')">
                                        <i class="fas fa-<?= $u['role'] === 'admin' ? 'user-minus' : 'user-shield' ?>"></i>
                                    </a>
                                    <a href="?action=supprimer&id=<?= $u['id'] ?>"
                                       class="btn-icon del"
                                       onclick="return confirm('Supprimer cet utilisateur ?')"
                                       title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <span style="font-size:11px;color:var(--muted)">(vous)</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>