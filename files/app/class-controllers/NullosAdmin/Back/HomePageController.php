<?php


namespace Controller\NullosAdmin\Back;


use Controller\NullosAdmin\NullosBaseController;
use Core\Services\Hooks;
use Kamille\Utils\Claws\ClawsWidget;

class HomePageController extends NullosBaseController
{


    protected function prepareClaws()
    {
        $claws = $this->getClaws();
        $claws->setWidget("maincontent.body", ClawsWidget::create()
            ->setTemplate('NullosAdmin/Main/Dashboard/default')
            ->setConf([])
        );
        Hooks::call("NullosAdmin_prepareHomePageClaws", $claws);
        parent::prepareClaws();
    }


}