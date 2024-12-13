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

        <form action="update_project.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="project_id">Sélectionner un projet :</label>
                <select id="project_id" name="project_id" class="form-control" onchange="this.form.submit()">
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
                    <input type="text" id="titre" name="titre" class="form-control" value="<?php echo htmlspecialchars($selected_project['titre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($selected_project['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="url">URL :</label>
                    <input type="text" id="url" name="url" class="form-control" value="<?php echo htmlspecialchars($selected_project['url'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="image">Image :</label>
                    <input type="file" id="image" name="image" class="form-control-file">
                </div>
                <div class="form-group">
                    <label for="date">Date :</label>
                    <input type="date" id="date" name="date" class="form-control" value="<?php echo htmlspecialchars($selected_project['date'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($selected_project['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="update_project" value="1">
                <button type="submit" class="btn btn-primary">Mettre à jour le projet</button>
            </form>
        <?php endif; ?>

        <h2 class="my-4">Liste des projets</h2>
        <ul class="list-group">
            <?php
            $sql = "SELECT id, titre, description, url, image_blob, date FROM projects";
            $stmt = $bdd->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<li class="list-group-item">';
                echo '<h3>' . htmlspecialchars($row['titre'], ENT_QUOTES, 'UTF-8') . '</h3>';
                echo '<p>' . htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<a href="' . htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8') . '">Voir le site</a><br>';
                if ($row['image_blob']) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image_blob']) . '" alt="' . htmlspecialchars($row['titre'], ENT_QUOTES, 'UTF-8') . '" width="200" height="150"><br>';
                }
                echo '<small>Date : ' . htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8') . '</small>';
                echo '</li>';
            }
            ?>
        </ul>
        <a class="btn btn-secondary mt-4" href="espace_admin.php">Retour vers l'espace admin</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>