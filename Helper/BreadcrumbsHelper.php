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
            if (is_string($bcItem)) {
                $bcItem = [
                    "label" => $bcItem,
                ];
            }

            if (false === array_key_exists("link", $bcItem)) {
                if (array_key_exists("route", $bcItem)) {
                    $bcItem['link'] = A::link($bcItem['route']);
                } else {
                    $bcItem['link'] = UriTool::uri(null, [], false);
                }
            }
            $bcItems[$k] = $bcItem;
        }
        return $bcItems;
    }
}