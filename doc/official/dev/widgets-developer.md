Créer des Widgets 
========================
2018-04-09



Nullos propose quelques outils pour créer le contenu des widgets.



XPanel
------------

Un XPanel est un panneau blanc, avec un titre optionnel, ainsi que des boutons d'action optionnels également.

C'est la base de quasiment tous les widgets dans nullos (c'est à dire que la plupart des widgets sont
intégrés à l'intérieur d'un xPanel).



Voici un exemple montrant comment créer un XPanel avec un texte arbitraire


```php

<?php NullosXPanelHelper::xpanelTop([
    'title' => $v['title'],
]); ?>

Contenu arbitraire...

<?php NullosXPanelHelper::xpanelBottom(); ?>


```




MorphicList
------------

Une manière simple d'afficher une liste morphic est ceci:

```php
/**
* Pour rappel, voici un snippet qui permet de générer la configuration de liste (DEPUIS LE CONTRÔLEUR).
*      
* $listConfig = A::getMorphicListConfig("Ekom", "back/stats/product_details", $context);
* 
**/



<?php NullosMorphicListRenderer::renderListWidgetByListConfig($v['list']); ?>
```



<img src="image/widget-morphic.png" alt="Drawing"/>




FlatTable
------------

```php
<?php NullosFlatTableHelper::printTable($flatTable); ?>
```

Pour rappel, exemple de flatTable (normalement produit depuis le contrôleur):

```php
$flatTable = [
    [
        'user_country',
        "Pays d'origine",
        $row['user_country'],
    ],
    [
        'newsletter',
        "Inscrit à la newsletter",
        (bool)($row['newsletter']),
    ],
    // ....
];
```

<img src="image/widget-flat-table.png" alt="Drawing"/>


VeryFlatTable
------------

```php
<?php NullosFlatTableHelper::printVeryFlatTable($veryFlatTable); ?>
```

Pour rappel, exemple de veryFlatTable (normalement produit depuis le contrôleur):

```php
$veryFlatTable = [
    "Pays d'origine" => $row['user_country'],
    "Inscrit à la newsletter" => (bool)$row['newsletter'],
];
```

<img src="image/widget-flat-table.png" alt="Drawing"/>


InfoTable
------------

```php
<?php NullosInfoTableRenderer::render($infoTable); ?>
```

Pour rappel, exemple de veryFlatTable (normalement produit depuis le contrôleur):

```php
$infoTable = [
    'headers' => [
        "Id",
        "Date",
        "Paiement",
        "État",
        "Produits",
        "Total payé",
        "", // actions
    ],
    'rows' => $rows,
    'colTransformers' => [
        'amount' => NullosMorphicHelper::getStandardColTransformer("Ekom.price"),
    ],
];
```

<img src="image/widget-info-table.png" alt="Drawing"/>




Alert
------------

```php
<?php  
$alert = $v['alert'] ?? null;
?>


<?php if ($alert): ?>
    <?php NullosAlertHelper::displayAlert($alert); ?>
<?php endif; ?>    
```


Pour rappel, voici un exemple de création d'une alerte depuis un contrôleur:

```php
            $conf['alert'] = [
                "title" => "Nombre d'achats comparé au nombre de vues",
                "text" => <<<EEE
La liste ci-dessous indique le rapport, pour chaque produit, entre le nombre d'achats de ce produit et le nombre 
de visites de ce produit, pour la période donnée. 
EEE
,
                "icon" => "fa fa-exclamation-circle",
                "type" => "warning",
            ];
```


<img src="image/nullos-alert.png" alt="Drawing"/>






Form 
------------

Nullos utilise le système de formulaire [Soko](https://github.com/lingtalfi/SokoForm).


Il y a deux méthodes principales pour afficher un formulaire:

- affichage d'une instance SokoFormInterface
- affichage d'un widget morphic form


###### Affichage d'une instance de SokoFormInterface

```php
<?php NullosMorphicBootstrapFormRenderer::displayForm($v['form']); ?>
```


###### Affichage d'un widget morphic form

```php
<?php NullosMorphicBootstrapFormRenderer::displayAll($v['formConf']); ?>
```

La configuration permet d'afficher d'autres éléments gravitant autour du formulaire de base, 
comme par exemple les `formAfterElements` (voir le code source pour plus d'infos).




<img src="image/morphic-form.png" alt="Drawing"/>



Stats Gauge
--------------

```php
<table class="flat_table">
    <tr>
        <td>Nombre de commandes sans/avec erreurs</td>
        <td class="text-right"><?php NullosStatsWidgetHelper::printGauge($percent_successful_order); ?></td>
    </tr>
    <tr>
        <td>Nombre de commandes avec/sans réductions</td>
        <td class="text-right"><?php NullosStatsWidgetHelper::printGauge($percent_order_with_discount); ?></td>
    </tr>
</table>
```

<img src="image/nullos-stats-gauge.png" alt="Drawing"/>



Stats Doughnut
--------------

```php
<table class="flat_table">
    <tr>
        <td>Méthodes de paiement</td>
        <td class="text-right">
            <?php NullosStatsWidgetHelper::printDoughnut($doughnutPayments); ?>
        </td>
    </tr>
</table>
```


Pour information, voici ce à quoi ressemble `$doughnutPayments`:

```php
array(4) {
  [""] => array(2) {
    ["label"] => string(7) "Inconnu"
    ["value"] => string(5) "36.11"
  }
  ["credit_card_wallet"] => array(2) {
    ["label"] => string(16) "Carte de crédit"
    ["value"] => string(5) "27.78"
  }
  ["credit_card_wallet1x"] => array(2) {
    ["label"] => string(20) "Carte de crédit: 1x"
    ["value"] => string(5) "22.22"
  }
  ["credit_card_wallet4x"] => array(2) {
    ["label"] => string(20) "Carte de crédit: 4x"
    ["value"] => string(5) "13.89"
  }
}
```



<img src="image/nullos-stats-doughnut.png" alt="Drawing"/>




Stats ProgressBar
--------------

```php
<div style="padding-top: 20px;" class="progressbar-smaller">
    <?php NullosStatsWidgetHelper::printProgressBar($barItems); ?>
</div>
```


Pour information, voici ce à quoi ressemble `$barItems`:

```php
array(13) {
  [0] => array(3) {
    ["label"] => string(15) "Tableau de bord"
    ["percentage"] => float(1.06)
    ["value"] => string(5) "3/283"
  }
  [1] => array(3) {
    ["label"] => string(6) "Compte"
    ["percentage"] => float(1.77)
    ["value"] => string(5) "5/283"
  }
  [2] => array(3) {
    ["label"] => string(12) "Coordonnées"
    ["percentage"] => float(4.59)
    ["value"] => string(6) "13/283"
  }
  // ...
  [12] => array(3) {
    ["label"] => string(23) "Mes articles consultés"
    ["percentage"] => float(8.48)
    ["value"] => string(6) "24/283"
  }
}

```



<img src="image/nullos-stats-progress-bar.png" alt="Drawing"/>




Echarts BasicLineChart
--------------

```php
<?php NullosXPanelHelper::xpanelTop([
    'title' => $v['title'],
]); ?>

<?php NullosEchartsWrapper::displayBasicLineChart($chart1); ?>

<?php NullosXPanelHelper::xpanelBottom(); ?>
```


Pour information, voici ce à quoi ressemble `$chart1`:

```php
array(2) {
  ["title"] => string(45) "Nombre de commandes et quantités commandées"
  ["series"] => array(2) {
    ["Nombre de commandes"] => array(365) {
      [0] => array(2) {
        [0] => string(10) "2017-01-01"
        [1] => int(0)
      }
      // ...
      [335] => array(2) {
        [0] => string(10) "2017-12-02"
        [1] => string(1) "2"
      }
      // ...
      [364] => array(2) {
        [0] => string(10) "2017-12-31"
        [1] => int(0)
      }
    }
    ["Quantité commandée"] => array(365) {
      [0] => array(2) {
        [0] => string(10) "2017-01-01"
        [1] => int(0)
      }
      // ...
      [335] => array(2) {
        [0] => string(10) "2017-12-02"
        [1] => string(2) "13"
      }
      // ...
      [364] => array(2) {
        [0] => string(10) "2017-12-31"
        [1] => int(0)
      }
    }
  }
}

```




<img src="image/echarts-basic-line-chart.png" alt="Drawing"/>






Echarts Pie
--------------

```php
<?php NullosEchartsWrapper::displayPie($chart3); ?>
```


Pour information, voici ce à quoi ressemble `$chart3`:

```php
array(4) {
  ["title"] => string(35) "Distribution des états de commande"
  ["labelColor"] => string(5) "black"
  ["data"] => array(4) {
    ["En attente de paiement"] => string(1) "7"
    ["En cours de préparation"] => string(1) "1"
    ["Erreur de livraison"] => string(1) "1"
    ["Paiement accepté"] => string(1) "5"
  }
  ["dataColors"] => array(12) {
    ["En attente de paiement"] => string(7) "#ffff00"
    ["Paiement accepté"] => string(7) "#80FF00"
    ["Paiement vérifié"] => string(7) "#00FF00"
    ["En cours de préparation"] => string(7) "#008080"
    ["Commande envoyée"] => string(7) "#29ABE2"
    ["Commande arrivée"] => string(7) "#0000FF"
    ["Erreur de paiement"] => string(7) "#FF0000"
    ["Erreur de préparation de commande"] => string(7) "#F15A24"
    ["Erreur de livraison"] => string(7) "#F7931E"
    ["Erreur de réception"] => string(7) "#FBB03B"
    ["Commande annulée"] => string(7) "#9E005D"
    ["Commande remboursée"] => string(7) "#662D91"
  }
}

```

> Si vous ne spécifiez pas de couleurs, les couleurs seront choisies automatiquement


<img src="image/echarts-pie.png" alt="Drawing"/>





