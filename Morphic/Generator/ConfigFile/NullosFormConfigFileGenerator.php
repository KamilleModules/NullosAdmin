<?php


namespace Module\NullosAdmin\Morphic\Generator\ConfigFile;


use Bat\CaseTool;
use Kamille\Services\XLog;
use Kamille\Utils\Morphic\Exception\MorphicException;
use Kamille\Utils\Morphic\Generator\ConfigFileGenerator\AbstractConfigFileGenerator;
use Kamille\Utils\Morphic\Generator\ConfigFileGenerator\ConfigFileGeneratorInterface;
use Kamille\Utils\Morphic\Generator\GeneratorHelper\MorphicGeneratorHelper;
use Module\NullosAdmin\Exception\NullosException;
use OrmTools\Helper\OrmToolsHelper;
use PhpFile\PhpFile;
use QuickPdo\QuickPdo;
use QuickPdo\QuickPdoInfoTool;

class NullosFormConfigFileGenerator extends AbstractConfigFileGenerator
{

    //--------------------------------------------
    //
    //--------------------------------------------
    public function getConfigFileContent(array $operation, array $config = [])
    {


        $file = PhpFile::create();

        $table = $operation['elementTable'];
        if (array_key_exists("elementType", $operation)) {
            $elementType = $operation['elementType'];
        } else {
            $elementType = (false !== strpos($table, '_has_')) ? 'context' : 'simple';
        }

        $dbPrefixes = (array_key_exists("dbPrefixes", $config)) ? $config['dbPrefixes'] : [];
        $ric = $operation['ric'];
        $vRic = $this->getVerticalRic($ric);
        $vRicKeys = $this->getVerticalRic($ric, true);
        $commaRics = '$' . implode(', $', $ric);
        $name = $operation['elementName'];
        $label = $operation['elementLabel'];
        $ai = $operation['ai'];
        $ucLabel = ucfirst($operation['elementLabel']);
        $columnTypes = $operation['columnTypes'];
        $columnTypesPrecision = $operation['columnTypesPrecision'];
        $columnFkeys = $operation['columnFkeys'];
        $onProcessBefore = $this->getOnProcessBefore($operation, $config);
        $elementObject = $this->getObjectByElementName($operation['elementName']);
        $file->addUseStatement('use Module\Ekom\Api\Object\\' . $elementObject . ";");
        $file->addUseStatement(<<<EEE
use Kamille\Utils\Morphic\Helper\MorphicHelper;
use Module\Ekom\Back\User\EkomNullosUser;
use SokoForm\Form\SokoFormInterface;
use SokoForm\Form\SokoForm;
use SokoForm\Control\SokoAutocompleteInputControl;
use SokoForm\Control\SokoInputControl;
use SokoForm\Control\SokoChoiceControl;
use SokoForm\Control\SokoBooleanChoiceControl;
use Module\Ekom\Utils\E;
EEE
        );


        if ('simple' === $elementType) {
            if (false === array_key_exists("formInsertSuccessMsg", $operation)) {
                $operation['formInsertSuccessMsg'] = "Le/la " . $label . " a bien été ajouté(e)";
            }
            if (false === array_key_exists("formUpdateSuccessMsg", $operation)) {
                $operation['formUpdateSuccessMsg'] = "Le/la " . $label . " a bien été mis(e) à jour";
            }
        } else {
            $leftTable = OrmToolsHelper::getHasLeftTable($table);
            $leftLabel = $this->dictionary->getLabel($leftTable);

            if (false === array_key_exists("formInsertSuccessMsg", $operation)) {
                $operation['formInsertSuccessMsg'] = "Le/la " . $label . " pour le/la $leftLabel \\\"\$avatar\\\" a bien été ajouté(e)";
            }
            if (false === array_key_exists("formUpdateSuccessMsg", $operation)) {
                $operation['formUpdateSuccessMsg'] = "Le/la " . $label . " pour le/la $leftLabel \\\"\$avatar\\\" a bien été mis(e) à jour";
            }
        }


        $formInsertSuccessMsg = htmlspecialchars($operation['formInsertSuccessMsg']);
        $formUpdateSuccessMsg = htmlspecialchars($operation['formUpdateSuccessMsg']);


        //--------------------------------------------
        // INTRO
        //--------------------------------------------
        $file->addBodyStatement(<<<EEE
//--------------------------------------------
// SIMPLE FORM PATTERN
//--------------------------------------------
EEE
        );


        if ("simple" === $elementType) {

            //--------------------------------------------
            // RIC PART
            //--------------------------------------------
            foreach ($ric as $col) {
                $file->addBodyStatement(<<<EEE
\$$col = (array_key_exists('$col', \$_GET)) ? (int)\$_GET['$col'] : null;
EEE
                );
            }
        } else {
            $contextCols = MorphicGeneratorHelper::getContextFieldsByHasTable($table, $dbPrefixes);
            foreach ($contextCols as $col) {
                $file->addBodyStatement(<<<EEE
\$$col = MorphicHelper::getFormContextValue("$col", \$context);
EEE
                );
            }
            $file->addBodyStatement(<<<EEE
\$avatar = MorphicHelper::getFormContextValue("avatar", \$context);
EEE
            );

            $childCols = array_diff($ric, $contextCols);
            foreach ($childCols as $col) {
                $file->addBodyStatement(<<<EEE
\$$col = (array_key_exists('$col', \$_GET)) ? (int)\$_GET['$col'] : null;
EEE
                );
            }
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
        // FORM BODY
        //--------------------------------------------
//        az(__FILE__, $operation);
        $cols = $operation['columns'];
        foreach ($cols as $col) {
            $fkey = false;
            if (array_key_exists($col, $columnFkeys)) {
                $fkey = $columnFkeys[$col];
            }

            $thisAi = ($ai === $col) ? $ai : false;

            $params = [
                "column" => $col,
                "label" => $operation['elementLabel'],
                "type" => $columnTypes[$col],
                "typePrecision" => $columnTypesPrecision[$col],
                "fkey" => $fkey,
                "ai" => $thisAi,
                "isInRic" => in_array($col, $ric, true),
            ];
            $this->prepareControl($file, $params, $config);

        }


        //--------------------------------------------
        // FORM END
        //--------------------------------------------
        $file->addBodyStatement(<<<EEE
    ,        
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
EEE
        );


        //--------------------------------------------
        // DISCOVERING PIVOT LINKS
        //--------------------------------------------
        $tableInfos = $this->getPivotTablesInfo($operation, $config);

        if ($tableInfos) {


            $sItems = '';
            foreach ($tableInfos as $item) {

                $args = '';
                $c = 0;
                foreach ($item['ric2Fkeys'] as $ric => $fkey) {
                    if (0 !== $c) {
                        $args .= '&';
                    }
                    $args .= $fkey . '=$' . $ric;
                    $c++;
                }

                $sItems .= '
                [
                    "link" => E::link("' . $item['route'] . '") . "?' . $args . '",
                    "text" => "' . str_replace('"', '\"', $item['text']) . '",
                ],
';
            }


            $file->addBodyStatement(<<<EEE
    //--------------------------------------------
    // IF HAS CONTEXT
    //--------------------------------------------
    'formAfterElements' => [
        [
            "type" => "pivotLinks",
            "links" => [
$sItems
            ],
        ],
    ],
EEE
            );
        }

        $file->addBodyStatement(<<<EEE
];
EEE
        );

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
        /**
         * collecting the potential pivot tables, considering the options/configurations
         * (see Kamille/Utils/Morphic/Generator/morphic-generator-brainstorm-2.md for more info):
         *
         * - operation:
         * ----- pivot
         * --------- mode: discover|manual=(configuration.pivotMode)
         * --------- removeTables: []
         * --------- tables: []
         * - configuration:
         * ----- pivotMode: discover|manual=discover
         *
         */
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
            $table = $operation['elementTable'];
            $hasTables = OrmToolsHelper::getHasTables($table);
            foreach ($hasTables as $t) {
                if (false === in_array($t, $tables, true)) {
                    $tables[] = $t;
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
                $ret[$table]["route"] = $this->getPivotLinkRoute($table);
            }

            // guess text?
            if (false === array_key_exists("text", $ret[$table])) {
                list($label, $labelPlural) = $this->getPivotLinkLabels($table, $operation);
                $ret[$table]["text"] = "Voir les " . $labelPlural . " de ce/cette " . $label;
            }

            $ric2Fkeys = $this->getPivotLinkRicToForeignKeys($operation, $table);
            $ret[$table]["ric2Fkeys"] = $ric2Fkeys;

        }
        return $ret;
    }


    protected function getPivotLinkRoute($table)
    {
        throw new NullosException("override me");

    }

    protected function getPivotLinkLabels($table, array $operation)
    {
        throw new NullosException("override me");
    }

    /**
     * We need to return the ric of the parent/context table
     * AND the corresponding keys in the foreign table.
     */
    protected function getPivotLinkRicToForeignKeys(array $operation, $hasTable)
    {
        $ret = [];
        $ric = $operation['ric'];
        $parentTable = $operation['elementTable'];
        $fkeys = QuickPdoInfoTool::getForeignKeysInfo($hasTable);

        foreach ($fkeys as $fkey => $info) {
            if ($parentTable === $info[1] && in_array($info[2], $ric, true)) {
                $ret[$info[2]] = $fkey;
            }
        }
        return $ret;
    }

    //--------------------------------------------
    // FORM BODY
    //--------------------------------------------
    /**
     * Add the code related to the control using the given phpFile.
     *
     * Note: you don't handle auto-incremented fields in this method,
     * as they are handled somewhere else.
     *
     *
     *
     * @param PhpFile $file
     * @param $params
     *
     */
    protected function prepareControl(PhpFile $file, array $params, array $config)
    {

        $col = $params['column'];
        $type = $params['type'];
        $typePrecision = $params['typePrecision'];
        $fkey = $params['fkey'];
        $ai = $params['ai'];
        $label = $params['label'];
        $isInRic = $params['isInRic'];


        if ($ai) {
            $file->addBodyStatement(<<<EEE
        ->addControl(SokoInputControl::create()
            ->setName("$ai")
            ->setLabel("$label")
            ->setProperties([
                'readonly' => true,
            ])
            ->setValue(\$$ai)
        )
EEE
            );
        } elseif (false !== $fkey) {
            $this->doPrepareForeignKeyControl($file, $params, $config);

        } else {


            if (false === $this->doPrepareColumnControl($file, $params, $config)) {


                $label = ucfirst($col);
                switch ($type) {
                    case "text":
                        $file->addBodyStatement(<<<EEE
        ->addControl(SokoInputControl::create()
            ->setName("$col")
            ->setLabel("$label")
            ->setType("textarea")
        )
EEE
                        );
                        break;
                    default:


                        if ('tinyint(1)' === $typePrecision) {

                            $file->addBodyStatement(<<<EEE
        ->addControl(SokoBooleanChoiceControl::create()
            ->setName("$col")
            ->setLabel("$label")
            ->setValue(1)
        )
EEE
                            );
                        } else {
                            $file->addBodyStatement(<<<EEE
        ->addControl(SokoInputControl::create()
            ->setName("$col")
            ->setLabel("$label")
        )
EEE
                            );
                        }
                        break;
                }
            }
        }
    }

    //--------------------------------------------
    // HELPERS FOR SUBCLASSES
    //--------------------------------------------
    protected function getPivotLinkLabelsByPrefix($prefix, $table, array $operation)
    {
        $rightTable = OrmToolsHelper::getHasRightTable($table, $prefix);
        if (false !== $rightTable) {


            $label = $operation['elementLabel'];
            $labelPlural = $this->dictionary->getLabel($rightTable, true);

            if (false === $label) {
                $label = "";
                XLog::error("[NullosAdmin Module] - NullosFormConfigFileGenerator: label not found for table $rightTable");
            } elseif (false === $labelPlural) {
                $labelPlural = "";
                XLog::error("[NullosAdmin Module] - NullosFormConfigFileGenerator: label plural not found for table $rightTable");
            }
        } else {
            throw new NullosException("Cannot find the right part of the has table $table");
        }
        return [
            $label,
            $labelPlural,
        ];
    }


    protected function doPrepareColumnControl(PhpFile $file, $params, array $config)
    {
        return false;
    }


    protected function doPrepareForeignKeyControl(PhpFile $file, $params, array $config)
    {
        $col = $params['column'];
        $fkey = $params['fkey'];
        $label = $params['label'];
        $isInRic = $params['isInRic'];

        $sValue = '';
        if ($isInRic) {
            $sValue = '
->setValue($' . $col . ')                
                ';
        }

        $fTable = $fkey[0] . "." . $fkey[1];
        $fCol = $fkey[2];
        $prettyColumn = OrmToolsHelper::getPrettyColumn($fTable);

        $file->addHeadStatement(<<<EEE
\$choice_$col = QuickPdo::fetchAll("select $fCol, concat($fCol, '. ', $prettyColumn) as label from $fTable", [], \PDO::FETCH_COLUMN|\PDO::FETCH_UNIQUE);
EEE
        );

        $file->addBodyStatement(<<<EEE
        ->addControl(SokoChoiceControl::create()
            ->setName("$col")
            ->setLabel('$label')
            ->setChoices(\$choice_$col)
            ->setProperties([
                'readonly' => \$isUpdate,
            ])
            $sValue
        )
EEE
        );
    }
}