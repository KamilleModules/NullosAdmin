<?php


namespace Module\NullosAdmin\Morphic\Helper;


use Core\Services\A;

class NullosMorphicModelHelper
{


    public static function getListModel(string $module, string $listIdentifier, array $options = [])
    {
        $context = $options['context'] ?? [];


        return [
            'menuCurrentRoute' => $options['menuCurrentRoute'] ?? null,
            'listConfig' => A::getMorphicListConfig($module, $listIdentifier, $context),
        ];
    }


}