<?php

namespace App\Controllers;

use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Hash;
use App\Utility\Session;
use App\Helper\Cookie;
use \Core\View;
use Exception;

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
        $loginOk = $this->login($f);

        if ($loginOk) {
            header('Location: /account');
            exit;
        } else {
            View::renderTemplate('User/login.html'); 
            return;
        }
    }
    // Affiche la vue si GET ou si POST sans succès
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
                throw new \InvalidArgumentException('Les mots de passe ne correspondent pas.');
            }

            $userID = $this->register($f);

            if ($userID) {
                $user = \App\Models\User::getById($userID);
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                ];
                header('Location: /account');
                exit;
            } else {
                $this->loginAction();
                return;
            }
        }
        // Affiche toujours le formulaire si GET ou si POST sans succès
        View::renderTemplate('User/register.html');
    }

    /*
     * Fonction privée pour enregistrer un utilisateur
     */
    private function register($data)
    {
        try {
            $salt = Hash::generateSalt(32);

            $userID = \App\Models\User::createUser([
                "email" => $data['email'],
                "username" => $data['username'],
                "password" => Hash::generate($data['password'], $salt),
                "salt" => $salt
            ]);

            return $userID;

        } catch (Exception $ex) {
           // Utility\Flash::danger($ex->getMessage());
            return false;
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



private function login($data) {
    try {
        if (empty($data['email']) || empty($data['password'])) {
            return false;
        }
        $user = \App\Models\User::getByLogin($data['email']);
       
if (!$user) {
    var_dump('Utilisateur non trouvé');
    exit;
}
if (Hash::generate($data['password'], $user['salt']) !== $user['password']) {
    var_dump('Mot de passe incorrect');
    var_dump('Password saisi:', $data['password']);
var_dump('Salt utilisé:', $user['salt']);
var_dump('Hash attendu:', $user['password']);
var_dump('Hash calculé:', Hash::generate($data['password'], $user['salt']));

    exit;
}

        if (!$user) return false;
        if (Hash::generate($data['password'], $user['salt']) !== $user['password']) return false;

       $_SESSION['user'] = [
    'id' => $user['id'],
    'username' => $user['username'],
];


        // Se souvenir de moi
        if (!empty($data['remember_me'])) {
            $token = bin2hex(random_bytes(32));
            \App\Models\User::storeRememberToken($user['id'], $token);
            setcookie('remember_me', $token, [
                'expires' => time() + 3600 * 24 * 30,
                'path' => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        // Force la sauvegarde de la session avant redirection (optionnel)
        session_write_close();
        return true;
    } catch (Exception $ex) {
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

        // Delete the cookie if it exists.
     if (Cookie::exists(Config::get("COOKIE_USER"))) {
    Cookie::delete(Config::get("COOKIE_USER"));
}

        // Destroy all data registered to the session.
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header ("Location: /");

        return true;
    }

}
