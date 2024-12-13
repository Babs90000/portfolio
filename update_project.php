<?php
session_start();
require 'database.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à jour un projet</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Mettre à jour un projet</h1>

        <?php

        // Récupérer tous les projets pour la liste déroulante
        $sql = "SELECT id, titre FROM projects";
        $stmt = $bdd->query($sql);
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $selected_project = null;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_project'])) {
            $project_id = isset($_POST['project_id']) ? htmlspecialchars($_POST['project_id'], ENT_QUOTES, 'UTF-8') : '';
            $titre = isset($_POST['titre']) ? htmlspecialchars($_POST['titre'], ENT_QUOTES, 'UTF-8') : '';
            $description = isset($_POST['description']) ? htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8') : '';
            $url = isset($_POST['url']) ? htmlspecialchars($_POST['url'], ENT_QUOTES, 'UTF-8') : '';
            $date = isset($_POST['date']) ? htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8') : '';
            $image = isset($_FILES['image']) ? $_FILES['image'] : null;

            $image_blob = null;
            if ($image && $image["tmp_name"]) {
                // Lire le contenu du fichier image
                $image_blob = file_get_contents($image['tmp_name']);
            }

            if ($image_blob) {
                $sql = "UPDATE projects SET titre = :titre, description = :description, url = :url, image_blob = :image_blob, date = :date WHERE id = :project_id";
                $stmt = $bdd->prepare($sql);
                $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
                $stmt->bindValue(':description', $description, PDO::PARAM_STR);
                $stmt->bindValue(':url', $url, PDO::PARAM_STR);
                $stmt->bindValue(':image_blob', $image_blob, PDO::PARAM_LOB);
                $stmt->bindValue(':date', $date, PDO::PARAM_STR);
                $stmt->bindValue(':project_id', $project_id, PDO::PARAM_INT);
            } else {
                $sql = "UPDATE projects SET titre = :titre, description = :description, url = :url, date = :date WHERE id = :project_id";
                $stmt = $bdd->prepare($sql);
                $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
                $stmt->bindValue(':description', $description, PDO::PARAM_STR);
                $stmt->bindValue(':url', $url, PDO::PARAM_STR);
                $stmt->bindValue(':date', $date, PDO::PARAM_STR);
                $stmt->bindValue(':project_id', $project_id, PDO::PARAM_INT);
            }

            if ($stmt->execute()) {
                echo "<div class='alert alert-success mt-4'>Projet mis à jour avec succès</div>";
            } else {
                echo "<div class='alert alert-danger mt-4'>Erreur : " . $stmt->errorInfo()[2] . "</div>";
            }
        }

        // Si un projet est sélectionné, récupérer ses détails
        if (isset($_POST['project_id']) && !isset($_POST['update_project'])) {
            $project_id = htmlspecialchars($_POST['project_id'], ENT_QUOTES, 'UTF-8');
            $sql = "SELECT * FROM projects WHERE id = :project_id";
            $stmt = $bdd->prepare($sql);
            $stmt->bindValue(':project_id', $project_id, PDO::PARAM_INT);
            $stmt->execute();
            $selected_project = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
            <option value="">-- Sélectionner un projet --</option>
<?php foreach ($projects as $project): ?>
    <option value="<?php echo htmlspecialchars($project['id'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo (isset($selected_project) && $selected_project['id'] == $project['id']) ? 'selected' : ''; ?>>
        <?php echo htmlspecialchars($project['titre'], ENT_QUOTES, 'UTF-8'); ?>
    </option>
<?php endforeach; ?>
</select>
</div>
</form>

<?php if ($selected_project): ?>
    <form action="update_project.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" class="form-control" value="<?php echo htmlspecialchars_decode($selected_project['titre'], ENT_QUOTES); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars_decode($selected_project['description'], ENT_QUOTES); ?></textarea>
        </div>
        <div class="form-group">
            <label for="url">URL :</label>
            <input type="text" id="url" name="url" class="form-control" value="<?php echo htmlspecialchars_decode($selected_project['url'], ENT_QUOTES); ?>" required>
        </div>
        <div class="form-group">
            <label for="image">Image :</label>
            <input type="file" id="image" name="image" class="form-control-file">
        </div>
        <div class="form-group">
            <label for="date">Date :</label>
            <input type="date" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars_decode($selected_project['date'], ENT_QUOTES); ?>" required>
        </div>
        <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($selected_project['id'], ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" name="update_project" class="btn btn-primary">Mettre à jour le projet</button>
    </form>
<?php endif; ?>