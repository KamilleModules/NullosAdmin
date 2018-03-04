Tutoriel de création d'un backoffice simple avec nullos
==========
2018-03-03


Aujourd'hui nous allons voir comment créer un backoffice (bo) simple avec le module NullosAdmin.

Pour commencer, on va importer une architecture [kamille](https://github.com/lingtalfi/kamille) de base.



Allez sur le [repository kamille-app](https://github.com/lingtalfi/kamille-app) et téléchargez le 
contenu dans votre application.

Alternativement, vous pouvez utiliser l'outil git:

```bash
git clone ...
```





Mise en place du serveur web
--------------------------------

Pour la mise en place du serveur web, je vous laisse faire.
Dans la suite de ce tutoriel mon nom de domaine sera nullos-app (http://nullos-app sera donc l'url du site).


### snippets qui peuvent être utiles si vous utilisez MAMP 

```bash
open /Applications/MAMP/conf/apache/extra/httpd-vhosts.conf
```

Mon virtual host:

```apacheconfig
<VirtualHost *:80>
    ServerAdmin admin@gmail.com
    DocumentRoot "/myphp/nullos-admin-app/www"
    ServerName nullos-app
    SetEnv APPLICATION_ENVIRONMENT dev
    <Directory "/myphp/nullos-admin-app/www">
        AllowOverride All
    </Directory>
</VirtualHost>
```



Actions en mode ling
----------------------

- ajout theme nullosAdmin
    - dans www (www/theme/nullosAdmin)
    - dans theme (theme/nullosAdmin)


- configuration module Core (config/modules/Core.conf.php)
    - dualSite: true
    - themeBack: nullosAdmin
    - themeFront: ApplicationParameters::get("theme")
        - s'assurer que le thème est bien bootstrapv4 dans **config/application-parameters.php**
    - defaultProtocol: https  (ou si vous n'avez pas installé https, mettez http)
    - uriPrefixBackoffice: /admin // choix de l'url de l'admin        
        
        
- creation fichier routes/back (config/routsy/back.php)


- copie des controllers (class-controllers/NullosAdmin)
- copie de la configuration du module: (config/modules/NullosAdmin.conf.php)

- enregistrement du module (ajouter NullosAdmin dans modules.txt)

- import de la planète Models (à rajouter dans les dépendances de nullosAdmin)


- ajout des classes thème (class-themes/NullosAdmin et class-themes/NullosTheme.php).
        la création du dossier class-themes était nécessaire.
        Donc décommenter la ligne correspondante dans boot.php
        
- copie du module (class-modules/NullosAdmin)

- copie et adaptation des hooks (class-core/Services/Hooks.php)      

    
- refonte du dashboard par défaut (theme/nullosAdmin/widgets/NullosAdmin/Main/Dashboard/default.tpl.php)          
- refonte du HomePageController par défaut (class-controllers/NullosAdmin/Back/HomePageController.php)

          
- import de la planète ModelRenderers (à rajouter dans les dépendances de nullosAdmin)



Note: l'url de gentelella est: https://colorlib.com/polygon/gentelella/index.html


- refonte du fichier common du theme: theme/nullosAdmin/includes/common.php
- refonte de la class NullosTheme: class-themes/NullosTheme.php
    - suppression de la référence à ekomApi
    - dans bionic:
        - suppression de HtmlPageHelper::js("/modules/Ekom/js/ekom-back-bionic.js", null, null, false);
        - remplacement de HtmlPageHelper::js("/theme/lee/libs/bionic/bionic.js", null, null, false);
            vers HtmlPageHelper::js($prefixUri . "/lib/bionic/bionic.js", null, null, false);
    - ajout d'assets dans stats            
- ajout de la librairie bionic dans le theme: www/theme/nullosAdmin/lib/bionic/bionic.js        