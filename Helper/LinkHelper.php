<?php


namespace Module\NullosAdmin\Helper;


use Module\NullosAdmin\Utils\N;

class LinkHelper
{


    public static function getSectionLink($routeId, $section, array $params = [])
    {
        $s = N::link($routeId) . "?tab=$section";
        foreach ($params as $k => $v) {
            $s .= "&$k=$v";
        }
        return $s;
    }
}