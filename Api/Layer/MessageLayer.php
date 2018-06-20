<?php


namespace Module\NullosAdmin\Api\Layer;


use Module\NullosAdmin\Api\Object\Message;
use Module\NullosAdmin\Authenticate\User\NullosUser;
use QuickPdo\QuickPdo;

class MessageLayer
{

    public static function updateMessageReadStatus(int $messageId, bool $isRead)
    {
        Message::getInst()->update([
            "is_read" => (int)$isRead,
        ], [
            "id" => $messageId,
        ]);
    }

    public static function countNonReadMessages(int $userId = null)
    {
        if (null === $userId) {
            $userId = NullosUser::getId();
            if (null === $userId) {
                return 0;
            }
        }


        return (int)QuickPdo::fetch("
select count(*) as count 
from nul_message
where
user_id=$userId 
and is_read=0 
", [], \PDO::FETCH_COLUMN);
    }


    /**
     * We recommend that origin is the name of the Module.
     * This allows us later to have more flexibility for filtering messages that one wants to receive.
     *
     * As for the badge naming, I'm not sure what to do, I'll experiment the following:
     *
     *
     *
     * - $Module.alert.$messageIdentifier:
     *              ex: Formaway.alert.new_training,
     *                  This class of messages
     *
     *
     */
    public static function addMessageByBadge(string $badgeName, string $message, string $origin = null)
    {
        $userIds = NullosAdminUserLayer::getUserIdsByBadge($badgeName);
        foreach ($userIds as $userId) {
            Message::getInst()->create([
                "user_id" => $userId,
                "date_added" => date("Y-m-d H:i:s"),
                "message" => $message,
                "origin" => (string)$origin,
                "is_read" => 0,
            ]);
        }
    }


    public static function getLastMessages(int $userId = null, array $options = [])
    {

        $nbMessages = $options['nbMessages'] ?? 5;


        if (null === $userId) {
            $userId = NullosUser::getId();
            if (null === $userId) {
                return [];
            }
        }

        return QuickPdo::fetchAll("
select
m.*, 
u.avatar,
u.pseudo
from nul_message m 
inner join nul_user u on u.id=m.user_id
where u.id=$userId
and m.is_read=0
order by date_added desc 
limit 0,$nbMessages
");
    }


}