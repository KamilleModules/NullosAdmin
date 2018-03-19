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
