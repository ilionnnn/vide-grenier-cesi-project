<?php

namespace App\Controllers;

use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Hash;
use App\Utility\Session;
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
        // TODO: Validation
        $loginOk = $this->login($f);

        if ($loginOk) {
            header('Location: /account');
            exit;
        } else {
            View::renderTemplate('User/login.html'); 
        }
    }
    ;
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
            Utility\Flash::danger($ex->getMessage());
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
                $token = bin2hex(random_bytes(32));
                \App\Models\User::storeRememberToken($user['id'], $token);
                setcookie('remember_me', $token, time() + 3600 * 24 * 30, '/', '', false, true);
            }

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
