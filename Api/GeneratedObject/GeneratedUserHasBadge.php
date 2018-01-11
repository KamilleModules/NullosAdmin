<?php


namespace Module\NullosAdmin\Api\GeneratedObject;


use XiaoApi\Object\TableCrudObject;

/**
 * This file was generated by the DbObjectGenerator.
 * You should not edit it manually, otherwise you
 * might loose your edits on the next update.
 *
 * You are supposed to extend this object.
 */
class GeneratedUserHasBadge extends TableCrudObject
{

    public function __construct()
    {
        parent::__construct();
        $this->table = "nul_user_has_badge";
        $this->primaryKey = ['user_id', 'badge_id'];
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    protected function getCreateData(array $data)
    {
        $base = [
			'user_id' => 0,
			'badge_id' => 0,
		];
        $ret = array_replace($base, array_intersect_key($data, $base));



        return $ret;
    }


}