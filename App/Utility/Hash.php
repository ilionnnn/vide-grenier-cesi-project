<?php

namespace App\Utility;

/**
 * Hash:
 */
class Hash {

    /**
     * Génère et retourne un hash
     */
    public static function generate($string, $salt = "") {
        return(hash("sha256", $string . $salt));
    }

    /**
     * Génère et retourne un salt
     */
    public static function generateSalt($length) {
        $salt = "";
        $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/\\][{}\'\";:?.>,<!@#$%^&*()-_=+|";
        for ($i = 0; $i < $length; $i++) {
            $salt .= $charset[mt_rand(0, strlen($charset) - 1)];
        }
        return $salt;
    }

    /**
     * Vérifie si le hash correspond à la chaîne de caractères et au sel
     */
    
    public static function check($string, $salt, $hash)
{
    return self::generate($string, $salt) === $hash;
}


    /**
     * Génère et retourne un UID
     */
    public static function generateUnique() {
        return(self::generate(uniqid()));
    }

}
