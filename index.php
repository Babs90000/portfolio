<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Mon Portfolio</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#projects">Projets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">À propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/Babs90000" target="_blank"><i class="bi bi-github"></i> GitHub</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.linkedin.com/in/babou-camara-diaby/" target="_blank"><i class="bi bi-linkedin"></i> LinkedIn</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../login.php"><i class="bi bi-box-arrow-in-right"></i> Espace Admin</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 class="my-4">Mes réalisations</h1>
        <div class="row" id="projects">
            <?php
            require 'database.php';
            $sql = "SELECT titre, description, url, image_blob FROM projects";
            $stmt = $bdd->query($sql);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="col-lg-4 col-sm-6 mb-4">';
                echo '<div class="card h-100">';
                if ($row['image_blob']) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image_blob']) . '" alt="' . htmlspecialchars_decode($row['titre'], ENT_QUOTES, 'UTF-8') . '" class="card-img-top" style="height: 200px; object-fit: cover;">';
                }
                echo '<div class="card-body">';
                echo '<h4 class="card-title">' . htmlspecialchars_decode($row['titre'], ENT_QUOTES, 'UTF-8') . '</h4>';
                echo '<p class="card-text">' . htmlspecialchars_decode($row['description'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<a href="' . htmlspecialchars_decode($row['url'], ENT_QUOTES, 'UTF-8') . '" class="btn btn-primary">Voir le site</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            if ($stmt->rowCount() == 0) {
                echo '<div class="alert alert-info">Aucun projet trouvé</div>';
            }
            ?>
        </div>
    </div>

    <!-- Section À propos -->
    <div class="container" id="about">
        <h1 class="my-4 text-center">À propos de moi</h1>
        <div class="row">
            <div class="col-md-4">
                <img src="./Images/photo_profil.jpg" alt="Ma photo en costume" class="img-fluid rounded-circle">
            </div>
            <div class="col-md-8">
                <p>Bonjour, je m'appelle Babou CAMARA-DIABY. Je suis un développeur passionné avec une expertise en PHP mais en dehors connais d'autres langages en dehors de HTML/CSS qui sont JS et je sais utilisé les bases de données SQL et noSQL comme MongoDB . J'ai travaillé sur différents petits projets, allant de la création d'un site web pour un zoo fictif à mon CV .</p>
                <p>J'aime résoudre des problèmes complexes et créer des solutions innovantes. Mon objectif est de continuer à apprendre et à évoluer dans le domaine du développement.</p>
                <p>En dehors du travail, j'aime les mangas, les voyages et le sport.</p>
                <p>N'hésitez pas à me contacter pour discuter de projets potentiels ou simplement pour échanger des idées.</p>
            </div>
        </div>
    </div>

    <!-- Section Contact -->
    <div class="container" id="contact">
        <h1 class="my-4 text-center">Contactez-moi</h1>
        <form action="contact.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="message">Message :</label>
                <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="attachment">Pièce jointe :</label>
                <input type="file" id="attachment" name="attachment" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>