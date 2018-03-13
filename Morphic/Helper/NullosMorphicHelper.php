<?php


namespace Module\NullosAdmin\Morphic\Helper;


use Module\NullosAdmin\Exception\NullosException;

class NullosMorphicHelper
{
    public static function getStandardColTransformer($name)
    {
        switch ($name) {
            case "active":
                return function ($value, array $row) {
                    if (1 === (int)$value) {
                        return '<i style="color: #02c302" class="fa fa-check"></i>';
                    } else {
                        return '<i style="color: #c30118" class="fa fa-remove"></i>';
                    }
                };
                break;
            default:
                throw new NullosException("Unknown col transformer with name $name");
                break;
        }
    }
}