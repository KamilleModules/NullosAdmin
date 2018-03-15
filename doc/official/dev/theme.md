Thème
===============
2018-03-15


La classe `NullosThemeElementsHelper` peut vous aider.
L'idée est que cette classe vous propose d'afficher des éléments et se charge de **COMMENT** afficher ces éléments.


Afficher une notification
--------------------------


```php
NullosThemeElementsHelper::renderNotif("
Au niveau de la carte, on choisit seulement le nom des attributs (par leur valeur).
Les valeurs des attributs sont définies au niveau des produits (un peu plus loin dans ce formulaire).   
");
```

<img src="image/notif.png" alt="Drawing" />


Afficher un bouton
--------------------------


```php
$buttonHtml = NullosThemeElementsHelper::renderButton("Ajouter un nouveau produit", "#", "fa fa-plus", "small");
```

<img src="image/button.png" alt="Drawing" />