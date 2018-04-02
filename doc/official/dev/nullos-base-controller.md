NullosBaseController
========================
2018-03-23



Nullos propose un contrôleur de base: `class-controllers/NullosAdmin/NullosBaseController.php`,
qui propose les méthodes suivantes:

- `setSelectedLeftMenuItemByRoute`: permet de définir quel élément de menu sera sélectionné dans le menu de gauche (parfois l'élément
de menu sélectionné par défaut n'est pas celui que l'on souhaite sélectionner)


- `addPopover`: ajoute un popover en haut de la page


```php
    $this->addPopOver("This is a popover");
    $this->addPopOver("This is a popover", "success", "I'm the title");
    // function addPopOver(string $message, string $type = "info", string $title = null)
```

<img src="image/nullos-popover.png" alt="Drawing"/>


- `addNotification`: ajoute une notification en haut de la page




```php
$this->addNotification("I'm a notification"); // reste jusqu'à ce qu'on la supprime manuellement
$this->addNotification("I'm a notification", "success", "Title...", [
    'sticky' => false, // s'en va automatiquement au bout d'un certain délai
    'icon' => "fa fa-plus",
]);
// function addNotification(string $message, string $type = "info", string $title = null, array $options = [])
```

<img src="image/nullos-notifications.png" alt="Drawing"/>




