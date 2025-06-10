<?php

namespace App;

class Config
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'videgrenierenligne';
    const DB_USER = 'webapplication';
    const DB_PASSWORD = '653rag9T';
    const SHOW_ERRORS = true;
    const COOKIE_USER = 'user';
    const COOKIE_DEFAULT_EXPIRY = 3600 * 24 * 30; // 30 jours

    public static function get($key) {
        $const = __CLASS__ . '::' . $key;
        if (defined($const)) {
            return constant($const);
        }
        return null;
    }
}