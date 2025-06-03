<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    $nom = $_POST['name'] ?? '';

    // Validation simple des champs
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message) && !empty($nom)) {
        $to = 'destinataire@monsite.fr'; // Mets ici l'adresse du destinataire
        $subject = 'Nouveau message depuis le formulaire de contact';
        $headers = 'From: webmaster@monsite.fr' . "\r\n" .
                   'Reply-To: ' . $email . "\r\n" .
                   'Content-Type: text/plain; charset=utf-8';

        $body = "Nom: $nom\nEmail: $email\n\n$message";

        // Envoi de l'email
        $success = mail($to, $subject, $body, $headers);

        if ($success) {
            // Redirection vers une page de confirmation
            header('Location: /merci');
            exit;
        } else {
            echo "Erreur lors de l'envoi du message.";
        }
    } else {
        echo "Veuillez remplir tous les champs correctement.";
    }
} else {
    // Accès direct sans POST
    echo "Accès non autorisé.";
}
?>
