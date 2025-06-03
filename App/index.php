<?php
require_once __DIR__ . '/Config.php';

require_once __DIR__ . '/../Core/Router.php'; 

$router = new Core\Router();
$router->dispatch($_SERVER['REQUEST_URI']);
