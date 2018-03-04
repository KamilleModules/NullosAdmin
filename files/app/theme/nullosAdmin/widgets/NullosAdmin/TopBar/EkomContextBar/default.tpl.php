<?php


$currencyIsSet = $v['currencyIsSet'];
$langIsSet = $v['langIsSet'];
$shopIsSet = $v['shopIsSet'];


$sClassCurrency = (true === $currencyIsSet) ? "default" : "pulse-color";
$sClassLang = (true === $langIsSet) ? "default" : "pulse-color";
$sClassShop = (true === $shopIsSet) ? "default" : "pulse-color";


$shopHost = $v['shopHost'];
$currencyIsoCode = $v['currencyIsoCode'];
$langIsoCode = $v['langIsoCode'];

//az($_SESSION);

?>
<li>
    <div class="btn-group" role="group" style="margin-top: 17px;">
        <div class="btn-group btn-group-xs" role="group">
            <button type="button" class="btn btn-<?php echo $sClassShop; ?> dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                <?php if (null !== $shopHost): ?>
                    Shop <?php echo $shopHost; ?>
                <?php else: ?>
                    Shop: choisissez
                <?php endif; ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php foreach ($v['shops'] as $item): ?>
                    <li><a href="#"
                           class="bionic-btn"
                           data-action="back.selectShopId"
                           data-param-shop_id="<?php echo $item['id']; ?>"
                           data-directive-reload="1"
                        ><?php echo $item['host']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="btn-group btn-group-xs" role="group">
            <button type="button" class="btn btn-<?php echo $sClassCurrency; ?> dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                <?php if ($currencyIsoCode): ?>
                    Currency <?php echo $currencyIsoCode; ?>
                <?php else: ?>
                    <?php if (is_string($v['currencies'])): ?>
                        <?php echo $v['currencies']; ?>
                    <?php else: ?>
                        Currency: choisissez
                    <?php endif; ?>
                <?php endif; ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php if (is_array($v['currencies'])): ?>
                    <?php foreach ($v['currencies'] as $item): ?>
                        <li><a href="#"
                               class="bionic-btn"
                               data-action="back.selectCurrencyId"
                               data-param-currency_id="<?php echo $item['id']; ?>"
                               data-directive-reload="1"
                            ><?php echo $item['iso_code']; ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div class="btn-group btn-group-xs" role="group">
            <button type="button" class="btn btn-<?php echo $sClassLang; ?> dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                <?php if (null !== $langIsoCode): ?>
                    Lang <?php echo $langIsoCode; ?>
                <?php else: ?>
                    Lang: choisissez
                <?php endif; ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php foreach ($v['languages'] as $item): ?>
                    <li><a href="#"
                           class="bionic-btn"
                           data-action="back.selectLangId"
                           data-param-lang_id="<?php echo $item['id']; ?>"
                           data-directive-reload="1"
                        ><?php echo $item['iso_code']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</li>