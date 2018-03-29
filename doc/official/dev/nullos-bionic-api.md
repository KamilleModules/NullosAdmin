Nullos bionic js api 
===============
2018-03-29


Nullos rend à disposition l'api [bionic](https://github.com/lingtalfi/bionic) au travers du fichier `nullos-bionic.js`(www/modules/NullosAdmin/js/nullos-bionic.js),
que nous appellerons nullosBionic dans la suite de ce document.






Enregistrer un handler pour mon module
------------------------------------

NullosBionic s'accapare le handler unique proposé par le framework bionic.
Afin de permettre aux autres modules d'utiliser bionic également, nullosBionic propose un tableau dans lequel les modules peuvent enregistrer leur handler.


Tout module souhaitant utiliser le framework bionic au sein de l'environnement nullos doit donc enregistrer son handler au niveau du handler nullos, comme ceci:


```js


var myModuleBionicHandler = function(jObj, action, params, take){
    // here your handler code        
}


window.nullosBionicHandlers.push(myModuleBionicHandler);

```




Services proposés par nullosBionic
----------------------------

NullosBionic propose également des services que tous les modules peuvent utiliser:


- ecp service



### Ecp service

NullosBionic propose une requête simple vers un service [ecp](https://github.com/lingtalfi/Ecp).

Une syntaxe particulière est utilisée pour l'action bionic:

- `data-action`: &lt;ecp> &lt;:> &lt;moduleName> &lt;:> &lt;ecpServiceIdentifier>

Par exemple: `ecp:Ekom:back.test`



Voici un exemple d'utilisation concret, utilisé par le module Ekom, pour modifier la visibilité d'un commentaire en cliquant
sur un lien en bout de ligne (dans la liste des commentaires).




```php

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

```


