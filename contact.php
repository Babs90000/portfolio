<?php
require_once './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $attachment = $_FILES['attachment'];

    $phpmailer = new PHPMailer(true);

    try {
        // Configuration du serveur SMTP
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.gmail.com'; // Adresse du serveur SMTP de Gmail
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = 'camara.enc@gmail.com'; // Votre adresse e-mail Gmail
        $phpmailer->Password = 'togl uqjd anhj fgkh'; // Votre mot de passe Gmail
        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpmailer->Port = 587;


        // Configurer l'encodage
        $phpmailer->CharSet = 'UTF-8';

        // En-têtes de l'e-mail
        $phpmailer->setFrom('b.camara.diaby@outlook.com', 'Babou-CAMARA-DIABY'); // Utiliser votre adresse e-mail comme expéditeur
        $phpmailer->addAddress('b.camara.diaby@outlook.com'); // Ajouter un destinataire
        $phpmailer->addReplyTo($email, $name); // Ajouter l'adresse e-mail de l'utilisateur comme adresse de réponse

        // Ajouter la pièce jointe si elle existe
        if ($attachment['error'] == UPLOAD_ERR_OK) {
            $phpmailer->addAttachment($attachment['tmp_name'], $attachment['name']);
        }

        // Contenu de l'e-mail
        $phpmailer->isHTML(false); // Envoyer l'e-mail en texte brut
        $phpmailer->Subject = 'Nouveau message de ' . $name;
        $phpmailer->Body    = 'Vous avez reçu un nouveau message de ' . $name . ' (' . $email . ') :<br><br>' . nl2br($message);

        $phpmailer->send();

        // Envoyer l'e-mail de confirmation à l'utilisateur
        $phpmailer->clearAddresses();
        $phpmailer->addAddress($email);
        $phpmailer->Subject = "Confirmation de réception de votre message";
        $phpmailer->Body    = "Bonjour " . $name . ",\n\nMerci de votre intérêt pour collaborer avec moi. J'ai bien reçu votre message et je reviendrai vers vous dès que possible.\n\nCordialement,\nBabou CAMARA-DIABY";
        $phpmailer->send();

        echo "Vous allez être redirigé à la page précédente dans 5 secondes...";
        header("refresh:5;url=" . $_SERVER['HTTP_REFERER']);
        exit();
      
    } catch (Exception $e) {
        echo "Votre message n'a pas été envoyé: {$phpmailer->ErrorInfo}</br>";
        echo "La page va se rafraîchir dans 5 secondes...";
        header("refresh:5;url=" . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>