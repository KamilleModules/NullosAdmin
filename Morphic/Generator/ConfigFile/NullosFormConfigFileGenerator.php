<?php


namespace Module\NullosAdmin\Morphic\Generator\ConfigFile;


use Bat\CaseTool;
use Kamille\Utils\Morphic\Exception\MorphicException;
use Kamille\Utils\Morphic\Generator\ConfigFileGenerator\ConfigFileGeneratorInterface;
use PhpFile\PhpFile;
use QuickPdo\QuickPdoInfoTool;

class NullosFormConfigFileGenerator implements ConfigFileGeneratorInterface
{

    public function __construct()
    {
        //
    }


    public static function create()
    {
        return new static();
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    public function getConfigFileContent(array $operation, array $config = [])
    {

        $elementType = $operation['elementType'];
        $file = PhpFile::create();

        switch ($elementType) {
            case "simple":
                break;
            default:
                throw new MorphicException("Type not handled yet: $elementType");
                break;
        }

        $ric = $operation['ric'];
        $vRic = $this->getVerticalRic($ric);
        $vRicKeys = $this->getVerticalRic($ric, true);
        $commaRics = '$' . implode(', $', $ric);
        $name = $operation['elementName'];
        $table = $operation['elementTable'];
        $label = $operation['elementLabel'];
        $ucLabel = ucfirst($operation['elementLabel']);
        $onProcessBefore = $this->getOnProcessBefore($operation, $config);
        $elementObject = $this->getObjectByElementName($operation['elementName']);
        $file->addUseStatement('use Module\Ekom\Api\Object\Seller\\' . $elementObject . ";");


        if (false === array_key_exists("formInsertSuccessMsg", $operation)) {
            $operation['formInsertSuccessMsg'] = "Le/la " . $label . " a bien été ajouté(e)";
        }
        if (false === array_key_exists("formUpdateSuccessMsg", $operation)) {
            $operation['formUpdateSuccessMsg'] = "Le/la " . $label . " a bien été mis(e) à jour";
        }
        $formInsertSuccessMsg = htmlspecialchars($operation['formInsertSuccessMsg']);
        $formUpdateSuccessMsg = htmlspecialchars($operation['formUpdateSuccessMsg']);


        //--------------------------------------------
        // RIC PART
        //--------------------------------------------
        foreach ($ric as $col) {
            $file->addBodyStatement(<<<EEE
\$$col = (array_key_exists('$col', \$_GET)) ? (int)\$_GET['$col'] : null;
EEE
            );
        }


        //--------------------------------------------
        // UPDATE INSERT
        //--------------------------------------------
        $file->addBodyStatement(<<<'EEE'
        
//--------------------------------------------
// UPDATE|INSERT MODE
//--------------------------------------------
$isUpdate = (false === array_key_exists("form", $_GET));
EEE
        );

        //--------------------------------------------
        // FORM START
        //--------------------------------------------
        $file->addBodyStatement(<<<EEE
        
//--------------------------------------------
// FORM
//--------------------------------------------
\$conf = [
    //--------------------------------------------
    // FORM WIDGET
    //--------------------------------------------
    'title' => "$ucLabel",
    //--------------------------------------------
    // SOKO FORM
    'form' => SokoForm::create()
        ->setName("soko-form-$name")
EEE
        );

        //--------------------------------------------
        // FORM END
        //--------------------------------------------
        $file->addBodyStatement(<<<EEE
    'feed' => MorphicHelper::getFeedFunction("$table"),
    'process' => function (\$fData, SokoFormInterface \$form) use (\$isUpdate, $commaRics) {

        $onProcessBefore


        if (false === \$isUpdate) {
            $elementObject::getInst()->create(\$fData);
            \$form->addNotification("$formInsertSuccessMsg", "success");
        } else {
            $elementObject::getInst()->update(\$fData, [
                $vRicKeys
            ]);
            \$form->addNotification("$formUpdateSuccessMsg", "success");
        }
        return false;
    },
    //--------------------------------------------
    // to fetch values
    'ric' => [
        $vRic
    ],
    //--------------------------------------------
    // IF HAS CONTEXT
    //--------------------------------------------
    'formAfterElements' => [
        [
            "type" => "pivotLinks",
            "links" => [
                [
                    /**
                     * Foreach ric,
                     * notice that we use the foreign key (seller_id) of the foreign table rather
                     * than the ric of the current table (id)
                     */
                    "link" => E::link("NullosAdmin_Ekom_TestHas_List") . "?seller_id=$id",
                    "text" => "Voir les addresses de ce vendeur",
                ],
            ],
        ],
    ],
];
EEE
        );


        //--------------------------------------------
        // DISCOVERING PIVOT LINKS
        //--------------------------------------------
        $tableInfos = $this->getPivotTablesInfo($operation, $config);
        az($tableInfos);
        foreach($tableInfos as $table => $info){

        }


        return $file->render();
    }


    protected function getOnProcessBefore(array $operation, array $config)
    {
        return [];
    }

    protected function getObjectByElementName($name)
    {
        return CaseTool::snakeToFlexiblePascal($name);
    }

    protected function getVerticalRic(array $ric, $addKeys = false)
    {
        $s = '';
        foreach ($ric as $col) {
            if (false === $addKeys) {
                $s .= "'" . $col . "'," . PHP_EOL;
            } else {
                $s .= "'" . $col . "' => \$$col," . PHP_EOL;
            }
        }
        return $s;

    }

    private function getPivotTablesInfo($operation, array $config)
    {
        $ret = [];

        //--------------------------------------------
        // GET TABLES
        //--------------------------------------------
        $tables = [];
        $pivotMode = (array_key_exists("pivotMode", $config)) ? $config['pivotMode'] : 'discover';
        $pivot = [];
        if (array_key_exists("pivot", $operation)) {
            $pivot = $operation['pivot'];
            if (array_key_exists("mode", $pivot)) {
                $pivotMode = $pivot['mode'];
            }
        }

        // manual tables
        $tablesInfo = [];
        if (array_key_exists("tables", $pivot)) {
            $tablesInfo = $pivot['tables'];
            $tables = array_keys($tablesInfo);
        }
        // do we need to discover tables?
        if ("discover" === $pivotMode) {
            $prefix = $operation['elementTable'] . "_has_";
            $dbName = QuickPdoInfoTool::getDatabase();
            $dbTables = QuickPdoInfoTool::getTables($dbName);
            foreach ($dbTables as $table) {
                if (
                    (0 === strpos($table, $prefix)) &&
                    false === in_array($table, $tables)
                ) {
                    $tables[] = $table;
                }
            }
        }
        // remove tables
        if (array_key_exists("removeTables", $pivot)) {
            $removeTables = $pivot['removeTables'];
            $tables = array_diff($tables, $removeTables);
        }

        //--------------------------------------------
        // COMBINE TABLES WITH INFO
        //--------------------------------------------
        foreach ($tables as $table) {
            if (array_key_exists($table, $tablesInfo)) {
                $ret[$table] = $tablesInfo;
            } else {
                $ret[$table] = [];
            }

            // guess route?
            if (false === array_key_exists("route", $ret[$table])) {
                $ret[$table]["route"] = $this->getPivotLinkRoute($operation, $config);
            }

            // guess text?
            if (false === array_key_exists("text", $ret[$table])) {
                $ret[$table]["text"] = "Voir les " . $operation['elementLabelPlural'] . " de ce/cette " . $operation['elementLabel'];
            }
        }
        return $ret;
    }


    protected function getPivotLinkRoute(array $operation, array $config)
    {

    }
}