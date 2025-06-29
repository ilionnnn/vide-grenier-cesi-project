<?php
require_once 'App/Config.php';

echo "APP_ENV: " . getenv('APP_ENV') . "<br>";
echo "DB_HOST: " . \App\Config::get('DB_HOST') . "<br>";
echo "DB_USER: " . \App\Config::get('DB_USER') . "<br>";
echo "DB_NAME: " . \App\Config::get('DB_NAME') . "<br>";

try {
    $pdo = new PDO(
        "mysql:host=" . \App\Config::get('DB_HOST') . ";dbname=" . \App\Config::get('DB_NAME'),
        \App\Config::get('DB_USER'),
        \App\Config::get('DB_PASSWORD')
    );
    echo "<p style='color:green'>✅ Connexion BD réussie!</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Erreur connexion: " . $e->getMessage() . "</p>";
}
?>
