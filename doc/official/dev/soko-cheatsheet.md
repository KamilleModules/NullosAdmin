
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



Input couleur
-----------

```php
->addControl(SokoInputControl::create()
    ->setName("color")
    ->setLabel("Couleur")
    ->setProperties([
        "colorPicker" => true,
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

###### Wysiwyg avec presets

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


###### Wysiwyg avec upload

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


###### Afficher du texte serialisé

```php
->addControl(SokoInputControl::create()
    ->setName("info_messages")
    ->setLabel("Info messages")
    ->setType("textarea")
    ->setProperties([
        "showSerializeOnly" => true,
    ])
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
###### Ajouter un texte informatif sous la liste

```php
->addControl(SokoChoiceControl::create()
    ->setName("generator_mode")
    ->setLabel("Mode")
    ->setChoices($choice_generators)
    ->setProperties([
        'info' => "Some text...",
    ])
)
```


###### Liste acceptant la valeur NULL

```php
->addControl(SokoChoiceControl::create()
    ->setName("generator_mode")
    ->setLabel("Mode")
    ->setChoices($choice_generators)
    ->setProperties([
        'nullableFirstItem' => "Aucune valeur",
    ])
)
```


###### Afficher un lien supplémentaire

```php
->addControl(SokoChoiceControl::create()
    ->setName("product_type_id")
    ->setLabel("Type de produit")
    ->setChoices($choice_product_type_id)
    ->setProperties([
        'readonly' => (null !== $product_id),
        'extraLink' => [
            'text' => 'Créer un nouvel élément "Product id"',
            'icon' => 'fa fa-plus',
            'link' => A::link('Ekom_Generated_EkProduct_List') . '?form',
        ],
    ])
)
```


###### Afficher une liste de type tennis

```php
->addControl(SokoTennisListChoiceControl::create()
    ->setName("cond_country_id")
    ->setLabel("Pays")
    ->setChoices($choice_countries)
    ->setProperties([
        'leftBtnText' => "Ajouter",
        'rightBtnText' => "Retirer",
        'leftTitle' => "Pays non sélectionnés",
        'rightTitle' => "Pays sélectionnés",
    ])
)
```



<img src="image/morphic-tennis-list.png" alt="Drawing"/>



###### Réagir par rapport à un autre élément


Ce méchanisme est l'implémentation du système [**reactive**](divers/reactive-system.md) (pour plus d'infos voir ce fichier `class-modules/Ekom/doc/backoffice/backoffice-brainstorm.md`),
qui permet en gros à un select enfant de modifier automatiquement son état en fonction du changement
d'un select parent.


```php
->addControl(SokoChoiceControl::create()
    ->setName("product_type_id")
    ->setLabel("Type de produit")
    ->setChoices($choice_product_type_id)
    ->setProperties([
        "listenTo" => "product_attribute_id",
        "service" => "Ekom:back.reactive.product_attribute_value", // la syntaxe est: <moduleName> <:> <serviceIdentifier>
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


Auto-complete multipliable
-----------------

Attention, cet élément renvoie un tableau (les caractères [] sont ajoutés après le nom de chaque élément créé).

```php
        ->addControl(SokoAutocompleteInputControl::create()
            ->setAutocompleteOptions(BackFormHelper::createSokoAutocompleteOptions([
                'action' => "auto.product", // créez le service dans votre ecp/api.php
            ]))
            ->setProperties([
                "multipliable" => true,
                "multipliableOptions" => [
                    "addBtnText" => "Ajouter ce produit à la sélection",
                    "removeBtnText" => "Supprimer",
                    "emptyValueTitle" => "Attention!",
                    "emptyValueText" => "La valeur ne peut pas être vide",
                    "acceptDuplicate" => false,
                    "duplicateValueTitle" => "Attention!",
                    "duplicateValueText" => "Cette valeur a déjà été ajoutée",
                ],
            ])
            ->setName("apply_product_ids")
            ->setLabel('Sélection des produits')
            ->setValue($addressId)
        )
```

Ou bien la même chose en plus court:


```php
        ->addControl(SokoAutocompleteInputControl::create()
            ->setAutocompleteOptions(BackFormHelper::createSokoAutocompleteOptions([
                'action' => "auto.product", // créez le service dans votre ecp/api.php
            ]))
            ->setProperties([
                "multipliable" => true,
                "multipliableOptions" => BackFormHelper::getSokoAutocompleteMultipleDefaultOptions(),
            ])
            ->setName("apply_product_ids")
            ->setLabel('Sélection des produits')
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
            'link' => A::link('Ekom_Generated_EkProduct_List') . '?form',
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

###### Ajouter un lien supplémentaire

```php
->addControl(SokoComboBoxControl::create()
    ->setName("product_attribute")
    ->setLabel("Attributs")
    ->setChoices($choices_product_attributes)
    ->setProperties([
        "useSortBox" => true,
        'extraLink' => [
            'text' => 'Créer un nouvel élément "Product id"',
            'icon' => 'fa fa-plus',
            'link' => A::link('Ekom_Generated_EkProduct_List') . '?form',
        ],
    ])
    ->addEmptyChoiceAtBeginning()
)
```



Arbre
-----------------

Le tableau $categories doit comporter des éléments dont la structure est la suivante:

- id
- label
- children: (récursif)

$expandedCats correspond à un tableau d'id de catégories à ouvrir au démarrage de l'arbre.


```php
->addControl(NullosSokoFancyTreeControl::create()
    ->setName("categories")
    ->setLabel("Catégories")
    ->setCategories($categories)
    ->setExpanded($expandedCats)
)
```



Dropzone
-----------------

###### Dropzone pour une image simple


```php
->addControl(SokoSafeUploadControl::create()
    ->setName("url")
    ->setLabel('Url')
    ->setProfileId("cardImage")
    ->setRic($ric)
    ->setPayloadVar("product_card_id", $product_card_id)
    ->setProperties([
        'required' => false,
    ])
)
```

