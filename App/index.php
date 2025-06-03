<?php

// Charger la config
require_once __DIR__ . '/Config.php';

// Charger le routeur
require_once __DIR__ . '/../Core/Router.php';

// Démarrer le routage
try {
    $router = new Core\Router();
    $router->dispatch($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    // Gérer les erreurs (affichage simple ici)
    echo "Erreur : " . $e->getMessage();
}
