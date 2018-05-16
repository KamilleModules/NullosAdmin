Appeler des Widgets
========================
2018-03-27



Les widgets suivants vous permettront de créer plus rapidement certains éléments du back office:


- [FlatTable](#flattable)
- [VeryFlatTable](#veryflattable)
- [InfoTable](#infotable)
- [MorphicList](#morphiclist)
- [MorphicItem](#morphicitem) 
- [Error](#error) 



> Les widgets NullosAdmin sont situés en général dans le dossier `class-themes/NullosAdmin`.


FlatTable
======================

Affiche une liste **finie** de données qui peut être manipulée facilement par plusieurs modules simultanément.

<img src="image/widget-flat-table.png" alt="Drawing"/>

Mise en place:

```php

$table = [
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


$claws
    ->setWidget("maincontent_right.appInfo", ClawsWidget::create()
        ->setTemplate("NullosAdmin/Main/FlatTable/default")
        ->setConf([
            'title' => "Détails du compte",
            'flatTable' => $table,
        ])
    );
```

> Pour manipuler ce type de liste simplement, on peut utiliser l'outil [NumericKeyArray](https://github.com/lingtalfi/NumericKeyArray)



VeryFlatTable
======================

Affiche une liste **finie** de données.
Contrairement à la flatTable, la veryFlatTable **NE PEUT PAS** être manipulée facilement 
par plusieurs modules simultanément.

En contre-partie, elle est beaucoup plus simple à mettre en place.


<img src="image/widget-flat-table.png" alt="Drawing"/>

Mise en place:

```php

$table = [
    "Pays d'origine" => $row['user_country'],
    "Inscrit à la newsletter" => (bool)$row['newsletter'],
];


$claws
    ->setWidget("maincontent_right.appInfo", ClawsWidget::create()
        ->setTemplate("NullosAdmin/Main/VeryFlatTable/default")
        ->setConf([
            'title' => "Détails du compte",
            'flatTable' => $table,
        ])
    );
```






InfoTable
======================

Affiche une liste **finie** de données présentée sous forme de tableau.

Ce tableau ne contient pas de tri, ni de filtre de recherche, ni de pagination,
c'est un simple tableau d'affichage de données.


<img src="image/widget-info-table.png" alt="Drawing"/>

> Cette liste est basée sur le modèle [InfoTable](https://github.com/lingtalfi/Models/blob/master/InfoTable/InfoTableModel.php)


Mise en place:

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


$claws->setWidget("maincontent_left.lastOrders", ClawsWidget::create()
    ->setTemplate("NullosAdmin/Main/InfoTable/default")
    ->setConf([
        'title' => "Les 5 dernières commandes",
        'buttonLink' => [
            'label' => "Voir toutes les commandes",
            'link' => E::link("Ekom_Orders_Order_List") . "?user_id=$userId",
        ],
        'infoTable' => $infoTable,
    ])
)
```

> Vous pouvez utiliser les mêmes colTransformers de nullos (voir la page ["morphic tips"](dev/morphic-tips.md#tips-pour-morphic) pour plus de détails)






MorphicList
======================

Affiche une liste morphic.

<img src="image/widget-morphic.png" alt="Drawing"/>

> [Morphic](http://www.ling-docs.ovh/kamille/#/tools/morphic) est un outil proposé par le framework kamille.


Mise en place:

```php
->setWidget("maincontent_bottom.userOrders", ClawsWidget::create()
    ->setTemplate("NullosAdmin/Main/MorphicList/default")
    ->setConf([
        'model' => NullosMorphicModelHelper::getListModel("Ekom", "back/users/user_order"),
    ])
)
```





MorphicItem
======================

Affiche un élément morphic (liste et formulaire).

<img src="image/morphic-element.png" alt="Drawing"/>


La mise en place "standard" est un peu plus complexe, il faut:

- générer l'élément morphic (en utilisant le [générateur morphic](http://www.ling-docs.ovh/kamille/#/tools/morphic?id=le-g%c3%a9n%c3%a9rateur))
    - utilisez l'option multi_modules (`morphic -m`), de manière à ce que les éléments soient générés directement dans votre module.
    Note: si vous n'aimez pas la ligne de commandes, vous pouvez aussi utiliser le module ApplicationMorphicGenerator pour lancer le générateur depuis le backoffice de nullos,
     
- une fois l'élément généré, créez votre contrôleur et étendez l'objet généré que vous souhaitez


```php
<?php


namespace Controller\Ekom\Back\Users;

use Controller\Ekom\Back\Generated\EkUser\EkUserListController;

class UserListController extends EkUserListController
{
    public function __construct()
    {
        parent::__construct();
        $this->addConfigValues([
            'title' => "Clients",
            'route' => "Ekom_Users_User_List",
            'form' => "back/users/user",
            'list' => "back/users/user",
        ]);
    }
}

```





Error
======================

Affiche un message d'erreur

<img src="image/nullos-error-widget.png" alt="Drawing"/>



```php
<?php

$this->getClaws()
    ->setWidget("maincontent.errorMessage", ClawsWidget::create()
        ->setTemplate("NullosAdmin/Main/Error/default")
        ->setConf([
            "title" => "Oops",
            "message" => "Veuillez renseigner votre ip dans l'url.",
        ])
    );

```




#### Astuce


Pour créer un contrôleur rapidement, vous pouvez utiliser l'outil en ligne de commandes `kamille`, qui permet
de créer des nouvelles pages (routes + contrôleur) en utilisant des modèles prédéfinis de votre choix.


> TODO: créer un modèle NullosAdmin pour le items morphic, et mettre la commande ci-dessous à jour

```bash
kamille newpage --module=Ekom --uri="/ekom/users/user/list" --route="Ekom_Users_User_List"  --controller="Controller\Ekom\Back\Users\UserListController:render"
```
