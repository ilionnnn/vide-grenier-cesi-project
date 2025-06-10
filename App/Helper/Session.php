<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Helper\Hash;
use App\Helper\Cookie;
use App\Helper\Config;
use \Core\View;
use Exception;

class User extends \Core\Controller
{
    public function loginAction()
    {
        if (isset($_POST['submit'])) {
            $f = $_POST;
            $this->login($f);
            header('Location: /account');
        }
        View::renderTemplate('User/login.html');
    }

    public function registerAction()
    {
        if (isset($_POST['submit'])) {
            $f = $_POST;
            if ($f['password'] !== $f['password-check']) {
                // Gérer l'erreur côté utilisateur si besoin
            }
            $this->register($f);
            $this->login($f);
            header('Location: /account');
        }
        View::renderTemplate('User/register.html');
    }

    public function accountAction()
    {
        $articles = Articles::getByUser($_SESSION['user']['id']);
        View::renderTemplate('User/account.html', [
            'articles' => $articles
        ]);
    }

    private function register($data)
    {
        try {
            $salt = Hash::generateSalt(32);
            return \App\Models\User::createUser([
                "email" => $data['email'],
                "username" => $data['username'],
                "password" => Hash::generate($data['password'], $salt),
                "salt" => $salt
            ]);
        } catch (Exception $ex) {
            // Gérer l'erreur si besoin
        }
    }

    private function login($data)
    {
        try {
            if (!isset($data['email'])) {
                throw new Exception('Email manquant');
            }
            $user = \App\Models\User::getByLogin($data['email']);
            if (Hash::generate($data['password'], $user['salt']) !== $user['password']) {
                return false;
            }
            if (isset($data['remember'])) {
                $hash = Hash::generate($user["id"], $user['salt']);
                Cookie::put(Config::get("COOKIE_USER"), $hash, Config::get("COOKIE_DEFAULT_EXPIRY"));
            }
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
            ];
            return true;
        } catch (Exception $ex) {
            // Gérer l'erreur si besoin
        }
    }

    public function logoutAction()
    {
        if (Cookie::exists(Config::get("COOKIE_USER"))) {
            Cookie::delete(Config::get("COOKIE_USER"));
        }
        $_SESSION = [];
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
        header("Location: /");
        return true;
    }
}
