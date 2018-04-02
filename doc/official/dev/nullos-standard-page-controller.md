NullosStandardPageController
========================
2018-03-30



Dans Nullos, une page standard a la structure suivante:

- top
- body (maincontent)



Pour créer une page standard rapidement, vous pouvez utiliser le contrôleur `NullosStandardPageController`,
qui propose les méthodes suivantes:

- [pageTop](#pagetop)
- [noPageTop](#nopagetop)
- [errorTemplate](#errortemplate)



PageTop
----------

PageTop représente la partie haute du maincontent dans un layout classique.

Il y a 3 parties (voir image):

- breadcrumbs: le fil d'arianne situé en haut à gauche
- le titre
- la partie droite (appelée RightBar) qui contient les boutons d'action généraux de la page


<img src="image/page-top.png" alt="Drawing"/>


- `pageTop()`: accès à l'object PageTop situé ici: `class-modules/NullosAdmin/Models/StandardPage/Top/PageTop.php`
    - `setTitle`: définit le titre de la page
    - `breadCrumbs()`:  accès à l'objet `Breadcrumbs` qui a les méthodes suivantes:
        - `addLink`: ajoute un lien au breadcrumb            
        - `addText`: ajoute un texte au breadcrumb
    - `rightBar()`:  accès à l'objet `RightBar` qui a les méthodes suivantes:
        - `addButton`: ajoute un bouton au menu dropdown en dernière position            
        - `prependButton`: ajoute un bouton au menu dropdown en première position            
        - `addDropDown`: ajoute un dropdown en dernière position
        

L'objet DropDown a lui-même les méthodes suivantes:
- `setLabel`: définit le label                    
- `setIcon`: définit un icône à utiliser pour cet élément de dropdown                     
- `addButton`: ajoute un bouton au menu dropdown                    



noPageTop
----------

noPageTop est une méthode qui supprime le pageTop.
Cette méthode peut être utile dans le où vous codez une gui "from scratch", et vous n'avez pas la nécessité d'utiliser 
le widget pageTop.




errorTemplate
----------------

Cette méthode permet de générer un widget d'erreur.

On peut soit utiliser une erreur locale, soit une erreur globale.


###### Erreur locale

Cette erreur s'affiche à la manière d'un widget normal.
On peut régler la position avec l'argument position.

<img src="image/local-error.png" alt="Drawing"/>


```php
$this->errorTemplate("oops", "Erreur fatale", true);
```


###### Erreur globale

Cette erreur prend toute la page, et ainsi attire mieux l'attention de l'utilisateur.

<img src="image/fatal-error.png" alt="Drawing"/>

```php
$this->errorTemplate("oops", "Erreur locale", null, "first"); // erreur locale en première position
```




