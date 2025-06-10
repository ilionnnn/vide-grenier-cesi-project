<?php
require_once __DIR__ . '/vendor/autoload.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader(__DIR__ . '/App/Views');
$twig = new Environment($loader);
echo "Twig fonctionne.";
