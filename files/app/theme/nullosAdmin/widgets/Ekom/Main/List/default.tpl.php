<?php


use Core\Services\A;
use Module\Ekom\Utils\Morphic\EkomMorphicAdminListRenderer;
use QuickPdo\Util\QuickPdoQueryBuilder;
use Theme\NullosTheme;

NullosTheme::useLib("datatable");


?>

<script>
    jqueryComponent.ready(function () {
        var jElement = $('.morphic-container-1');
        var o = new window.Morphic({
            'element': jElement
        });
    });
</script>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel morphic-container-1">
            <?php
            $config = $v['listConfig'];
            echo EkomMorphicAdminListRenderer::create()->renderByConfig($config, []);
            ?>
        </div>
    </div>
</div>
