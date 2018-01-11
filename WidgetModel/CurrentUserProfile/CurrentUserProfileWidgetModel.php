<?php


namespace Module\NullosAdmin\WidgetModel\CurrentUserProfile;



use Module\NullosAdmin\Authenticate\User\NullosUser;
use Module\NullosAdmin\Utils\N;

class CurrentUserProfileWidgetModel{


    public static function getModel(){
        $defaultImage = N::defaultUserImage();
        return [
            "image" => NullosUser::get("avatar", $defaultImage),
            "pseudo" => NullosUser::get("pseudo", "user"),
            "rights" => NullosUser::getRights(),
        ];
    }

}