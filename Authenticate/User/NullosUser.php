<?php


namespace Module\NullosAdmin\Authenticate\User;


use Bat\ArrayTool;
use Bat\SessionTool;
use Core\Services\Hooks;
use Module\NullosAdmin\Session\NullosSession;


/**
 * @todo-ling: add a timeout?
 * See Authenticate\SessionUser\SessionUser for a timeout example
 */
class NullosUser
{


    public static function isConnected()
    {
        return NullosSession::has("user");
    }

    public static function has($privilege)
    {
        $hasRight = false;
        Hooks::call("NullosAdmin_User_hasRight", $hasRight, $privilege);
        return $hasRight;
    }

    public static function getRights()
    {
        $user = NullosSession::get("user");
        if (is_array($user)) {
            if (array_key_exists("rights", $user)) {
                return $user['rights'];
            }
        }
        return [];
    }

    public static function get($k, $default = null)
    {
        return NullosSession::getUserValue($k, $default);
    }

    public static function set($k, $v)
    {
        return NullosSession::setUserValue($k, $v);
    }


    public static function disconnect()
    {
        NullosSession::remove("user");
    }
}
