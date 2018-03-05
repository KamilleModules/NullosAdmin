<?php


namespace Theme\NullosAdmin\Ekom\Back\Button;


class ButtonStackRenderer
{

    /**
     * @param array $buttons , array of button:
     *      - icon: string, fa fa-plus
     *      - text: string
     *      - link: string
     */
    public static function displayButtons(array $buttons)
    {
        if ($buttons): ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                    <?php foreach ($buttons as $button):
                        $icon = 'fa fa-plus';
                        if (array_key_exists("icon", $button)) {
                            $icon = $button['icon'];
                        }
                        ?>
                        <a href="<?php echo htmlspecialchars($button['link']); ?>"
                           class="btn btn-default"><i class="<?php echo $icon; ?>"></i>
                            <?php echo $button['text']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php
        endif;
    }
}