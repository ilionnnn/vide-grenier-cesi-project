<?php

namespace App\Controllers;

use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Hash;
use App\Utility\Session;
use \Core\View;
use Exception;
use http\Env\Request;
// use http\Exception\InvalidArgumentException; // Removed, use built-in instead

/**
 * User controller
 */
class User extends \Core\Controller
{

    /**
     * Affiche la page de login
     */
    public function loginAction()
    {
        if(isset($_POST['submit'])){
            $f = $_POST;

            // TODO: Validation

            $this->login($f);

            // Si login OK, redirige vers le compte
            header('Location: /account');
        }

        View::renderTemplate('User/login.html');
    }

    /**
     * Page de création de compte
     */
    public function registerAction()
    {
        if(isset($_POST['submit'])){
            $f = $_POST;

            if($f['password'] !== $f['password-check']){
                // TODO: Gestion d'erreur côté utilisateur
                throw new \InvalidArgumentException('Les mots de passe ne correspondent pas.');
            }
             // 1. Créer l'utilisateur et récupérer son ID
        $userID = $this->register($f);

        if ($userID) {
            // 2. Récupérer l'utilisateur depuis la base
            $user = \App\Models\User::getById($userID);

            // 3. Initialiser la session comme lors du login
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
            ];

            // 4. Rediriger vers la page de compte
            header('Location: /account');
            exit;
            // validation

            $this->register($f);
            // TODO: Rappeler la fonction de login pour connecter l'utilisateur
        } else {
            $this->loginAction(); 
        }

        View::renderTemplate('User/register.html');
    }
}
        
    /*
     * Fonction privée pour enregister un utilisateur
     */
    private function register($data)
    {
        try {
            // Generate a salt, which will be applied to the during the password
            // hashing process.
            $salt = Hash::generateSalt(32);

            $userID = \App\Models\User::createUser([
                "email" => $data['email'],
                "username" => $data['username'],
                "password" => Hash::generate($data['password'], $salt),
                "salt" => $salt
            ]);

            return $userID;

        } catch (Exception $ex) {
            // TODO : Set flash if error : utiliser la fonction en dessous
            /* Utility\Flash::danger($ex->getMessage());*/
        }
    }
  /**
     * Affiche la page du compte
     */
    public function accountAction()
    {
        $articles = Articles::getByUser($_SESSION['user']['id']);

        View::renderTemplate('User/account.html', [
            'articles' => $articles
        ]);
    }

    private function login($data){
        try {
            if(!isset($data['email'])){
                throw new Exception('TODO');
            }

            $user = \App\Models\User::getByLogin($data['email']);

            if (Hash::generate($data['password'], $user['salt']) !== $user['password']) {
                return false;
            }
            if (isset($data['remember_me'])) {
            // Créer un token unique (par exemple, un hash)
            $token = bin2hex(random_bytes(32));

            // Stocker ce token en base de données, associé à l'utilisateur
            \App\Models\User::storeRememberToken($user['id'], $token);

            // Créer le cookie persistant pour 30 jours
            setcookie('remember_me', $token, time() + 3600 * 24 * 30, '/', '', false, true);
}
            // TODO: Create a remember me cookie if the user has selected the option
            // to remained logged in on the login form.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L86

            $_SESSION['user'] = array(
                'id' => $user['id'],
                'username' => $user['username'],
            );

            return true;

        } catch (Exception $ex) {
            // TODO : Set flash if error
            
            /* Utility\Flash::danger($ex->getMessage());*/
            return false;

        }
    }


    /**
     * Logout: Delete cookie and session. Returns true if everything is okay,
     * otherwise turns false.
     * @access public
     * @return boolean
     * @since 1.0.2
     */
public function logoutAction() {
    // Supprimer le cookie "remember me"
    if (isset($_COOKIE['remember_me'])) {
        unset($_COOKIE['remember_me']);
        setcookie('remember_me', '', time() - 3600, '/');
        // Supprime aussi le token en base si besoin
        // \App\Models\User::deleteRememberToken($_COOKIE['remember_me']);
    }

    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();

    header ("Location: /");
    return true;
}


}
