<?php


namespace Module\NullosAdmin\Echarts;


use EchartsWrapper\EchartsWrapper;


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

}