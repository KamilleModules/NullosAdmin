<?php


namespace Module\NullosAdmin\Morphic\Generator;


use Bat\CaseTool;
use Kamille\Architecture\ApplicationParameters\ApplicationParameters;
use Kamille\Utils\Morphic\Generator\MorphicGenerator;

class NullosMorphicGenerator extends MorphicGenerator
{

    protected $configFileDir;


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
        return $operation;
    }

    protected function getFormConfigFileDestination(array $operation, array $config = [])
    {
        $name = $operation['elementName'] . ".form.conf.php";
        return $this->configFileDir . "/$name";
    }
}


