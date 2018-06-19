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
        }


        return (int)QuickPdo::fetch("
select count(*) as count 
from nul_message
where
user_id=$userId 
and is_read=0 
", [], \PDO::FETCH_COLUMN);
    }


    public static function addMessageByGroupName(string $groupName, string $message, string $origin = null)
    {
        $userIds = NullosAdminUserLayer::getUserIdsByGroup($groupName);

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