Tips pour morphic
========================
2018-03-13



Rajouter un icône actif/inacf dans une liste
---------------

```php
'colTransformers' => [
    'active' => NullosMorphicHelper::getStandardColTransformer("active"),
],
```


<img src="image/morphic-active-inactive.png" alt="Drawing"/>


Transformer une url en image
---------------

```php
'colTransformers' => [
    'photo' => NullosMorphicHelper::getStandardColTransformer("image"),
    'photo2' => NullosMorphicHelper::getStandardColTransformer("image", ['width' => 120]),
],
```


<img src="image/morphic-image.png" alt="Drawing"/>



Transformer une url en media
---------------

```php
'colTransformers' => [
    'imageOrVideo' => NullosMorphicHelper::getStandardColTransformer("media"),
    'imageOrVideo' => NullosMorphicHelper::getStandardColTransformer("media", ['width' => 120]),
],
```


<img src="image/morphic-image.png" alt="Drawing"/>



Transformer un champ en utilisant une fonction custom
---------------

```php
'colTransformers' => [
    'label' => function ($value, array $row) {
        return $value . " ( " . $row['name'] . " )";
    },
],
```


<img src="image/morphic-image.png" alt="Drawing"/>




Transformer un code hexadécimal en couleur dans une liste
---------------

```php
'colTransformers' => [
    'color' => NullosMorphicHelper::getStandardColTransformer("color"),
],
```

<img src="image/morphic-list-color.png" alt="Drawing"/>



Tronquer un texte trop long dans une liste
---------------

```php
'colTransformers' => [
    'error_messages' => NullosMorphicHelper::getStandardColTransformer("toolong"),
],
```

<img src="image/morphic-toolong.png" alt="Drawing"/>




Désérialiser un texte sérialisé dans une liste
---------------

```php
'colTransformers' => [
    'shop_info' => NullosMorphicHelper::getStandardColTransformer("unserialize"),
],
```

<img src="image/morphic-unserialize.png" alt="Drawing"/>


Entourer le texte avec un oreiller de couleur dans une liste
---------------

```php
'colTransformers' => [
    'amount' => NullosMorphicHelper::getStandardColTransformer("pill"),
],
```

Pour changer la couleur, utiliser l'option class, qui peut prendre une des valeurs suivantes:

- default (gris)
- primary (bleu foncé)
- success (vert), valeur par défaut
- info (bleu clair)
- warning (orange)
- danger (rouge)

```php
'colTransformers' => [
    'amount' => NullosMorphicHelper::getStandardColTransformer("pill", ["class" => "success"]),
],
```

<img src="image/morphic-pill.png" alt="Drawing"/>



Whitespace: nowrap pour un élément de liste
---------------

```php
'colTransformers' => [
    'label' => NullosMorphicHelper::getStandardColTransformer("nowrap"),
],
```


<img src="image/morphic-nowrap.png" alt="Drawing"/>



Des dates propres 
---------------

Ce transformateur filtre les dates `"0000-00-00 00:00:00"`.

```php
'colTransformers' => [
    'label' => NullosMorphicHelper::getStandardColTransformer("datetime"),
],
```


Transformer une note en système d'étoiles 
---------------



```php
'colTransformers' => [
    'rating' => NullosMorphicHelper::getStandardColTransformer("rating"),
    'rating' => NullosMorphicHelper::getStandardColTransformer("rating", [
        'maxValue' => 100, 
        'maxNbStars' => 5, 
        'class' => "nullos-morphic-rating",  
    ]),
],
```

<img src="image/morphic-rating.png" alt="Drawing"/>



Ajouter un menu dropdown 
---------------



```php


$productLinkFmt = A::link("Ekom_Catalog_Product_Form") . "?form=1&t=products&t2=product&product_type=%s&id=%s&product_id=%s";
$commentLinkFmt = A::link("Ekom_Catalog_ProductCommentList") . "?form&id=%s";       
        
        
'colTransformers' => [
    'action' => NullosMorphicHelper::getStandardColTransformer("dropdown", [
        'callback' => function ($value, array $row) use ($productLinkFmt, $commentLinkFmt) {

            $isActive = (bool)$row['active'];
            $word = (true === $isActive) ? "invisible" : "visible";

            return [
                "label" => "Actions",
                "openingSide" => "left", // left|right
                "items" => [
                    [
                        "label" => "Rendre $word sur le site",
                        "link" => "#",
                        "class" => "bionic-btn",
                        "attributes" => [
                            "data-action" => "ecp:Ekom:back.updateProductCommentActive",
                            "data-param-id" => $row['id'],
                            "data-param-is_active" => (int)!$isActive,
                            "data-directive-reload" => 1,
                        ],
                    ],
                    [
                        "label" => "Modifier le produit",
                        "link" => sprintf($productLinkFmt, $row['product_type_id'], $row['product_id'], $row['card_id']),
                    ],
                    [
                        "label" => "Modifier le commentaire",
                        "link" => sprintf($commentLinkFmt, $row['id']),
                    ],
                ],
            ];
        }
    ]),
],
```

<img src="image/morphic-dropdown.png" alt="Drawing"/>








Ajouter un filtre de type "liste de valeurs"
---------------

```php
"searchColumnLists" => [
    "new_client" => [
        '1' => 'Oui',
        '0' => 'Non',
    ],
],
```

Ou, plus standardisé:

```php
"searchColumnLists" => [
    "new_client" => NullosMorphicHelper::getStandardSearchList("active"),
],
```


<img src="image/morphic-filter-list.png" alt="Drawing"/>



Ajouter un filtre de type "date"
---------------

```php
    "realColumnMap" => [
        // ...
        'date_low' => "h.date",
        'date_high' => "h.date",
    ],
    "searchColumnDates" => [
        "date" => [
            'date_low',
            'date_high',
        ],
    ],
    "operators" => [
        "date_low" => '>=',
        "date_high" => '<=',
    ],
```


<img src="image/morphic-filter-date.png" alt="Drawing"/>



Filtres pre/post
---------------

Les filtres pre/post sont essentiellement utilisés pour les valeurs sérialisées.
Le filtre de type "pre" intervient lorsque la donnée sort de la base de données et va vers le formulaire (on a alors besoin
de désérialiser), tandis que le filtre de type "post" est utilisé lorsque le formulaire est posté, et juste avant
d'insérer les données dans la base de données (on doit alors sérialiser les données).


Les filtres pre/post se placent respectivement au niveau des propriétés `feed` et `process` proposées par le fichier
de configuration de formulaire morphic.




```php
    'feed' => MorphicHelper::getFeedFunction("kamille.ek_coupon", function (SokoFormInterface $form) {
        if (SessionTool::pickupFlag("form-generated-ek_coupon")) {
            $form->addNotification("Le coupon a bien été ajouté", "success");
        }
    }, [], [
        'filters' => [ // pre-filter
            "unserializeArray" => [
                "cond_country_id",
            ],
        ],
    ]),
    'process' => function ($fData, SokoFormInterface $form) use ($isUpdate, $ric, $id) {


        MorphicHelper::applyPostFilters($fData, [ // post filter, ici, les valeurs NULL restent NULL, seuls les arrays sont sérialisés
            "serializeIfArray" => [
                'cond_country_id',
            ],
        ]);

        // ... update/insert in the database
        return false;
    },
```



Ajouter une action de ligne
---------------

```php
// $linkProductFmt = A::link("Ekom_Catalog_Product_Form") . '?product_id=';
"formRouteExtraActions" => [
    [
        "name" => "updateProduct",
        "label" => "Modifier le produit",
        "icon" => "fa fa-book",
        "link" => function (array $row) use ($linkProductFmt) {
            return $linkProductFmt . $row['product_id'];
        },
    ],
],
```



Créer sa propre classe d'actions
---------------

Utilisez le hook `NullosAdmin_MorphicHelper_StandardColTransformer`.
Exemples:

- \Module\Ekom\Back\Helper\BackHooksHelper::NullosAdmin_MorphicHelper_StandardColTransformer
- \Module\EkomUserTracker\Helper\EkomUserTrackerBackHooksHelper::NullosAdmin_MorphicHelper_StandardColTransformer
- \Module\EkomCartTracker\Helper\EkomCartTrackerBackHooksHelper::NullosAdmin_MorphicHelper_StandardColTransformer


Par convention, les nouvelles actions que vous proposez doivent commencer par le nom du module suivi d'un point.

Par exemple, le module Ekom propose l'action suivante:

- `Ekom.price`
