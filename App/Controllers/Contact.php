<?php
namespace App\Controllers;

use \Core\View;

class Contact extends \Core\Controller
{
    public function indexAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $message = trim($_POST['message'] ?? '');

            if (filter_var($email, FILTER_VALIDATE_EMAIL) && $name && $message) {
                $to = 'ilianmahzem@orange.fr'; 
                $subject = 'Nouveau message depuis le formulaire de contact';
                $headers = 'From: webmaster@monsite.fr' . "\r\n" .
                           'Reply-To: ' . $email . "\r\n" .
                           'Content-Type: text/plain; charset=utf-8';

                $body = "Nom: $name\nEmail: $email\n\n$message";

                if (mail($to, $subject, $body, $headers)) {
                    header('Location: /merci');
                    exit;
                } else {
                    $error = "Erreur lors de l'envoi du message.";
                }
            } else {
                $error = "Veuillez remplir tous les champs correctement.";
            }
            // Affiche la vue avec l’erreur éventuelle
            View::renderTemplate('Contact/index.html', ['error' => $error]);
        } else {
            // Affiche le formulaire de contact si GET
            View::renderTemplate('Contact/index.html');
        }
    }
}

?>
