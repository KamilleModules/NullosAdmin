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
'colTransformers' => [
    'dropdown' => NullosMorphicHelper::getStandardColTransformer("rating"),
],
```

<img src="image/morphic-rating.png" alt="Drawing"/>








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
