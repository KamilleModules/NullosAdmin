Reactive
=================
2018-03-15

        
        
Un composant **reactive** est un élément de formulaire qui écoute un autre élément et se remplit lui-même.
L'exemple le plus courant, également connu sous le nom de "chained select" est lorsque l'on choisit un pays,
dans un select, et les villes correspondantes s'affichent dans un autre select.


Ce cas d'école illustre parfaitement la raison d'être du système **reactive**.

L'implémentation du système **reactive** est la suivante:

- créer un select pour les pays 
- puis créer un select **réactif** pour les villes
- le select **réactif** est géré par un wrapper javascript et écoute tout changement sur le select pays.
Lorsque le pays change, le select **réactif** va chercher (via ajax) les villes et les injecte dans le select des villes.


De plus, au moment de l'initialisation, un premier changement est déclenché automatiquement sur le select pays, 
de manière à laisser l'opportunité au select villes de se remplir au démarrage de la page.


Le service ajax
----------------
Il utilise json.
Il reçoit l'argument "parentIdentifier" par défaut, qui représente la valeur changée du select country.
Le service renvoie un tableau value => label, correspondant aux villes à injecter.



Exemple
----------

Un exemple d'utilisation avec Soko se trouve [ici](dev/soko-cheatsheet.md#réagir-par-rapport-à-un-autre-élément).