<?php


use Module\Ekom\Utils\Morphic\EkomMorphicAdminListRenderer;
use Module\NullosAdmin\SokoForm\Renderer\NullosBootstrapFormRenderer;
use Theme\NullosTheme;

NullosTheme::useLib("datatable");

$sections = $v['sections'];
$activeSection = $v['activeSection'];

?>

<script>
    jqueryComponent.ready(function () {
        var jElement = $('#morphic-container-2');
        var o = new window.Morphic({
            'element': jElement
        });
    });
</script>


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
                    <?php foreach ($sections as $id => $section):
                        $sActive = ($id === $activeSection) ? 'active' : '';
                        ?>
                        <li class="<?php echo $sActive; ?>"><a href="#<?php echo $id; ?>"
                                                               data-toggle="tab"><?php echo $section['label']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-xs-9">
                <!-- Tab panes -->
                <div class="tab-content">
                    <?php


                    foreach ($sections as $id => $section):
                        $sActive = ($id === $activeSection) ? 'active' : '';
                        ?>
                        <div class="tab-pane <?php echo $sActive; ?>" id="<?php echo $id; ?>">
                            <?php if (array_key_exists("title", $section)): ?>
                                <p class="lead"><?php echo $section['title']; ?></p>
                            <?php endif; ?>
                            <?php if (array_key_exists("description", $section)): ?>
                                <p><?php echo $section['description']; ?></p>
                            <?php endif; ?>



                            <?php if (array_key_exists("boundFormConfig", $section)): ?>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="x_content">
                                            <br>
                                            <?php NullosBootstrapFormRenderer::displayForm($section["boundFormConfig"]["form"]); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>


                            <!-- -------------------------------------------------- -->
                            <!-- LIST -->
                            <!-- -------------------------------------------------- -->
                            <?php if (array_key_exists("listConfig", $section)): ?>

                                <?php if (array_key_exists("formLink", $section)): ?>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                                            <a href="<?php echo htmlspecialchars($section['formLink']); ?>"
                                               class="btn btn-default"><i class="fa fa-plus"></i>
                                                <?php echo $section['formText']; ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="x_panel morphic-container-2 morphic-table-container-small"
                                             id="morphic-container-2">
                                            <?php
                                            $config = $section['listConfig'];
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
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="clearfix"></div>

        </div>
    </div>
</div>