<?php


namespace Module\NullosAdmin\Models\LastMessages;


use Core\Services\A;
use Module\NullosAdmin\Api\Layer\MessageLayer;

class LastMessagesModel
{

    public static function getLastMessagesModel()
    {
        $userMessages = MessageLayer::getLastMessages();
        $nbMessages = MessageLayer::countNonReadMessages();

        $baseLink = A::link("NullosAdmin_messageList");
        $localys = A::localys();


        $messages = [];
        foreach ($userMessages as $userMessage) {

            $timeElapsed = $localys->getTimeElapsedString($userMessage['date_added'], ['full' => false]);
            $messages[] = [
                "link" => $baseLink . "?id=" . $userMessage['id'] . "&form",
                "title" => $userMessage['pseudo'],
                "image" => $userMessage['avatar'],
                "aux" => $timeElapsed,
                "message" => $userMessage['message'],
            ];
        }


        $ret = [
            'nbMessages' => $nbMessages,
            'badgeColor' => 'red',
            'showAllMessagesLink' => true,
            'allMessagesText' => "Voir tous les messages",
            'allMessagesLink' => $baseLink,
            "messages" => $messages,

        ];


        return $ret;
    }
}