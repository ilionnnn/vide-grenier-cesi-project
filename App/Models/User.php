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
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouvel utilisateur
     */
    public static function createUser($data)
    {
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
}
