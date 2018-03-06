<?php


namespace Module\NullosAdmin\Helper;


class NullosGuiEnvironment
{

    private static $notifications = [];

    public static function addNotification($message, $type, $title = null)
    {
        self::$notifications[] = [
            "message" => $message,
            "type" => $type,
            "title" => $title,
        ];
    }

    public static function getNotifications()
    {
        return self::$notifications;
    }

}