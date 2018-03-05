<?php


namespace Controller\NullosAdmin\Back;


use Controller\NullosAdmin\Back\Form\NullosLoginSokoForm;
use Controller\NullosAdmin\NullosBaseController;

use Kamille\Architecture\Response\ResponseInterface;
use Kamille\Utils\Claws\ClawsWidget;

class LoginController extends NullosBaseController
{


    /**
     * See original file when in doubt:
     * /myphp/leaderfit/leaderfit/class-modules/NullosAdmin/Controller/NullosAdminController.php
     */
    protected function prepareClaws()
    {
        parent::prepareClaws();


        $response = null;
        $form = NullosLoginSokoForm::getPreparedForm($response);
        if ($response instanceof ResponseInterface) {
            $this->clawsReturn = $response;
            return;
        }


        $this->getClaws()
            ->setLayout("splash/login-form")
            //--------------------------------------------
            // MAIN
            //--------------------------------------------
            ->setWidget("maincontent.body", ClawsWidget::create()
                ->setTemplate('NullosAdmin/Main/LoginForm/default')
                ->setConf([
                    'form' => $form->getModel(),
                ])
            );
    }
}