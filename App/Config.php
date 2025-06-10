<?php

namespace App;

class Config
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'mon_site';
    const DB_USER = 'user';
    const DB_PASSWORD = 'pass';
    const SHOW_ERRORS = true;
    const COOKIE_USER = 'user';
    const COOKIE_DEFAULT_EXPIRY = 3600 * 24 * 30; 

    public static function get($key) {
        $const = __CLASS__ . '::' . $key;
        if (defined($const)) {
            return constant($const);
        }
        return null;
    }
}