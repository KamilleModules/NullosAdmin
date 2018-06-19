<?php


namespace Module\NullosAdmin\Api;

use Module\NullosAdmin\Api\Object\Badge;
use Module\NullosAdmin\Api\Object\Message;
use Module\NullosAdmin\Api\Object\User;
use Module\NullosAdmin\Api\Object\UserGroup;
use Module\NullosAdmin\Api\Object\UserHasBadge;

use XiaoApi\Api\XiaoApi;


/**
 * IMPORTANT NOTE:
 * -------------------
 * This class is generated by a script, don't edit it manually, or you might loose
 * you changes on the next update.
 *
 *
 * The goal of this class is to generate explicit method names, so that you can benefit
 * your IDE's auto-completion features.
 */
class GeneratedNullosAdminApi extends XiaoApi
{
    private static $inst;

    public static function inst()
    {
        if (null === self::$inst) {
            self::$inst = new static();
        }
        return self::$inst;
    }


    
    /**
     * @return Badge
     */
    public function badge()
    {
        return $this->getObject('badge');
    }
    /**
     * @return Message
     */
    public function message()
    {
        return $this->getObject('message');
    }
    /**
     * @return User
     */
    public function user()
    {
        return $this->getObject('user');
    }
    /**
     * @return UserGroup
     */
    public function userGroup()
    {
        return $this->getObject('userGroup');
    }
    /**
     * @return UserHasBadge
     */
    public function userHasBadge()
    {
        return $this->getObject('userHasBadge');
    }
}