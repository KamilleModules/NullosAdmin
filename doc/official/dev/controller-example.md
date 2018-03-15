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