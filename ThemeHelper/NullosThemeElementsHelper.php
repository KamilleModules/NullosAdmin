<?php


namespace Module\NullosAdmin\ThemeHelper;


class NullosThemeElementsHelper
{


    public static function renderBlueLink($text, $href, $isExternal = true)
    {
        ob_start();
        ?>
    <a target="_blank" class="" style="white-space: nowrap; color: #498dcb"
       href="<?php echo htmlspecialchars($href); ?>">
        <i class="fa fa-plus"></i>
        <?php echo $text; ?>
        <?php if ($isExternal): ?>
        <i class="fa fa-external-link"></i></a>
    <?php endif; ?>
        <?php
        return ob_get_clean();
    }


    /**
     * possible types are:
     * - success    (green)
     * - info       (blue)
     * - warning    (orange)
     * - error      (red)
     *
     */
    public static function renderNotif($text, $type = "info")
    {
        ob_start();
        if ("danger" === $type) {
            $type = 'error';
        }
        ?>
        <div class="alert alert-<?php echo $type; ?> alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">×</span>
            </button>
            <?php echo $text; ?>
        </div>
        <?php
        return ob_get_clean();
    }


    /**
     * - size:
     *      - large
     *      - default
     *      - small
     *      - xsmall
     *
     */
    public static function renderButton($text, $href, $iconClass = false, $size = null)
    {
        ob_start();
        switch ($size) {
            case "large":
                $size = 'lg';
                break;
            case "small":
                $size = 'sm';
                break;
            case "xsmall":
                $size = 'xs';
                break;
            default:
                $size = '';
                break;
        }

        ?>
        <div class="btn-group" style="display: flex">
            <a href="<?php echo htmlspecialchars($href); ?>" class="btn btn-sm btn-default" type="button">
                <?php if ($iconClass): ?>
                    <i class="<?php echo $iconClass; ?>"></i>
                <?php endif; ?>
                <?php echo $text; ?>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }
}