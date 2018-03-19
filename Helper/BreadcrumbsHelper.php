<?php


namespace Module\NullosAdmin\Helper;

use Bat\UriTool;
use Core\Services\A;
use Module\NullosAdmin\Utils\N;

class BreadcrumbsHelper
{
    public static function getBreadCrumbsModel(array $bcItems)
    {
        foreach ($bcItems as $k => $bcItem) {
            if (false === array_key_exists("link", $bcItem)) {
                if (array_key_exists("route", $bcItem)) {
                    $bcItems[$k]['link'] = A::link($bcItem['route']);
                } else {
                    $bcItems[$k]['link'] = UriTool::uri(null, [], false);
                }
            }
        }
        return $bcItems;
    }
}