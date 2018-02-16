<?php


namespace Module\NullosAdmin\Morphic\Generator\ConfigFile;


use ArrayToString\ArrayToStringTool;
use Bat\CaseTool;
use Kamille\Utils\Morphic\Generator\ConfigFileGenerator\AbstractConfigFileGenerator;
use Kamille\Utils\Morphic\Generator\GeneratorHelper\MorphicGeneratorHelper;
use Module\NullosAdmin\Exception\NullosException;
use OrmTools\Helper\OrmToolsHelper;
use PhpFile\PhpFile;
use QuickPdo\QuickPdoInfoTool;

class NullosListConfigFileGenerator extends AbstractConfigFileGenerator
{

    //--------------------------------------------
    //
    //--------------------------------------------
    public function getConfigFileContent(array $operation, array $config = [])
    {

        $file = PhpFile::create();
        $this->onPhpFileReady($file, $operation);

        $dbPrefixes = (array_key_exists("dbPrefixes", $config)) ? $config['dbPrefixes'] : [];
        $ric = $operation['ric'];
        $name = $operation['elementName'];
        $route = $operation['elementRoute'];
        $ucLabelPlural = ucfirst($operation['elementLabelPlural']);
        $table = $operation['elementTable'];
        $elementType = MorphicGeneratorHelper::getElementType($operation);


        $columns = $operation['columns'];
        $columnTypes = $operation['columnTypes'];


        foreach ($columns as $k => $col) {
            // we don't show blobs: too long to load...
            if (false !== strpos($columnTypes[$col], 'blob')) {
                unset($columns[$k]);
            }
        }


        $columnFkeys = $operation['columnFkeys'];
        $fTables = [];
        foreach ($columnFkeys as $info) {
            $fTables[] = $info[1];
        }
        $fTable2Alias = OrmToolsHelper::getAliases($fTables, $dbPrefixes, ['h']);
        $operation['fTable2Alias'] = $fTable2Alias;


        $t = str_repeat("\t", 2);

        $isContext = ('context' === $elementType);

        $contextCols = [];
        if (true === $isContext) {

            $file->addUseStatement('use Kamille\Utils\Morphic\Helper\MorphicHelper;');
            $contextCols = MorphicGeneratorHelper::getContextFieldsByHasTable($table);


            $s = '
//--------------------------------------------
// LIST WITH CONTEXT
//--------------------------------------------
';
            foreach ($contextCols as $col) {
                $s .= <<<EEE
\$$col = MorphicHelper::getFormContextValue("$col", \$context);
EEE;
            }
            $s .= <<<EEE
            
\$avatar = MorphicHelper::getFormContextValue("avatar", \$context);
EEE;

            $file->addHeadStatement($s);


        }


//        $childCols = array_diff($ric, $contextCols);


        $sqlQuery = $this->getSqlQuery($operation, $config, $contextCols);


        // headers
        $headerCols = [];
        foreach ($columns as $col) {
            $headerCols[$col] = $this->col2Label($col);
        }


        if (true === $isContext) {

            $leftTable = OrmToolsHelper::getHasLeftTable($table);

            foreach ($columnFkeys as $col => $info) {
                $ftable = $info[1];
                if ($leftTable === $ftable) {
                    continue;
                }
                $prettyName = $this->getFkeyPrettyName($ftable, $dbPrefixes);
                $headerCols[$prettyName] = $this->col2Label($prettyName);
            }
        }


        $this->prepareHeaderCols($headerCols);
        $headerCols["_action"] = '';
        $headers = $this->getArraySection("headers", $headerCols);


        $file->addBodyStatement(<<<EEE
\$q = "$sqlQuery";
EEE
        );


        $this->onSqlQueryAddedAfter($file, $operation);


        // queryCols
        if (true === $isContext) {
            $queryCols = array_map(function ($v) {
                return "h." . $v;
            }, $columns);
            foreach ($columnFkeys as $col => $info) {
                $ftable = $info[1];
                if ($leftTable === $ftable) {
                    continue;
                }
                $prettyName = $this->getFkeyPrettyName($ftable, $dbPrefixes);

                $alias = $fTable2Alias[$ftable];
                $queryCols[] = $this->getFkeyPrettySqlColumn($ftable, $prettyName, $alias);
            }

            // headersVisibility
            $vis = [];
            foreach ($contextCols as $col) {
                $vis[$col] = false;
            }
            $sVis = $this->getArraySection('headersVisibility', $vis);


            // realColumnMap
            $rightTable = OrmToolsHelper::getHasRightTable($table, $dbPrefixes);
            $prettyName = $this->getFkeyPrettyName($rightTable, $dbPrefixes);
            $alias = $fTable2Alias[$rightTable];
            $rcm = [
                $prettyName => $this->getRealColumnMapEntry($rightTable, $alias)
            ];
            $sRcm = $this->getArraySection('realColumnMap', $rcm);

        } else {
            $queryCols = $columns;
            $sVis = '';
            $sRcm = '';
        }
        $this->prepareQueryCols($queryCols);
        $qCols = $this->getArraySection('queryCols', $queryCols);


        // ric
        $sRic = $this->getArraySection("ric", $ric);


        $title = $ucLabelPlural;
        $viewId = $name;


        $file->addBodyStatement(<<<EEE
\$conf = [
    //--------------------------------------------
    // LIST WIDGET
    //--------------------------------------------
    'title' => "$title",
    'table' => '$table',
    /**
     * This is actually the list.conf identifier
     */
    'viewId' => '$viewId',
    $headers
    $sVis
    $sRcm
    'querySkeleton' => \$q,
    $qCols
    $sRic
    /**
     * formRoute is just a helper, it will be used to generate the rowActions key.
     */
    'formRoute' => "$route",    
EEE
        );


        if ($isContext) {
            $file->addBodyStatement(<<<EEE
    'context' => \$context,
EEE
            );
        }


        $file->addBodyStatement(<<<EEE
];
EEE
        );


        return $file->render();
    }


    protected function onPhpFileReady(PhpFile $file, array $operation)
    {

    }


    protected function prepareHeaderCols(array &$columns)
    {

    }

    protected function prepareQueryCols(array &$columns)
    {

    }

    /**
     *
     * count(isContext) > 0 means this is a context element,
     * otherwise it's a simple element.
     *
     * @param array $operation
     * @param array $config
     * @param array $contextCols
     * @return string
     * @throws \Exception
     *
     */
    protected function getSqlQuery(array $operation, array $config, array $contextCols = [])
    {
        $table = $operation['elementTable'];
        $dbPrefixes = (array_key_exists("dbPrefixes", $config)) ? $config['dbPrefixes'] : [];


        // simple element type
        if ('simple' === $operation['elementType']) {
            return 'select %s from `' . $table . '`';
        }

        // context element type
        $s = 'select %s from `' . $table . '` h';


        $rightTable = OrmToolsHelper::getHasRightTable($table, $dbPrefixes);

        if (false !== $rightTable) {
            $fTable2Alias = $operation['fTable2Alias'];
            $columnFkeys = $operation['columnFkeys'];
            foreach ($columnFkeys as $fkey => $info) {

                $ftable = $info[1];


                if ($rightTable === $ftable) {

                    $alias = $fTable2Alias[$ftable];
                    $s .= ' 
inner join ' . $rightTable . ' ' . $alias . ' on ' . $alias . '.' . $info[2] . '=h.' . $fkey;
                }
            }
        } else {
            throw new NullosException("right table not found for table $table");
        }


        $s .= '
where ';
        $c = 0;
        foreach ($contextCols as $col) {
            if (0 !== $c++) {
                $s .= ' and ';
            }
            $s .= 'h.' . $col . '=$' . $col . PHP_EOL;
        }
        return $s;

    }


    protected function getArraySection($title, array $cols)
    {
        return '"' . $title . '" => ' . ArrayToStringTool::toPhpArray($cols, null, 4) . ',';
    }


    protected function col2Label($column)
    {
        return ucfirst(str_replace('_', ' ', $column));
    }

    protected function getFkeyPrettyName($ftable, array $dbPrefixes = [])
    {
        foreach ($dbPrefixes as $prefix) {
            if (0 === strpos($ftable, $prefix)) {
                return substr($ftable, strlen($prefix));
            }
        }
        return $ftable;
    }


    /**
     * @param $table
     * @param $prettyName
     * @return string,
     * something like
     *          concat(a.id, a.name) as user
     */
    protected function getFkeyPrettySqlColumn($table, $prettyName, $alias)
    {
        $s = 'concat(';

        $ai = QuickPdoInfoTool::getAutoIncrementedField($table);
        $repr = $alias . "." . OrmToolsHelper::getRepresentativeColumn($table);

        if (false !== $ai) {
            $s .= $alias . '.' . $ai . ", ";
        }
        $s .= $repr;

        $s .= ') as ' . $prettyName;
        return $s;
    }


    protected function getRealColumnMapEntry($table, $alias)
    {
        $ai = QuickPdoInfoTool::getAutoIncrementedField($table);
        $colTypes = QuickPdoInfoTool::getColumnDataTypes($table);

        /**
         * Note: we can return either an array of a simple string.
         * Reminder: the first element should be the element we sort with.
         */
        $repr = OrmToolsHelper::getRepresentativeColumn($table);

        $ret = [
            $alias . '.' . $repr,
        ];
        foreach ($colTypes as $col => $type) {
            if ('varchar' === $type && $col !== $repr) {
                $ret[] = $alias . '.' . $col;
            }
        }

        if ($ai) {
            $ret[] = $alias . '.' . $ai;
        }
        return $ret;

    }


    protected function onSqlQueryAddedAfter(PhpFile $file, array $operation)
    {

    }
}