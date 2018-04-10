<?php


namespace Module\NullosAdmin\Echarts;


use EchartsWrapper\EchartsWrapper;
use Theme\NullosTheme;


class NullosEchartsWrapper extends EchartsWrapper
{
    protected static function jsTop()
    {
        ?>
        jqueryComponent.ready(function () {
        <?php
    }

    protected static function jsBottom()
    {
        ?>
        });
        <?php
    }

    protected static function init() // override me
    {
        NullosTheme::useLib("echarts");
    }


    protected static function printEmptyMessage($msg, $emptyDataMessageStyle)
    {

        $class = "echarts-empty-message-default";
        if (null === $emptyDataMessageStyle) {

        }


        echo <<<EEE
<div class="$class">$msg</div>
EEE;

    }

}