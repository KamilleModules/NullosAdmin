Page Nullos 101
==========================
2018-04-09


Dans ce tutoriel, nous créons une page de test rapide avec nullos.



Dans cet exemple, nous utilisons cet environnement pour tester une liste morphic rapidement.




Tester une liste morphic rapidement
-------- 


```php
<?php


use Core\Services\A;
use Kamille\Architecture\ApplicationParameters\ApplicationParameters;
use Module\NullosAdmin\ThemeHelper\NullosThemeElementsHelper;
use Theme\NullosAdmin\Morphic\MorphicList\NullosMorphicListRenderer;

// using kamille framework here (https://github.com/lingtalfi/kamille)
require_once __DIR__ . "/../boot.php";
require_once __DIR__ . "/../init.php";


A::testInit();
ApplicationParameters::set("theme", "nullosAdmin");




NullosThemeElementsHelper::renderHtmlPage(function(){
    $moduleName = "Ekom";
    $viewId = "back/carriers/carrier";
    $context = [];
    $listConfig = A::getMorphicListConfig($moduleName, $viewId, $context);
    NullosMorphicListRenderer::renderListWidgetByListConfig($listConfig);
});

```

