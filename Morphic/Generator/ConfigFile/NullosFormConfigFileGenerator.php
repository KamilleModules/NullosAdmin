<?php


namespace Module\Ekom\Morphic\Generator\ConfigFile;


use Kamille\Utils\Morphic\Exception\MorphicException;
use Kamille\Utils\Morphic\Generator\ConfigFileGenerator\ConfigFileGeneratorInterface;
use Kamille\Utils\Morphic\Generator\GeneratorHelper\MorphicGeneratorHelper;

class NullosFormConfigFileGenerator implements ConfigFileGeneratorInterface
{

    protected $beginStatements;
    protected $formControls;

    public function __construct()
    {
        $this->beginStatements = [];
        $this->formControls = [];
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
        $file = __DIR__ . "/../assets/simple.form.conf.php";
        $camelCase = $operation['CamelCase'];
        $name = $operation['elementName'];


        $this->beginStatements = [];
        $this->formControls = [];

        $columns = $operation['columns'];

        $this->prepareBeginStatements($operation, $config);

        foreach ($columns as $col) {
            $this->scanControl($col, $operation, $config);
        }


//        $formControls = $this->getFormControls($operation, $config);


        $beginStatements = implode(PHP_EOL, $this->beginStatements);
        $formControls = implode(PHP_EOL, $this->formControls);


        $content = str_replace([
            'ProductGroup',
            'product_group',
            '// beginStatements',
            '// formControls',
        ], [
            $camelCase,
            $name,
            $beginStatements,
            $formControls,
        ], file_get_contents($file));
        return $content;
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    protected function prepareBeginStatements(array $operation, array $config = [])
    {
        /**
         *
         * Note to myself:
         * The main strategy to decide whether a form is in insert mode or update mode is the following:
         *
         * - if the ric keys are found in the uri, it's an update, otherwise it's an insert.
         * Note that some of the ric keys might be found in the context, and those keys
         * might be redundant with the ones find in the uri.
         *
         */
        $s = '';
        $ric = $operation['ric'];
        foreach ($ric as $ricCol) {
            $s .= $this->line('$' . $ricCol . ' = (array_key_exists(\'' . $ricCol . '\', $_GET)) ? (int)$_GET[\'' . $ricCol . '\'] : null;');
        }
        $this->beginStatements[] = $s;
    }


    protected function scanControl($column, array $operation, array $config = [])
    {
        $columnLabel = MorphicGeneratorHelper::getColumnLabel($column, $operation, $config);
        if(in_array($column, $operation['ric'])){
            $this->formControls[] = [
                "class" => 'SokoInputControl',
                "name" => $column,
                "label" => $columnLabel,
                "properties" => [
                    'readonly' => true,
                ],
                "valuePhp" => 0,
            ];
        }
        else{

        }
    }


    protected function getFormControls(array $operation, array $config = [])
    {
        $s = '';
        $cols = $operation['columns'];
        $ric = $operation['ric'];

        foreach ($cols as $col) {

            $columnLabel = MorphicGeneratorHelper::getColumnLabel($col, $operation, $config);
            //--------------------------------------------
            // GET THE RIC OUT OF THE WAY
            //--------------------------------------------
            if (true === in_array($col, $ric, true)) {
                $columnLabel = MorphicGeneratorHelper::getColumnLabel($col, $operation, $config);

                $s .= $this->line('
->addControl(SokoInputControl::create()
->setName("' . $col . '")
->setLabel(\'' . $columnLabel . '\')
->setProperties([
    \'readonly\' => true,
])
->setValue($id)
)
');
            }
            //--------------------------------------------
            // OTHER COLUMNS
            //--------------------------------------------
            else {
                $controlInfo = $this->getControlInfo($col, $operation);


                /**
                 * $addressControl = SokoAutocompleteInputControl::create()
                 * ->setAutocompleteOptions(BackFormHelper::createSokoAutocompleteOptions([
                 * 'action' => "auto.address",
                 * ]))
                 * ->setName("address_id")
                 * ->setLabel('Address id')
                 * ->setValue($addressId);
                 *
                 *
                 * if ($isUpdate) {
                 * $addressControl->setProperties([
                 * 'readonly' => true,
                 * ]);
                 * }
                 */


                $t = '
->addControl(' . $controlInfo['class'] . '::create()
    ->setName("' . $col . '")
    ->setLabel(\'' . $columnLabel . '\')
';
                $t .= '
)';
                $s .= $this->line($t);
            }
        }
        return $s;
    }


    protected function getControlInfo($column, array $operation)
    {
        $ret = [];
        $columnTypes = $operation['columnTypes'];
        $fkeys = $operation['columnFkeys'];
        $sqlType = $columnTypes[$column];
        if (array_key_exists($column, $fkeys)) {
            $ret['class'] = "SokoChoiceControl";
        } else {

            switch ($sqlType) {
                case "int":
                case "tinyint":
                case "varchar":
                    $ret['class'] = "SokoInputControl";
                    break;
                case "text":
                    $ret['class'] = "SokoInputControl";
                    $ret['type'] = "textarea";
                    break;
                default:
                    break;
            }
        }

        return $ret;
    }

    //--------------------------------------------
    //
    //--------------------------------------------

    protected function line($s, $indent = 0)
    {
        if ($indent) {
            $s = str_repeat("\t", $indent) . $s;
        }
        return $s . PHP_EOL;
    }


protected f
}