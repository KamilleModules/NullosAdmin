<?php

namespace Module\NullosAdmin\Api\Layer;


use Bat\HashTool;
use QuickPdo\QuickPdo;

class NullosAdminUserLayer
{


    public static function getUserIdsByBadge(string $badge)
    {
        return QuickPdo::fetchAll("
select u.id 
from nul_user u 
inner join nul_user_has_badge h on h.user_id=u.id
inner join nul_badge b on b.id=h.badge_id
where b.name=:name
", [
            "name" => $badge,
        ], \PDO::FETCH_COLUMN);
    }


    /**
     *
     * @param $email
     * @param $pass
     * @param $errorType
     *      - 0: no error
     *      - 1: email not found in database
     *      - 2: wrong pass
     *      - 3: user not active
     *
     *
     * @return array|false
     *
     *
     *
     *
     */
    public static function getUserInfoByEmailPassword($email, $pass, &$errorType = 0)
    {
        $row = QuickPdo::fetch("select * from nul_user where email=:email", [
            'email' => $email,
        ]);
        if (false !== $row) {
            if (true === HashTool::passwordVerify($pass, $row['pass'])) {
                if (1 === (int)$row['active']) {
                    return $row;
                } else {
                    $errorType = 3;
                }
            } else {
                $errorType = 2;
            }
        } else {
            $errorType = 1;
        }
        return false;
    }


    public static function getRightsById($id)
    {
        $id = (int)$id;
        $ret = QuickPdo::fetchAll("
select b.name 
from nul_badge b
inner join nul_user_has_badge h on h.badge_id=b.id 
where h.user_id=$id
        ", [], \PDO::FETCH_COLUMN);

        return $ret;
    }
}