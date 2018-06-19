<?php


namespace Module\NullosAdmin\Authenticate\User;


use Core\Services\Hooks;
use Module\NullosAdmin\Api\Layer\NullosAdminUserLayer;
use Module\NullosAdmin\Session\NullosSession;
use QuickPdo\QuickPdo;

class NullosUserHelper
{


    public static function connectDbUser($email, $pass, &$errorType = 0)
    {
        $errorType = 0;
        $userInfo = NullosAdminUserLayer::getUserInfoByEmailPassword($email, $pass, $errorType);

        if (false !== $userInfo) {


            $rights = NullosAdminUserLayer::getRightsById($userInfo['id']);


            /**
             * Connect the user
             */
            $user = [
                "id" => $userInfo['id'],
                "login" => $userInfo['id'],
                "email" => $email,
                "avatar" => $userInfo['avatar'],
                "pseudo" => $userInfo['pseudo'],
                "rights" => $rights,
            ];
            Hooks::call("NullosAdmin_User_populateConnectedUser", $user);
            NullosSession::set("user", $user);


            /**
             * When the user connects, we update the last connexion date
             */
            QuickPdo::update("nul_user", [
                "date_last_connexion" => date("Y-m-d H:i:s"),
            ], [
                ["id", "=", $userInfo['id']],
            ]);

            return true;
        }
        return false;
    }
}
