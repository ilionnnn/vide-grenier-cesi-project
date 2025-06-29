<?php
namespace App;

class Config
{
    public const SHOW_ERRORS = true; // ou false selon le besoin

    private static $environments = [
        'dev' => [
            'DB_HOST' => 'db',
            'DB_NAME' => 'mon_site_dev',
            'DB_USER' => 'user_dev',
            'DB_PASSWORD' => 'pass_dev',
            'SHOW_ERRORS' => true,
        ],
        'prod' => [
            'DB_HOST' => 'db',
            'DB_NAME' => 'mon_site_prod',
            'DB_USER' => 'user_prod',
            'DB_PASSWORD' => 'pass_prod',
            'SHOW_ERRORS' => false,
        ],
    ];

    const COOKIE_USER = 'user';
    const COOKIE_DEFAULT_EXPIRY = 3600 * 24 * 30;

    public static function get($key)
    {
        // 1. Vérifie d'abord les variables d'environnement
        $envValue = getenv($key);
        if ($envValue !== false) {
            return $envValue;
        }

        // 2. Fallback sur la configuration statique
        $env = getenv('APP_ENV') ?: 'dev';
        if (isset(self::$environments[$env][$key])) {
            return self::$environments[$env][$key];
        }

        // 3. Vérifie les constantes
        $const = __CLASS__ . '::' . $key;
        if (defined($const)) {
            return constant($const);
        }

        return null;
    }
}


