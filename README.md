# Vide Grenier en Ligne

Ce Readme.md est à destination des futurs repreneurs du site-web Vide Grenier en Ligne.

Title and Description
Vide Grenier CESI Project est un projet développé dans le cadre des études au CESI, visant à créer une plateforme web pour organiser, gérer et rechercher des vide-greniers. Il permet aux utilisateurs de publier, consulter et gérer des annonces de vide-grenier, avec des fonctionnalités adaptées aux organisateurs et aux visiteurs.

Features
Création et gestion de vide-greniers : Les organisateurs peuvent publier, modifier ou supprimer leurs annonces.

Recherche de vide-greniers : Les visiteurs peuvent filtrer les vide-greniers par date, lieu, etc.

Gestion des utilisateurs : Inscription, connexion et profils personnalisés.

Interface responsive : Accessible sur ordinateur, tablette et mobile.

Installation
Cloner le dépôt :

bash
git clone https://github.com/ilionnnn/vide-grenier-cesi-project.git
cd vide-grenier-cesi-project
Installer les dépendances :

bash
composer install
npm install
Configurer l’environnement :

Créer un fichier .env à partir de .env.example et remplir les paramètres nécessaires.

Lancer le serveur :

bash
php artisan serve
npm run dev
(À adapter selon la stack technique réelle du projet.)

Run Locally
Pour lancer le projet en local :

bash
php artisan serve
npm run dev
Accédez à l’application via http://localhost:8000.

Tech
Backend : PHP
Base de données : MySQL/PostgreSQL
Serveur web : Apache/Nginx
Outils : Docker pour le développement (configuration possible)


## Mise en place du projet back-end

```php
docker-compose down --volumes --remove-orphans
docker-compose build --no-cache
docker-compose up -d
```

## Routing

Le [Router](Core/Router.php) traduit les URLs. 

Les routes sont ajoutées via la méthode `add`. 

En plus des **controllers** et **actions**, vous pouvez spécifier un paramètre comme pour la route suivante:

```php
$router->add('product/{id:\d+}', ['controller' => 'Product', 'action' => 'show']);
```


## Vues

Les vues sont rendues grâce à **Twig**. 
Vous les retrouverez dans le dossier `App/Views`. 

```php
View::renderTemplate('Home/index.html', [
    'name'    => 'Toto',
    'colours' => ['rouge', 'bleu', 'vert']
]);
```
## Models

Les modèles sont utilisés pour récupérer ou stocker des données dans l'application. Les modèles héritent de `Core
\Model
` et utilisent [PDO](http://php.net/manual/en/book.pdo.php) pour l'accès à la base de données. 

```php
$db = static::getDB();
```

### Environment Variables
DB_CONNECTION : Type de base de données

DB_HOST : Adresse de la base de données

DB_PORT : Port de la base de données

DB_DATABASE : Nom de la base de données

DB_USERNAME : Utilisateur de la base de données

DB_PASSWORD : Mot de passe de la base de données

## Authors
Équipe du projet Vide Grenier CESI (GitHub : ilionnnn)
