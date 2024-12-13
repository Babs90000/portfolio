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
    <title>Ajouter un projet</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Ajouter un projet</h1>
        <form action="add_project.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="url">URL :</label>
                <input type="text" id="url" name="url" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="image">Image :</label>
                <input type="file" id="image" name="image" class="form-control-file" required>
            </div>
            <div class="form-group">
                <label for="date">Date :</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter le projet</button>
        </form>

        <?php
       
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = htmlspecialchars($_POST['titre'], ENT_QUOTES, 'UTF-8');
            $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
            $url = htmlspecialchars($_POST['url'], ENT_QUOTES, 'UTF-8');
            $image = $_FILES['image'];
            $date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');

            // Lire le contenu du fichier image
            $image_blob = file_get_contents($image['tmp_name']);

            $sql = "INSERT INTO projects (titre, description, url, image_blob, date) VALUES (:titre, :description, :url, :image_blob, :date)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':url', $url, PDO::PARAM_STR);
            $stmt->bindValue(':image_blob', $image_blob, PDO::PARAM_LOB);
            $stmt->bindValue(':date', $date, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success mt-4'>Nouveau projet ajouté avec succès</div>";
            } else {
                echo "<div class='alert alert-danger mt-4'>Erreur : " . $stmt->errorInfo()[2] . "</div>";
            }
        }
        ?>

        <a class="btn btn-secondary mt-4" href="espace_admin.php">Retour vers l'espace admin</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>