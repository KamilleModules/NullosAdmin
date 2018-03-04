<?php


use Bat\StringTool;
use Core\Services\A;
use Module\Ekom\Utils\Morphic\EkomMorphicAdminListRenderer;
use Module\NullosAdmin\SokoForm\Renderer\NullosBootstrapFormRenderer;
use QuickPdo\Util\QuickPdoQueryBuilder;
use SokoForm\Form\SokoForm;
use SokoForm\Form\SokoFormInterface;
use SokoForm\NotificationRenderer\SokoNotificationRenderer;
use Theme\NullosTheme;

NullosTheme::useLib("soko");

$conf = $v['formConfig'];
/**
 * @var $form SokoFormInterface
 */
$form = $conf["form"];
$cssId = StringTool::getUniqueCssId("ke");

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
                <?php NullosBootstrapFormRenderer::displayForm($form); ?>
            </div>
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
