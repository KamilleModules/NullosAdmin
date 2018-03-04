<div class="pagetop">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <?php
                $c = 0;
                $n = count($v['breadcrumbs']);
                foreach ($v['breadcrumbs'] as $item): ?>
                    <?php if (false && ($n - 1) === $c): ?>
                        <li class="active"><?php echo $item['label']; ?></li>
                    <?php else: ?>
                        <?php if (null !== $item['link']): ?>
                            <li><a href="<?php echo $item['link']; ?>"><?php echo $item['label']; ?></a></li>
                        <?php else: ?>
                            <li><?php echo $item['label']; ?></li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php
                    $c++;
                endforeach; ?>
            </ol>
        </div>
    </div>


    <div class="page-title" style="padding-top: 0px;">
        <div class="title_left">
            <h3><?php echo $v['title']; ?></h3>
        </div>

        <div class="title_right">
            <div class="title_right_container">
                <?php if (isset($v['buttons'])): ?>
                    <div class="btn-group" style="display: flex">
                        <?php foreach ($v['buttons'] as $item): ?>
                            <a href="<?php echo htmlspecialchars($item['link']); ?>"
                               class="btn btn-sm btn-default" type="button"><i
                                        class="<?php echo htmlspecialchars($item['icon']); ?>"></i> <?php echo $item['label']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($v['buttonsList']) && $v['buttonsList']):
                    $bl = $v['buttonsList'];
                    ?>
                    <div class="btn-group dropleft pagetop-buttonslist" style="display: flex">
                        <div class="dropdown dropleft">
                            <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo $bl['label']; ?>
                                <span class="caret"></span>
                            </button>
                            <ul id="menu2" class="dropdown-menu animated fadeInDown" role="menu"
                                aria-labelledby="drop5">
                                <?php foreach ($bl['items'] as $item): ?>
                                    <li role="presentation"><a role="menuitem" tabindex="-1"
                                                               href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo htmlspecialchars($item['label']); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>