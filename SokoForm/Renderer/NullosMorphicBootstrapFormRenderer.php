<?php


namespace Module\NullosAdmin\SokoForm\Renderer;


use Bat\StringTool;
use SokoForm\Form\SokoFormInterface;
use SokoForm\Renderer\SokoFormRenderer;
use Theme\NullosTheme;

class NullosMorphicBootstrapFormRenderer extends NullosBootstrapFormRenderer
{


    public static function displayAll(array $conf, $cssId = null)
    {

        $form = $conf['form'];
        parent::displayForm($form, $cssId);

        if (array_key_exists("formAfterElements", $conf)) {
            $els = $conf['formAfterElements'];
            foreach ($els as $el) {
                self::displayFormAfterElement($el);
            }
        }

    }


    //--------------------------------------------
    //
    //--------------------------------------------
    protected static function displayFormAfterElement(array $el)
    {
        $type = $el['type'];
        switch ($type) {
            case "pivotLink":
                self::displayPivotLinks([$el]);
                break;
            case "pivotLinks":
                self::displayPivotLinks($el['links']);
                break;
            default:
                throw new \Exception("Unknown element with type=$type");
                break;
        }
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    protected static function displayPivotLinks(array $links)
    {
        ?>
        <hr>
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <div class="row">
                <?php foreach ($links as $el):
                    $link = $el['link'];
                    $text = $el['text'];
                    ?>
                    <a href="<?php echo htmlspecialchars($link); ?>" class="btn btn-primary"><?php echo $text; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}




