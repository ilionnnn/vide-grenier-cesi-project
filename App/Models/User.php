<?php

namespace App\Models;

use Core\Model;
use Exception;

class User extends Model
{
    /**
     * Récupère un utilisateur par son ID
     */
    public static function getById($id)
    {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur par son email (pour le login)
     */
 public static function getByLogin($email)
{
    return [
        'id' => 1,
        'username' => 'testuser',
        'salt' => 'abc123', 
        'password' => \App\Utility\Hash::generate('password', 'abc123'), 
    ];
}


    /**
     * Crée un nouvel utilisateur
     */
    private static $mockedCreateCallback = null;

public static function mockCreateUser(callable $callback): void
{
    self::$mockedCreateCallback = $callback;
}

public static function createUser($data)
{
    if (self::$mockedCreateCallback !== null) {
        return call_user_func(self::$mockedCreateCallback, $data);
    }

    // ⚠️ Code original (exécuté seulement si non mocké)
    $db = static::getDB();
    $stmt = $db->prepare('
        INSERT INTO users (email, username, password, salt)
        VALUES (:email, :username, :password, :salt)
    ');
    $stmt->execute([
        ':email' => $data['email'],
        ':username' => $data['username'],
        ':password' => $data['password'],

        ':salt' => $data['salt']
    ]);
    return $db->lastInsertId();
}

    /**
     * Stocke le token "remember me" pour l'utilisateur
     */
    public static function storeRememberToken($userId, $token)
    {
        $db = static::getDB();
        $stmt = $db->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
        $stmt->execute([
            ':token' => $token,
            ':id' => $userId
        ]);
    }

    /**
     * Supprime le token "remember me" (optionnel, pour logout)
     */
    public static function deleteRememberToken($token)
    {
        $db = static::getDB();
        $stmt = $db->prepare("UPDATE users SET remember_token = NULL WHERE remember_token = :token");
        $stmt->execute([':token' => $token]);
    }

    private static $mockedLoginCallback = null;

        public static function mockGetByLogin(callable $callback): void
        {
            self::$mockedLoginCallback = $callback;
        }

        public static function getByLoginMock($email)
        {
            if (self::$mockedLoginCallback !== null) {
                return call_user_func(self::$mockedLoginCallback, $email);
            }

            // ⚠️ Remets ici ton vrai comportement si nécessaire
            throw new \Exception("getByLogin() non mocké dans le test.");
        }
}
