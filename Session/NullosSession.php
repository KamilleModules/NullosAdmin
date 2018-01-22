<?php


namespace Module\NullosAdmin\Session;


use Kamille\Utils\Session\KamilleSession;

class NullosSession extends KamilleSession
{
    protected static function getSessionName()
    {
        return "nullos";
    }

    public static function getUserValue($k, $default = null)
    {
        $user = self::get("user", []);
        if (array_key_exists($k, $user)) {
            return $user[$k];
        }
        return $default;
    }

    public static function setUserValue($k, $v)
    {
        $user = self::get("user", []);
        $user[$k] = $v;
        self::set("user", $user);
    }
}