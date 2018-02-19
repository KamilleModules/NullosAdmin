<?php


namespace Module\NullosAdmin\Morphic\Generator;


use Bat\ArrayTool;
use Bat\CaseTool;
use Kamille\Architecture\ApplicationParameters\ApplicationParameters;
use Kamille\Utils\Morphic\Generator\MorphicGenerator;

class NullosMorphicGenerator extends MorphicGenerator
{

    protected $configFileDirForm;
    protected $configFileDirList;


    public function __construct()
    {
        parent::__construct();
    }


    protected function prepareOperation(array $operation)
    {
        $operation = parent::prepareOperation($operation);
        if (false === array_key_exists("icon", $operation)) { // this belongs to nullos
            $operation['icon'] = "fa fa-bomb";
        }


        if (array_key_exists('ric', $operation)) {
            ArrayTool::removeEntry("shop_id", $operation['ric']);
        }

        return $operation;
    }

    protected function getFormConfigFileDestination(array $operation, array $config = [])
    {

        /**
         * Changed 2018-02-19 for conflicts with no namespaces
         */
//        $name = $operation['elementName'] . ".form.conf.php";
        $name = $operation['elementTable'] . ".form.conf.php";
        return $this->configFileDirForm . "/$name";
    }


    protected function getListConfigFileDestination(array $operation, array $config = [])
    {
        /**
         * Changed 2018-02-19 for conflicts with no namespaces
         */
//        $name = $operation['elementName'] . ".list.conf.php";
        $name = $operation['elementTable'] . ".list.conf.php";
        return $this->configFileDirList . "/$name";
    }
}


