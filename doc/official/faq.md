Faq
==========
2018-03-06




Comment ajouter une notification depuis un contrôleur?
---------------------


```php
$type = "error";
$message = "This is an error message";
NullosGuiEnvironment::addNotification($message, $type);
```



Comment appeler un web service ecp ?
-----------------

L'exemple ci-dessous appelle le ecp de Ekom (`service/Ekom/ecp/api.php`) avec l'action `back.morphic`

```js
nullosApi.inst().request("Ekom:back.morphic", data, function (response) {
    jElement.empty().append(response.view);
    onRefreshAfter && onRefreshAfter();
    makeTdClickable();
});
```


Comment faire un window.confirm en plus joli ?
----------------------------------- 


Version minimale

```js 
nullosApi.inst().confirm("Etes-vous sûr(e) de supprimer ce produit de cette carte", function () {
    alert("ok");
});
```

Version abstraite

```js
nullosApi.inst().confirm(confirmText, function () {
    alert("ok");
}, confirmTitle, confirmButtonOkText, confirmButtonCancelText);
```



Récupérer des informations sur l'utilisateur connecté
----------------------------------- 

```php
// récupérer l'id de l'utilisateur
a(NullosUser::getId()); // 4
```