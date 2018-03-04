<?php


use Bat\StringTool;
use Module\Ekom\Utils\Morphic\EkomMorphicAdminListRenderer;
use Module\NullosAdmin\SokoForm\Renderer\NullosBootstrapFormRenderer;
use Theme\NullosAdmin\Ekom\Back\Category\CategoryFancyTreeRenderer;
use Theme\NullosTheme;

NullosTheme::useLib("datatable");

$sections = $v['sections'];
$activeSection = $v['activeSection'];
$cssIds = [];

?>


<div class="col">
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-bars"></i> <?php echo $v['title']; ?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                                class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                    </ul>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <div class="col-xs-3">
                <!-- required for floating -->
                <!-- Nav tabs -->
                <ul class="nav nav-tabs tabs-left">
                    <?php foreach ($sections as $id => $item):
                        $sActive = ($id === $activeSection) ? 'active' : '';
                        ?>
                        <li class="<?php echo $sActive; ?>"><a
                                    href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo $item['label']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-xs-9">
                <!-- Tab panes -->
                <div class="tab-content mix-tab-content">

                    <!-- CONTENT -->
                    <?php $section = $v['content']; ?>

                    <?php if (array_key_exists("title", $section)): ?>
                        <p class="lead"><?php echo $section['title']; ?></p>
                    <?php endif; ?>
                    <?php if (array_key_exists("description", $section)): ?>
                        <p><?php echo $section['description']; ?></p>
                    <?php endif; ?>



                    <?php if (
                        array_key_exists("formLink", $section) ||
                        array_key_exists("listLink", $section)
                    ): ?>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 text-right">

                                <?php if (array_key_exists("formLink", $section)): ?>
                                    <a href="<?php echo htmlspecialchars($section['formLink']); ?>"
                                       class="btn btn-default"><i class="fa fa-plus"></i>
                                        <?php echo $section['formText']; ?>
                                    </a>
                                <?php endif; ?>
                                <?php if (array_key_exists("listLink", $section)): ?>
                                    <a href="<?php echo htmlspecialchars($section['listLink']); ?>"
                                       class="btn btn-default"><i class="fa fa-list"></i>
                                        <?php echo $section['listLinkText']; ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>




                    <?php if (array_key_exists("boundFormConfig", $section)):
                        $formSection = $section['boundFormConfig'];
                        ?>
                        <?php if (array_key_exists("showForm", $section) && true === $section['showForm']): ?>
                        <div class="x_panel">
                            <?php if (array_key_exists("title", $formSection)): ?>
                                <div class="x_title">
                                    <h2><i class="fa fa-bars"></i> <?php echo $formSection['title']; ?></h2>
                                    <!--                                    <ul class="nav navbar-right panel_toolbox">-->
                                    <!--                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>-->
                                    <!--                                        </li>-->
                                    <!--                                        <li class="dropdown">-->
                                    <!--                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"-->
                                    <!--                                               aria-expanded="false"><i-->
                                    <!--                                                        class="fa fa-wrench"></i></a>-->
                                    <!--                                            <ul class="dropdown-menu" role="menu">-->
                                    <!--                                                <li><a href="#">Settings 1</a>-->
                                    <!--                                                </li>-->
                                    <!--                                                <li><a href="#">Settings 2</a>-->
                                    <!--                                                </li>-->
                                    <!--                                            </ul>-->
                                    <!--                                        </li>-->
                                    <!--                                        <li><a class="close-link"><i class="fa fa-close"></i></a>-->
                                    <!--                                        </li>-->
                                    <!--                                    </ul>-->
                                    <div class="clearfix"></div>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="x_content">
                                        <br>
                                        <?php NullosBootstrapFormRenderer::displayForm($formSection["form"]); ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (array_key_exists("afterFormElements", $formSection)): ?>
                                <?php
                                $elements = $formSection['afterFormElements'];
                                ?>
                                <?php if ($elements): ?>
                                    <hr>
                                    <?php foreach ($elements as $element):
                                        $type = $element['type'];
                                        switch ($type):
                                            case 'list':
                                                $config = $element['listConfig'];
                                                $cssId = (array_key_exists("cssId", $config)) ? $config['cssId'] : StringTool::getUniqueCssId("list-");
                                                $cssIds[] = $cssId;
                                                ?>
                                                <?php if (array_key_exists("formLink", $config)): ?>

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                                                        <a href="<?php echo htmlspecialchars($config['formLink']); ?>"
                                                           class="btn btn-default"><i class="fa fa-plus"></i>
                                                            <?php echo $config['formText']; ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="x_panel morphic-container-2 morphic-table-container-small"
                                                             id="<?php echo $cssId; ?>">
                                                            <?php
                                                            echo EkomMorphicAdminListRenderer::create()->renderByConfig($config);
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php break; ?>
                                            <?php case "form"; ?>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="x_content">
                                                        <br>
                                                        <?php NullosBootstrapFormRenderer::displayForm($element["formConfig"]["form"]); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php break; ?>
                                        <?php endswitch; ?>

                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php endif; ?>


                    <!-- -------------------------------------------------- -->
                    <!-- LIST -->
                    <!-- -------------------------------------------------- -->
                    <?php if (array_key_exists("listConfig", $section)):

                        $config = $section['listConfig'];
                        $cssId = (array_key_exists("cssId", $config)) ? $config['cssId'] : StringTool::getUniqueCssId("list-");
                        $cssIds[] = $cssId;

                        ?>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel morphic-container-2 morphic-table-container-small"
                                     id="<?php echo $cssId; ?>">
                                    <?php
                                    echo EkomMorphicAdminListRenderer::create()->renderByConfig($config, []);
                                    ?>
                                </div>
                            </div>
                        </div>


                        <!-- -------------------------------------------------- -->
                        <!-- FORM -->
                        <!-- -------------------------------------------------- -->
                    <?php elseif (array_key_exists("formConfig", $section)): ?>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_content">
                                    <br>
                                    <?php NullosBootstrapFormRenderer::displayForm($section["formConfig"]["form"]); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>


                    <!-- -------------------------------------------------- -->
                    <!-- TREEVIEW -->
                    <!-- -------------------------------------------------- -->
                    <?php if (array_key_exists("treeViewConfig", $section)):
                        $config = $section['treeViewConfig'];
                        CategoryFancyTreeRenderer::create()->display();
                        ?>
                    <?php endif; ?>


                </div>
            </div>

            <div class="clearfix"></div>

        </div>
    </div>
</div>


<script>
    jqueryComponent.ready(function () {
        <?php foreach($cssIds as $cssId): ?>
        var jElement = $('#<?php echo $cssId; ?>');
        var o = new window.Morphic({
            'element': jElement
        });
        <?php endforeach; ?>
    });
</script>