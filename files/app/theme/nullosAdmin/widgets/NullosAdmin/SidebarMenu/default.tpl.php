<?php


use ModelRenderers\AdminSidebarMenu\BootstrapAdminSidebarMenuRenderer;


?>

<br/>
<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <?php
    echo BootstrapAdminSidebarMenuRenderer::create()->setModel($v['sidebarMenuModel'])->render();
    ?>
</div>
<!-- /sidebar menu -->
