<?php


namespace AlexClaimer\Generator\App\Services\Generators;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Table
{
    protected $tablesNames = [];
    protected $tablesNamesData = [];
//"table_name" => array:5 [▼
//  "id" => array:8 [▼
//      "Field" => "id"
//      "Type" => "tinyint(1)" or "int(11)" or "bigint(20) unsigned"  or "varchar(191)" or "longtext" or "timestamp" or ""
//      "Null" => "NO"
//      "Key" => "PRI"
//      "Default" => null
//      "Extra" => "auto_increment"
//      "required" => "required" or ""
//      "request_required" => "required|" or ""
//      "unique" => "unique" or ""

    /**
     * @return array
     */
    public function getTablesNamesData(): array
    {
        return $this->tablesNamesData;
    }
    protected $allTablesNames = [];
    protected $belongsToKeys = [];
    protected $ignoredTables = [];
    protected $belongsToMany = [];
    protected $allRelations = [];

    /**
     * @return array|void
     */
    public function getAllRelations()
    {
        return $this->allRelations;
    }

    /**
     * @return array|void
     */
    public function getUniqueRelations()
    {
        return $this->uniqueRelations;
    }

    protected $uniqueRelations = [];


    public function __construct()
    {
        $this->ignoredTables = config('alex-claimer-generator.config.ignored_tables');

        $tables = $this->findTableNames();
        $this->tablesNames = $this->findColumnNames($tables);
        $allTables = $this->allTablesNames = $this->findAllTableNames();
        $this->allTablesNames = $this->findColumnNames($allTables);

        $this->belongsToKeys = $this->findBelongsToNames($tables, $allTables);
        $this->setBelongsToMany();

        $this->setAllRelations();


    }

    public function getPropertyNameFromRelTab($toTableName)
    {
        if ($pos = strpos($toTableName, '_'))
            return Str::singular(substr($toTableName, $pos + 1));
        else {
            return Str::singular($toTableName);
        }
    }

    protected function setAllRelations()
    {
        $allRelations = $this->belongsToMany;

        $belongsToKeys = $this->belongsToKeys;
        foreach ($belongsToKeys as $tName => $belongsToData) {
            $arrBelongsTo = $belongsToData['belongsTo'];
            if (Arr::exists($allRelations, $tName)) {
                foreach ($arrBelongsTo as $key => $data) {
                    // bbb($key, $data);
                    $toTabName = $this->findTableNameByPivotColumn($data['key']);
                    $belongsToProperty = $this->getPropertyNameFromRelTab($toTabName);
                    $allRelations[$tName][$belongsToProperty]['relatedKey'] = $data['key'];
                    $allRelations[$tName][$belongsToProperty]['to_table'] = $data['to_table'];
                    $allRelations[$tName][$belongsToProperty]['related_class'] = Helper::className($data['to_table']);
                    $allRelations[$tName][$belongsToProperty]['type'] = 'belongsTo';
//dd(__METHOD__, $this->tablesNamesData[$tName]);
                    $allRelations[$tName][$belongsToProperty]['required'] =
                        $this->getRequiredForColumn(
                            $data['key'],
                            $this->tablesNamesData[$tName][$data['key']]);
                }
            }

        }

        $this->setUniqueRelations($allRelations);
        //return $allRelations;
        //   dd(__METHOD__, $this->belongsToMany['auth_roles'], $this->belongsToKeys['auth_roles'], $allRelations['auth_roles']);
    }

    protected function setUniqueRelations($allRelations)
    {


        foreach ($allRelations as $tName => $relations) {
            foreach ($relations as $property => $relData) {
                if (!Arr::exists($relData, 'type')) {
                    $allRelations[$tName][$property]['type'] = 'belongsToMany';
                }
            }

        }
        $this->allRelations = $allRelations;
        foreach ($allRelations as $tName => $relations) {
            foreach ($relations as $property => $relData) {

                if (Arr::exists($relations, $sing = Str::singular($property))) {
                    if (Arr::exists($relations, Str::plural($sing))) {

                        unset($allRelations[$tName][$sing]);
                    }
                }

            }

        }
        $this->uniqueRelations = $allRelations;
        // dd(__METHOD__, $allRelations['auth_roles'], $this->allRelations['auth_roles'], $allRelations);
    }

    protected function setBelongsToMany()
    {
        $linkTables = [];
        foreach ($this->allTablesNames as $tName => $arrFields) {
            if (strpos(' ' . $tName, 'link_')) {

                foreach ($arrFields as $columnName => $arrColumnData) {
                    //dd(__METHOD__, $tName, $arrFields, strpos(' ' . $columnName, '_id'));
                    if (strpos(' ' . $columnName, '_id')) {
                        foreach ($this->allTablesNames[$tName] as $column => $arrData) {
                            if (strpos(' ' . $column, '_id') && $column != $columnName) {
                                if ($pos = strpos($this->findTableNameByPivotColumn($column), '_'))
                                    $belongsToManyProperty = substr($this->findTableNameByPivotColumn($column), $pos + 1);
                                else {
                                    $belongsToManyProperty = $this->findTableNameByPivotColumn($column);
                                }
                                $linkTables[$this->findTableNameByPivotColumn($columnName)] [$belongsToManyProperty] ['pivot_table'] = $tName;
                                $linkTables[$this->findTableNameByPivotColumn($columnName)] [$belongsToManyProperty] ['to_table'] = $this->findTableNameByPivotColumn($column);
                                $linkTables[$this->findTableNameByPivotColumn($columnName)] [$belongsToManyProperty] ['related_class'] = Helper::className($this->findTableNameByPivotColumn($column));
                                $linkTables[$this->findTableNameByPivotColumn($columnName)] [$belongsToManyProperty] ['relatedPivotKey'] = $column;
                                $linkTables[$this->findTableNameByPivotColumn($columnName)] [$belongsToManyProperty]['foreignPivotKey'] = $arrColumnData['name'];
                            }
                        }
                    }
                }
            }
        }
        $this->belongsToMany = $linkTables;
        //dd(__METHOD__, $this->belongsToMany);
        return $linkTables;
    }

    public function getBelongsToManyKeys()
    {
        return $this->belongsToMany;
    }

    protected function findTableNameByPivotColumn($columnName)
    {
        $partTableName = substr($columnName, 0, strpos($columnName, '_id'));
        foreach ($this->tablesNames as $tName => $arrFields) {
            if (strpos(' ' . $tName, $partTableName)) {
                return $tName;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getAllTablesNames(): array
    {
        return $this->allTablesNames;
    }

    /**
     * @return array
     */
    public function getTablesNames(): array
    {
        return $this->tablesNames;
    }

    /**
     * @return array
     */
    public function getBelongsToKeys(): array
    {
        return $this->belongsToKeys;
    }


    protected function findBelongsToNames($tables, $allTables)
    {
        $foreignKeys = [];

        foreach ($tables as $table) {
            $columns = DB::getSchemaBuilder()->getColumnListing($table);
            foreach ($columns as $column) {

                if (mb_strrpos($column, '_id')) {

                    if (isset($foreignKeys[$table]['belongsTo']))
                        $count = count($foreignKeys[$table]['belongsTo']);
                    else $count = 0;

                    $foreignKeys[$table]['belongsTo'][$count]['key'] = $column;
                    $foreignKeys[$table]['belongsTo'][$count]['to_table'] =
                        $this->getTableNameFromKeyBelongsTo($tables, $allTables, $table, $column);
                }
            }
        }

        //dd(__METHOD__, $tables, $table, $foreignKeys);
        return $foreignKeys;
    }

    /**
     * @param $tables
     * @param $key
     * @return bool|string
     */
    protected function getTableNameFromKeyBelongsTo($tables, $allTables, $tab, $key)
    {
        $key = substr($key, 0, strpos($key, '_id'));

        if ($key == 'parent') {

            return $tab;
        }
        foreach ($allTables as $table) {

            if (Str::plural($key) == $table) {

                return $table;
            }
            $tab = substr($table, strpos($table, '_') + 1);

            if (Str::plural($key) == $tab) {

                return $table;
            }


            //return $table;
        }

        return false;
    }

    /**
     * @param $cName
     * @param $data
     * @return string
     */
    protected function getRequiredForColumn($cName, $data)
    {
        if ($data['Null'] == 'NO' && $data['Default'] == null && $cName !== 'id')
            return 'required';
        else
            return '';
    }

    /**
     * @param $cName
     * @param $data
     * @return string
     */
    protected function getRequiredForColumnRequired($cName, $data)
    {
        if ($data['Null'] == 'NO' && $data['Default'] == null && $cName !== 'id')
            return 'required|';
        else
            return '';
    }

    /**
     * @param $cName
     * @param $data
     * @return string
     */
    protected function getUniqueForColumn($cName, $data)
    {
        if ($data['Key'] == 'UNI' && $cName !== 'id')
            return 'unique';
        else
            return '';
    }

    /**
     * @param $tables
     * @return array
     */
    protected function findColumnNames($tables)
    {
        $names = [];
        $fullNames = [];
        foreach ($tables as $table) {

            $arrTableData = DB::select("DESCRIBE $table");

            foreach ($arrTableData as $data) {
                $fullNames[$table][$data->Field] = json_decode(json_encode($data), true);// to Array
            }

            $names[$table] = DB::getSchemaBuilder()->getColumnListing($table);

        }
        foreach ($fullNames as $tName => $columns){
            foreach ($columns as $cName => $data){
                $fullNames[$tName][$cName]['required'] = $this->getRequiredForColumn($cName, $data);
                $fullNames[$tName][$cName]['request_required'] = $this->getRequiredForColumnRequired($cName, $data);
                $fullNames[$tName][$cName]['unique'] = $this->getUniqueForColumn($cName, $data);
            }
        }
        $this->tablesNamesData = $fullNames;
        //dd(__METHOD__,$tables, $this->tablesNamesData);
        $tNames = [];
        foreach ($names as $tName => $cNames) {

            foreach ($cNames as $cName) {
                $tNames[$tName][$cName]['name'] = $cName;
                $tNames[$tName][$cName]['type'] = strtolower(DB::connection()->getDoctrineColumn(
                    $tName, $cName)
                    ->getType());
                $tNames[$tName][$cName]['type'] = str_replace(
                    'bigint',
                    'integer',
                    $tNames[$tName][$cName]['type']);
            }

        }

        return $tNames;

    }

    /**
     * @return array
     */
    protected function findTableNames()
    {
        $arTablesNames = [];

        $tables = DB::select('SHOW TABLES');
        $db_name_key = 'Tables_in_' . config('database.connections.mysql.database');

        $only_table_with_prefix = config('alex-claimer-generator.config.only_table_with_prefix');

        $table_prefix = config('alex-claimer-generator.config.table_prefix');

        $only_this_table = config('alex-claimer-generator.config.only_this_table');

        $not_with_link_tables = config('alex-claimer-generator.config.not_with_link_tables');

        if ($only_this_table) {
            $tables = $only_this_table;
        }

        foreach ($tables as $table) {

            if (!$only_table_with_prefix ||
                ($only_table_with_prefix && strpos('_' . $table->$db_name_key, $table_prefix . '_'))) {

                $t_name = $table->$db_name_key;
                if (
                    !in_array($t_name, $this->ignoredTables) &&
                    !in_array(substr($t_name, strpos($t_name, '_') + 1), $this->ignoredTables) &&
                    !($not_with_link_tables && strpos($t_name, '_link_'))
                ) {
//                    if (strpos($t_name, 'reset'))
//                    dd(__METHOD__, substr($t_name, strpos($t_name, '_')+1));
                    $arTablesNames[] = $t_name;

                }
            }

        }
        return $arTablesNames;
    }

    /**
     * @return array
     */
    protected function findAllTableNames()
    {
        $arTablesNames = [];

        $tables = DB::select('SHOW TABLES');
        $db_name_key = 'Tables_in_' . config('database.connections.mysql.database');

        foreach ($tables as $table) {
            $arTablesNames[] = $table->$db_name_key;
        }

        return $arTablesNames;
    }

}
