<?php


namespace Module\NullosAdmin\SokoForm\Renderer;


class NullosBootstrapNotificationRenderer
{

    public static function create()
    {
        return new static();
    }


    public function render(array $notif)
    {

        $type = $notif['type'];
        if ('error' === $type) {
            $type = "danger";
        }
        ?>
        <div class="alert alert-<?php echo $type; ?>" role="alert">
            <?php if (array_key_exists("title", $notif) && !empty($notif['title'])): ?>
                <h4 class="alert-heading"><?php echo $notif['title']; ?></h4>
            <?php endif; ?>
            <?php echo $notif['msg']; ?>
        </div>
        <?php
    }
}