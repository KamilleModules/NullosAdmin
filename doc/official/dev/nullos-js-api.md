L'api javascript de Nullos 
===============
2018-03-15


L'api javascript de nullos est située ici: `www/theme/nullosAdmin/js/nullos.js` (ou `class-modules/NullosAdmin/files/app/www/theme/nullosAdmin/js/nullos.js`).

Cette api est disponible partout au sein du thème `nullosAdmin`.




Elle expose les méthodes suivantes:

- confirm
- notif
- request
- on
- off
- once
- trigger

 
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