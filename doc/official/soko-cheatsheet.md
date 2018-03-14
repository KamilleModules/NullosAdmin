Soko Cheatsheet
====================
2018-03-12


Soko est le système de formulaire utilisé par défaut dans NullosAdmin.

Voici un petit mémo aidant à créer des formulaires.

Le renderer utilisé est: `class-modules/NullosAdmin/SokoForm/Renderer/NullosMorphicBootstrapFormRenderer.php`





Input
-----------

```php
->addControl(SokoInputControl::create()
    ->setName("reference")
    ->setLabel("Référence")
)
```

###### Ajouter un texte informatif sous le input

```php
->addControl(SokoInputControl::create()
    ->setName("slug")
    ->setLabel("Slug")
    ->setProperties([
        'info' => "Le lien vers la fichier produit sera: <b>$slugUrl</b>",
    ])
    ->setPlaceholder($placeHolders['slug'])
)
```

###### Placer un mini-texte à gauche du input, en décoration

```php
->addControl(SokoInputControl::create()
    ->setName("meta_title")
    ->setLabel("Balise titre")
    ->setProperties([
        'leftBoxText' => 70,
    ])
)
```



Textarea
-----------

```php
->addControl(SokoInputControl::create()
    ->setName("conditions")
    ->setLabel("Conditions")
    ->setType("textarea")
)
```

###### Wysiwyg

```php
->addControl(SokoInputControl::create()
    ->setName("description")
    ->setLabel("Description")
    ->setType("textarea")
    ->setProperties([
        "wysiwyg" => true,
        "wysiwygConfig" => TrumbowygConfigHelper::create(),
    ])
    ->setPlaceholder($placeHolders['description'])
)
```

###### Wysiwyg with preset

```php
->addControl(SokoInputControl::create()
    ->setName("description")
    ->setLabel("Description")
    ->setType("textarea")
    ->setProperties([
        "wysiwyg" => true,
        "wysiwygConfig" => TrumbowygConfigHelper::create()->prepareByPreset("minimal"),
    ])
    ->setPlaceholder($placeHolders['description'])
)
```


###### Wysiwyg with upload

```php
->addControl(SokoInputControl::create()
    ->setName("description")
    ->setLabel("Description")
    ->setType("textarea")
    ->setProperties([
        "wysiwyg" => true,
        "wysiwygConfig" => TrumbowygConfigHelper::create()
            ->setUploadParameters([
                "serverPath" => "/service/Ekom/ecp/api?action=upload_handler&profile_id=defaultImage&return_type=url",
                "fileFieldName" => "file",
                "urlPropertyName" => "url",
            ]),
    ])
    ->setPlaceholder($placeHolders['description'])
)
```



Liste
-------------

```php

$choice_product_types = QuickPdo::fetchAll("select id, concat(id, \". \", name) as label from ek_product_type where bo_active=1", [], \PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);


->addControl(SokoChoiceControl::create()
    ->setName("product_type")
    ->setLabel("Type de produit")
    ->setChoices($choice_product_types)
)
```

###### Mode read only

```php
->addControl(SokoChoiceControl::create()
    ->setName("product_type_id")
    ->setLabel("Type de produit")
    ->setChoices($choice_product_type_id)
    ->setProperties([
        'readonly' => true,
    ])
)
```



Auto-complete
-----------------

```php
->addControl(SokoAutocompleteInputControl::create()
    ->setAutocompleteOptions(BackFormHelper::createSokoAutocompleteOptions([
        'action' => "auto.address", // créez le service dans votre ecp/api.php
    ]))
    ->setName("address_id")
    ->setLabel('Address id')
    ->setValue($addressId)
)
```

###### Ajouter un lien supplémentaire

```php
->addControl(SokoAutocompleteInputControl::create()
    ->setName("product_id")
    ->setLabel("Product id")
    ->setProperties([
        'readonly' => (null !== $product_id),
        'extraLink' => [
            'text' => 'Créer un nouvel élément "Product id"',
            'icon' => 'fa fa-plus',
            'link' => E::link('Ekom_Back_Generated_EkProduct_List') . '?form',
        ],
    ])
    ->setValue($product_id)
    ->setAutocompleteOptions(BackFormHelper::createSokoAutocompleteOptions([
        'action' => "auto.product",
    ]))
)
```



Choix booléen
-----------------

```php
->addControl(SokoBooleanChoiceControl::create()
    ->setName("active")
    ->setLabel("Active")
    ->setValue(1)
)
```


ComboBox avec choix de l'ordre des éléments
-----------------

```php
->addControl(SokoComboBoxControl::create()
    ->setName("product_attribute")
    ->setLabel("Attributs")
    ->setChoices($choices_product_attributes)
    ->setProperties([
        "useSortBox" => true,
    ])
    ->addEmptyChoiceAtBeginning()
)
```