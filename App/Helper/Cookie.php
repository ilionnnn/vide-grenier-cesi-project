<?php

namespace App\Helper;


class Cookie
{
    public static function put($key, $value, $expiry)
    {
        return setcookie($key, $value, time() + $expiry, "/");
    }

    public static function get($key)
    {
        return $_COOKIE[$key] ?? null;
    }

    public static function exists($key)
    {
        return isset($_COOKIE[$key]);
    }

    public static function delete($key)
    {
        return setcookie($key, '', time() - 3600, "/");
    }
}
