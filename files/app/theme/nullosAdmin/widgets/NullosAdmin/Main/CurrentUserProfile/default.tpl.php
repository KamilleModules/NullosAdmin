<?php


?>

<div class="col-md-6 col-xs-12 widget_tally_box">
    <div class="x_panel fixed_height_390">
        <div class="x_content">

            <div class="flex">
                <ul class="list-inline widget_profile_box">
                    <li>
                        <h3 style="margin-top: 2px;"><?php echo $v['pseudo']; ?></h3>
                    </li>
                    <li>
                        <img src="<?php echo htmlspecialchars($v['image']); ?>" alt="..."
                             class="img-circle profile_img">
                    </li>
                </ul>
            </div>



            <div class="flex">
                <?php if (isset($v['rights'])): ?>
                    <ul class="list-inline count2">
                        <?php foreach($v['rights'] as $item): ?>
                        <li>
                            <span><?php echo $item; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>