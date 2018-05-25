Contrôleurs d'exemple
========================
2018-03-13



Le contrôleur de base
----------------------

````php
<?php


namespace Controller\Ekom\Misc;


use Controller\NullosAdmin\NullosBaseController;

class MyTestController extends NullosBaseController
{
    public function render(){
        $this->prepareClaws();
        return parent::doRenderClaws();
    }
}
````


Le contrôleur de base avec un module
----------------------

````php
<?php


namespace Controller\Ekom\Misc;


use Controller\NullosAdmin\NullosBaseController;
use Kamille\Utils\Claws\ClawsWidget;

class MyTestController extends NullosBaseController
{
    public function render(){
        $this->prepareClaws();
        $this->getClaws()->setWidget("maincontent.mywidget", ClawsWidget::create() // la position est maincontent
            ->setTemplate("Test/mytemplate") // theme/nullosAdmin/widgets/Test/mytemplate.tpl.php
            ->setConf([])
        );

        return parent::doRenderClaws();
    }
}



````



Le contrôleur avec une liste morphic
----------------------

````php
<?php

namespace Controller\EkomTrainingProducts\Back\Participant;


use Controller\NullosAdmin\Back\NullosMorphicController;
use Core\Services\A;
use Kamille\Utils\Claws\ClawsWidget;
use Module\NullosAdmin\Morphic\Helper\NullosMorphicModelHelper;

class ParticipantFilesListController extends NullosMorphicController
{


    public function __construct()
    {
        parent::__construct();
        $pageTop = $this->pageTop();
        $pageTop->breadcrumbs()->reset()
            ->addLink("Pièces administratives", A::link("EkomTrainingProducts_ParticipantFiles_List"));
    }


    public function render()
    {

        $this->prepareClaws();
        $this->getClaws()
            ->setWidget("maincontent.participant_files", ClawsWidget::create()
                ->setTemplate("NullosAdmin/Main/MorphicList/default")
                ->setConf([
                    'model' => NullosMorphicModelHelper::getListModel("Ekom", "back/users/user_order"),
                ])
            );


        return $this->doRenderClaws();
    }
}




````



Le contrôleur d'affichage du détail d'un item de liste
----------------------

````php
<?php


namespace Controller\PeiPei\Back\Transaction;

use Controller\NullosAdmin\Back\NullosMorphicController;
use Kamille\Utils\Claws\ClawsWidget;


class TransactionInfoController extends NullosMorphicController
{

    public function render()
    {
        $this->prepareClaws();


        //--------------------------------------------
        // CONFIGURE HEADER
        //--------------------------------------------
        $pageTop = $this->pageTop();
        $pageTop->setTitle("Détail d'une transaction");
        $pageTop->breadcrumbs()->addLink("Transactions");


        //--------------------------------------------
        // PREPARE THE PAGE DEPENDING ON THE ID PASSED IN THE URI
        //--------------------------------------------
        $id = $_GET['id'] ?? null;
        if ($id) {
            $table = [
                "Pays d'origine" => "France",
                "Inscrit à la newsletter" => false,
            ];
            $this->getClaws()
                ->setWidget("maincontent.appInfo", ClawsWidget::create()
                    ->setTemplate("NullosAdmin/Main/VeryFlatTable/default")
                    ->setConf([
                        'title' => "Détails du compte",
                        'flatTable' => $table,
                    ])
                );
        } else {
            $this->getClaws()
                ->setWidget("maincontent.errorMessage", ClawsWidget::create()
                    ->setTemplate("NullosAdmin/Main/Error/default")
                    ->setConf([
                        "title" => "Oops",
                        "message" => "Veuillez renseigner l'id dans l'url",
                    ])
                );
        }


        return parent::doRenderClaws();
    }
}





````



Le contrôleur FormList morphic qui étend un contrôleur morphic généré
----------------------

Ce contrôleur est très pratique lorsqu'on créé un système from scratch proche de la structure des tables.

````php
<?php


namespace Controller\TeamMail\Back\Contact;

use Controller\TeamMail\Back\Generated\TmContact\TmContactListController;
use Core\Services\A;
use Module\TeamMail\Helper\TeamMailBackControllerHelper;


class ContactListController extends TmContactListController
{
    public function __construct()
    {
        parent::__construct();
        $this->addConfigValues([
            'title' => "Contacts",
            'route' => "TeamMail_Contact_List",
            'form' => "back/contact",
            'list' => "back/contact",
            'boundTables' => TeamMailBackControllerHelper::getBoundTables(),
        ]);

    }


    public function preparePageTop()
    {
        parent::preparePageTop();
        $this->pageTop()->setTitle("Contacts");
        $this->pageTop()->breadcrumbs()->reset()
            ->addLink("Contacts", A::link("TeamMail_Contact_List"));
    }
}



````