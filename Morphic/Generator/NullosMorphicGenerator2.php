<?php


namespace Module\NullosAdmin\Morphic\Generator;


use Kamille\Utils\Morphic\Generator2\MorphicGenerator2;

class NullosMorphicGenerator2 extends MorphicGenerator2
{


    protected function getTableRouteByTable($table)
    {
        $camel = $this->getCamelByTable($table);
        return "NullosAdmin_Ekom_Generated_" . $camel . "_List";
    }
}


