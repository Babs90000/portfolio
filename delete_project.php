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
    <title>Supprimer un projet</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Supprimer un projet</h1>

        <?php
    
        // Récupérer tous les projets pour la liste déroulante
        $sql = "SELECT id, titre FROM projects";
        $stmt = $bdd->query($sql);
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $project_id = isset($_POST['project_id']) ? htmlspecialchars($_POST['project_id'], ENT_QUOTES, 'UTF-8') : '';

            if ($project_id) {
                $sql = "DELETE FROM projects WHERE id = :project_id";
                $stmt = $bdd->prepare($sql);
                $stmt->bindValue(':project_id', $project_id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success mt-4'>Projet supprimé avec succès</div>";
                } else {
                    echo "<div class='alert alert-danger mt-4'>Erreur : " . $stmt->errorInfo()[2] . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger mt-4'>Erreur : Aucun projet sélectionné</div>";
            }
        }
        ?>

        <form action="delete_project.php" method="post">
            <div class="form-group">
                <label for="project_id">Sélectionner un projet à supprimer :</label>
                <select id="project_id" name="project_id" class="form-control" required>
                    <option value="">-- Sélectionner un projet --</option>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?php echo htmlspecialchars($project['id'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($project['titre'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-danger">Supprimer le projet</button>
        </form>

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