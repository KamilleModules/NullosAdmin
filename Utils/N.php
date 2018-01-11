<?php



namespace Module\NullosAdmin\Utils;


use Core\Services\A;
use Kamille\Architecture\ApplicationParameters\ApplicationParameters;

class N{

    public static function link($routeId, array $params = [], $absolute = false, $https = null)
    {
        return A::link($routeId, $params, $absolute, $https);
    }

    public static function defaultUserImage(){
        $prefixUri = "/theme/" . ApplicationParameters::get("theme");
        $imgPrefix = $prefixUri . "/production";
        return $imgPrefix . '/images/ninja-dab.png';
    }
}