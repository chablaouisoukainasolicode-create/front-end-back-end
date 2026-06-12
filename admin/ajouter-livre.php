<?php
require "../config/config.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* categories */
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

/* create pdf folder if not exists */
if (!is_dir("../uploads/pdf")) {
    mkdir("../uploads/pdf", 0777, true);
}

if (isset($_POST['ajouter'])) {

    /* data */
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $description = $_POST['description'];
    $categorie_id = $_POST['id_categorie'];

    /* IMAGE */
    $image = time() . "_" . $_FILES['image']['name'];
    $tmp_image = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp_image, "../uploads/" . $image);

    /* PDF */
    $pdf = time() . "_" . $_FILES['fichier_pdf']['name'];
    $tmp_pdf = $_FILES['fichier_pdf']['tmp_name'];
    move_uploaded_file($tmp_pdf, "../uploads/pdf/" . $pdf); 

    /* INSERT */
    $stmt = $pdo->prepare("
        INSERT INTO livres
        (titre, auteur, description, fichier_pdf, image, id_categorie, date_ajout)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([
        $titre,
        $auteur,
        $description,
        $pdf,
        $image,
        $categorie_id
    ]);

    echo "<p style='color:green;'>Livre ajouté avec succès 👍</p>";
}
?>
<form method="POST" enctype="multipart/form-data">
<label>Titre:</label>
    <input type="text" name="titre" placeholder="Titre du livre" required><br><br>

    <label>Auteur:</label>
    <input type="text" name="auteur" placeholder="Auteur" required><br><br>

    <label>Description:</label>
    <textarea name="description" placeholder="Description"></textarea><br><br>

    <select name="id_categorie" required>
        <option value="">Choisir catégorie</option>

        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>">
                <?= $cat['nom'] ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <input type="file" name="image" required><br><br>

    <input type="file" name="fichier_pdf" required><br><br>

    <button type="submit" name="ajouter">Ajouter Livre</button>
    <
</form>
