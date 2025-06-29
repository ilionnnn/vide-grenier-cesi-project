<?php

namespace App;

class Config
{
    // Valeurs par défaut, fallback si variable d'env absente
    const DB_HOST = 'db';
    const DB_NAME = 'mon_site';
    const DB_USER = 'user';
    const DB_PASSWORD = 'pass';
    const SHOW_ERRORS = true;
    const COOKIE_USER = 'user';
    const COOKIE_DEFAULT_EXPIRY = 3600 * 24 * 30;

    public static function get($key)
    {
        // Essaye d'abord de récupérer la variable d'environnement Docker correspondante
        $envValue = getenv($key);
        if ($envValue !== false) {
            return $envValue;
        }

        // Sinon, retourne la constante définie dans la classe
        $const = __CLASS__ . '::' . $key;
        if (defined($const)) {
            return constant($const);
        }

        return null;
    }
}
