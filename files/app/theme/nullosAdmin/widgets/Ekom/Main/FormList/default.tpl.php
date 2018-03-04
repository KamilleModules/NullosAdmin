<?php


use Bat\StringTool;
use Module\Ekom\Utils\Morphic\EkomMorphicAdminListRenderer;
use Module\NullosAdmin\SokoForm\Renderer\NullosBootstrapFormRenderer;
use Module\NullosAdmin\SokoForm\Renderer\NullosMorphicBootstrapFormRenderer;
use SokoForm\Form\SokoFormInterface;
use Theme\NullosAdmin\Ekom\Back\Button\ButtonStackRenderer;
use Theme\NullosTheme;


//--------------------------------------------
//
//--------------------------------------------
/**
 * @todo-ling: change fra to dynamic lang...
 */
$lang = 'fra';
NullosTheme::useLib("soko");
NullosTheme::useLib("jqueryUiDate", $lang);


//--------------------------------------------
//
//--------------------------------------------
$cssIds = [];


?>



<?php if (array_key_exists("formConfig", $v)):
    $conf = $v['formConfig'];
    /**
     * @var $form SokoFormInterface
     */
    $form = $conf["form"];
    $cssId = StringTool::getUniqueCssId("fl-");
    ?>


    <div class="row" id="<?php echo $cssId; ?>">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><?php echo $conf['title']; ?></h2>
                    <?php if (false): ?>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Settings 1</a>
                                    </li>
                                    <li><a href="#">Settings 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br>
                    <?php
                    NullosMorphicBootstrapFormRenderer::displayAll($conf); ?>
                </div>


                <?php if (array_key_exists("boundListConfig", $v)):
                    $boundListConfig = $v['boundListConfig'];
                    $tableCssId = StringTool::getUniqueCssId("boundlist-");
                    $cssIds[] = $tableCssId;
                    ?>


                    <?php if (array_key_exists("topActionButtons", $boundListConfig)):
                    $topActionButtons = $boundListConfig['topActionButtons'];
                    ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                            <?php foreach ($topActionButtons as $button): ?>
                                <a href="<?php echo htmlspecialchars($button['link']); ?>"
                                   class="btn btn-default"><i class="<?php echo $button['icon']; ?>"></i>
                                    <?php echo $button['text']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>


                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel morphic-container-2 morphic-table-container-small"
                                 id="<?php echo $tableCssId; ?>">
                                <?php
                                if (array_key_exists('buttons', $boundListConfig)) {
                                    ButtonStackRenderer::displayButtons($boundListConfig['buttons']);
                                }
                                ?>
                                <?php
                                echo EkomMorphicAdminListRenderer::create()->renderByConfig($boundListConfig, []);
                                ?>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        jqueryComponent.ready(function () {
            var jContext = $('#<?php echo $cssId; ?>');
            var errorRemoval = new SokoFormErrorRemovalTool({
                context: jContext
            });
            errorRemoval.refresh();
        });
    </script>

<?php endif; ?>

<!-- -------------------------------------------------- -->
<!-- LIST -->
<!-- -------------------------------------------------- -->
<?php if (array_key_exists("listConfig", $v)):
    $config = $v['listConfig'];

    $cssId = (array_key_exists("cssId", $config)) ? $config['cssId'] : StringTool::getUniqueCssId("list-");
    $cssIds[] = $cssId;
    ?>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel morphic-container-2 morphic-table-container-small"
                 id="<?php echo $cssId; ?>">
                <?php
                if (array_key_exists('buttons', $config)) {
                    ButtonStackRenderer::displayButtons($config['buttons']);
                }
                ?>

                <?php echo EkomMorphicAdminListRenderer::create()->renderByConfig($config, []);
                ?>
            </div>
        </div>
    </div>

<?php endif; ?>


<script>
    jqueryComponent.ready(function () {
        <?php foreach($cssIds as $cssId): ?>
        var jElement = $('#<?php echo $cssId; ?>');
        var o = new window.Morphic({
            'element': jElement
        });
        <?php endforeach; ?>
    });



    <?php if(array_key_exists("menuCurrentUri", $v)): ?>
    window.EkomNullosMenuCurrentUri = "<?php echo $v['menuCurrentUri']; ?>";
    <?php endif; ?>
</script>


