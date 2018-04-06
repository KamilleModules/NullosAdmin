L'api javascript de Nullos 
===============
2018-03-15


L'api javascript de nullos est située ici: `www/theme/nullosAdmin/js/nullos.js` (ou `class-modules/NullosAdmin/files/app/www/theme/nullosAdmin/js/nullos.js`).

Cette api est disponible partout au sein du thème `nullosAdmin`.




Elle expose les méthodes suivantes:

- confirm
- notif
- modal
- request
- request2Modal
- on
- off
- once
- trigger
- debounce

 
confirm
------------


C'est une version améliorée (visuellement) de window.confirm qui ne fonctionne qu'au sein du thème nullosAdmin.



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


 
modal
------------


Cette méthode permet d'afficher un dialogue modal.



Version minimale

```js 
nullosApi.inst().modal("Hi");
```

Version maximale:

```js
/**
 * The modal's interface is this:
 *
 * function open ( string text, callable onSuccess, string ?title, string ?okBtnText, string ?cancelBtnText)
 * function freeOpen ( string html, options)
 *          options:
 *              - title: null|string, the title of the modal. If null, no title will be displayed.
 *              - onOpenAfter:
 *                      callback ( jContainer )
 *                          with jContainer: a jquery element containing the modal's content container.
 *              - onCloseBefore:
 *                      callback ( jContainer )
 *                          with jContainer: a jquery element containing the modal's content container.
 *
 */
var api = nullosApi.inst();
api.modal("Salut, je suis un modal", {
    title: "Le titre",
    onOpenAfter: function(jContainer){},
    onCloseBefore: function(jContainer){}
});

```

<img src="image/nullos-modal.png" alt="Drawing"/>


 
notif
------------


Cette méthode permet d'afficher une notification flottant vers la partie haut-droite du site.



Version minimale

```js 
nullosApi.inst().notif();
```

Version maximale:

```js
/**
 * @param options
 * - title: default=Hey!
 * - text: string default=
 * - icon: string default=
 * - type: string (success, info, notice, error, dark) default=info
 * - isDark: bool, default=false
 */
var api = nullosApi.inst();
api.notif({
    title: "ça va",
    text: "hello",
    type: "error",  
    icon: "fa fa-plus"
});

```

<img src="image/pnotify.png" alt="Drawing"/>


request
-----------------

Permet de faire une requête [ecp](https://github.com/lingtalfi/Ecp).

L'exemple ci-dessous appelle le ecp de Ekom (`service/Ekom/ecp/api.php`) avec l'action `back.morphic`

```js
nullosApi.inst().request("Ekom:back.morphic", data, function (response) {
    // now do something with the response...
});
```


request2Modal
-----------------

Permet de faire une requête [ecp](https://github.com/lingtalfi/Ecp), dont le retour s'affiche dans un modal (en cas de succès).



```js
nullosApi.inst().request2Modal("Ekom:back.coupon-tennis", {
    type: type,
    values: values
}, {
    onCloseBefore: function (jModal) {
        var jRightSelect = jModal.find('.right-select');
        var values = [];
        jRightSelect.find('option').each(function () {
            values.push($(this).val());
        });

        var sValues = values.join(',');
        var count = values.length;

        jCriteriaItem.find('.values-count').val(count);
        jCriteriaItem.attr('data-values', sValues);
    }
});
```



on, trigger
------

On peut déclarer des événements avec la méthode `on`, et les lancer plus tard avec la méthode `trigger`.

```js
var api = nullosApi.inst();
api
    .on("myEvent", function () {
        alert("fire");
    })
    .trigger("myEvent");
```

La méthode peut accepter un tableau de noms également: 


```js
var api = nullosApi.inst();


api.on(["myEvent", "anotherEvent"], function (eventName) {
    alert("fire ");
});


api.trigger("anotherEvent"); // alert fire
api.trigger("myEvent"); // alert fire
api.trigger("dumm"); // rien ne se passera ici
```


###### Passer des arguments

On peut également passer des arguments

```js
var api = nullosApi.inst();


api.on("myEvent", function (name, age) {
    alert("Je m'appelle " + name + " et j'ai " + age + ' ans');
});
api.trigger("myEvent", "Jordi", 4);

```


off
------

Permet de retirer programmatiquement un événement défini avec on.

```js
var api = nullosApi.inst();


api.on(["myEvent", "anotherEvent"], function (letter) {
    alert("fire " + letter);
});


api.off("myEvent");

api.trigger("anotherEvent", "a"); // alert a
api.trigger("myEvent", "b"); // rien ne se passera ici
api.trigger("dumm", "c"); // rien ne se passera ici
```



once
----------

Permet de programmer la suppression d'un événement juste après sa première exécution.

```js
var api = nullosApi.inst();


api.on("myEvent", function () {
    alert("fire");
});
api.trigger("myEvent"); // fire
api.trigger("myEvent"); // fire


api.once("myEvent2", function () {
    alert("fire2");
});
api.trigger("myEvent2"); // fire2
api.trigger("myEvent2"); // rien ici
```



debounce
----------

Permet de réduire à 1 le nombre d'appels à un callback donné si ce callback est appelé
plusieurs fois pendant une période définie.


```js

// code sans debounce (le callback est appelé à chaque fois que le clavier est touché)
jSearch.on('keyup.gui', function () {
    currentSearch = jSearch.val();
    refresh();
});


// code avec debounce (le callback est appelé 1 seule fois par tranche de 250ms)
jSearch.on('keyup.gui', nullosApi.inst().utils.debounce(function () {
    currentSearch = jSearch.val();
    refresh();
}, 250));

```