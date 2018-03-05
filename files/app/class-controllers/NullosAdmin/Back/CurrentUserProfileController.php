<?php


namespace Controller\NullosAdmin\Back;


use Controller\NullosAdmin\NullosBaseController;
use Kamille\Utils\Claws\ClawsWidget;
use Module\NullosAdmin\WidgetModel\CurrentUserProfile\CurrentUserProfileWidgetModel;

class CurrentUserProfileController extends NullosBaseController
{

    protected function prepareClaws()
    {
        parent::prepareClaws();
        $model = CurrentUserProfileWidgetModel::getModel();






        $this->getClaws()
            //--------------------------------------------
            // MAIN
            //--------------------------------------------
            ->setWidget("maincontent.body", ClawsWidget::create()
                ->setTemplate('NullosAdmin/Main/CurrentUserProfile/default')
                ->setConf($model)
            );
    }
}