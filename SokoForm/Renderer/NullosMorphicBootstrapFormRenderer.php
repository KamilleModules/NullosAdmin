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

        if (null === $cssId) {
            $cssId = StringTool::getUniqueCssId("form-");
        }
        $form = $conf['form'];
        parent::displayForm($form, $cssId, $conf);
        if (array_key_exists("formAfterElements", $conf)) {
            $els = $conf['formAfterElements'];
            foreach ($els as $el) {
                self::displayFormAfterElement($el);
            }
        }


        /**
         * Assuming we are on a form/list pattern,
         * when the user deletes the row we reload the page
         * and display just the list (i.e. we do not display the form because it's confusing for the user).
         *
         */
        $context = $conf['context'] ?? null;
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== ($pos = strpos($uri, '?'))) {
            $uri = substr($uri, 0, $pos);
        }
        if ($context) {
            unset($context['avatar']);
            $uri .= "?" . http_build_query($context);
        }

        ?>
        <script>
            jqueryComponent.ready(function () {
                window.Morphic.onDeleteAfter = function () {
                    //window.location.href = "<?php echo $uri; ?>";
                    window.location.href = location.href;
                };
            });
        </script>

        <?php
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
                <?php foreach ($links

                as $el):
                $link = $el['link'];
                $text = $el['text'];
                $disabled = false;
                if (array_key_exists("disabled", $el) && true === $el['disabled']) {
                    $disabled = true;
                }


                $htmlTag = "a";
                if (true === $disabled) {
                    $sClass = "disabled";
                    $htmlTag = "span";
                }


                ?>
                <<?php echo $htmlTag; ?> href="<?php echo htmlspecialchars($link); ?>"
                class="btn btn-primary <?php echo $sClass; ?>"><?php echo $text; ?></<?php echo $htmlTag; ?>>
            <?php endforeach; ?>
        </div>
        </div>
        <?php
    }
}




