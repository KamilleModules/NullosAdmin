<?php


use Module\NullosAdmin\ThemeHelper\ThemeHelper;

$notifications = $v['notifications'];

?>

<?php if ($notifications): ?>


    <div class="row">
        <div class="x_content">
            <?php foreach ($notifications as $item):
                $type = ThemeHelper::getBsType($item['type']);
                $title = (array_key_exists("title", $item)) ? $item['title'] : null;
                ?>
                <div class="alert alert-<?php echo $type; ?> alert-dismissible fade in" role="alert"
                     style="margin-bottom: 5px"
                >
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">×</span>
                    </button>
                    <?php if ($title): ?>
                        <strong><?php echo $title; ?></strong>
                    <?php endif; ?>
                    &nbsp;
                    <?php echo $item['msg']; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


<?php endif; ?>