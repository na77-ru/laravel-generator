<?php


namespace AlexClaimer\Generator\App\Services\Generators;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Table
{
    protected $ignoredTables = [];

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


    protected $allTablesNames = [];
    protected $belongsToKeys = [];

    protected $belongsToMany = [];
    protected $hasMany = [];


    protected $allRelations = [];
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

        $this->setHasMany();

        $this->setAllRelations();

//        dd(__METHOD__,
//            'ignoredTables', $this->ignoredTables,
//            'tablesNames', $this->tablesNames,
//            'tablesNamesData', $this->tablesNamesData,
//            'allTablesNames', $this->allTablesNames,
//            'belongsToKeys', $this->belongsToKeys,
//            'belongsToMany', $this->belongsToMany,
//            'hasMany', $this->hasMany,
//            'allRelations', $this->allRelations,
//            'uniqueRelations', $this->uniqueRelations,
//            'END END END END END END END END END END END '
//        );

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

    protected function setHasMany()
    {
        $relativeTables = [];
        foreach ($this->allTablesNames as $tName => $arrColumns) {

            foreach ($arrColumns as $columnName => $arrColumnData) {
                //dd(__METHOD__, $tName, $arrFields, strpos(' ' . $columnName, '_id'));
                if (strpos($columnName, '_id')) {

                    if ($pos = strpos($this->findTableNameByPivotColumn($columnName), '_'))
                        $hasManyProperty = substr($this->findTableNameByPivotColumn($columnName), $pos + 1);
                    else {
                        $hasManyProperty = $this->findTableNameByPivotColumn($columnName);
                    }

                    if ($relativeTableName = $this->findTableNameByPivotColumn($columnName)) {
                        if ($this->haveToMake($relativeTableName)) {
                            $relativeTables[$relativeTableName] [$hasManyProperty] ['pivot_table'] = $tName;
                            $relativeTables[$relativeTableName] [$hasManyProperty] ['to_table'] = $relativeTableName;
                            $relativeTables[$relativeTableName] [$hasManyProperty] ['related_class'] = Helper::className($relativeTableName);
                            $relativeTables[$relativeTableName] [$hasManyProperty] ['relatedPivotKey'] = $columnName;
                            $relativeTables[$relativeTableName] [$hasManyProperty]['foreignPivotKey'] = $arrColumnData['name'];

                        }

                    }

                }
            }

        }
        $this->hasMany = $relativeTables;
        return $relativeTables;
    }

    protected function setBelongsToMany()
    {

        $pivotTables = [];
        foreach ($this->allTablesNames as $pivotName => $pivotColumns) {
            if (strpos(' ' . $pivotName, 'pivot_') || strpos(' ' . $pivotName, 'link_')) {

                foreach ($pivotColumns as $relPivotColumnName => $relPivotColumnData) {
                    //dd(__METHOD__, $pivotName, $pivotColumns, strpos(' ' . $relPivotColumnName, '_id'));
                    if (strpos(' ' . $relPivotColumnName, '_id')) {
                        foreach ($this->allTablesNames[$pivotName] as $foreignPivotColumnName => $arrData) {
                            if (strpos(' ' . $foreignPivotColumnName, '_id')) {

                                $relativeTableName = $this->findTableNameByPivotColumn($relPivotColumnName);
                                $foreignTableName = $this->findTableNameByPivotColumn($foreignPivotColumnName);

                                if (strpos(' ' . $foreignPivotColumnName, '_id') && $foreignPivotColumnName != $relPivotColumnName) {
                                    if ($pos = strpos($foreignTableName, '_'))
                                        $belongsToManyProperty = substr($foreignTableName, $pos + 1);
                                    else {
                                        $belongsToManyProperty = $foreignTableName;
                                    }
                                    if ($this->haveToMake($relativeTableName)) {
                                        $pivotTables[$relativeTableName] [$belongsToManyProperty] ['pivot_table'] = $pivotName;
                                        $pivotTables[$relativeTableName] [$belongsToManyProperty] ['to_table'] = $foreignTableName;
                                        $pivotTables[$relativeTableName] [$belongsToManyProperty] ['related_class'] = Helper::className($foreignTableName);
                                        $pivotTables[$relativeTableName] [$belongsToManyProperty] ['relatedPivotKey'] = $foreignPivotColumnName;
                                        $pivotTables[$relativeTableName] [$belongsToManyProperty]['foreignPivotKey'] = $relPivotColumnData['name'];
                                    }

                                }

                            }
                        }

                    }
                }
            }
        }
        $this->belongsToMany = $pivotTables;
        //dd(__METHOD__, $this->belongsToMany);
        return $pivotTables;
    }


    protected function findTableNameByPivotColumn($columnName, $tableName = false)
    {
        if ($columnName == 'parent_id') return $tableName;

        $partTableName = substr($columnName, 0, strpos($columnName, '_id'));
        if ($partTableName == 'author') {
            $partTableName = 'user';
        }
        foreach ($this->allTablesNames as $tName => $arrFields) {

            if (strpos(' ' . $tName, $partTableName) && !strpos(' ' . $tName, 'link_') && !strpos(' ' . $tName, 'pivot_')) {
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

//                    if (isset($foreignKeys[$table]['belongsTo']))
//                        $count = count($foreignKeys[$table]['belongsTo']);
//                    else $count = 0;

                    $toTable = $this->getTableNameFromKeyBelongsTo($tables, $allTables, $table, $column);
//                    $foreignKeys[$table]['belongsTo'][$count]['key'] = $column;
//                    $foreignKeys[$table]['belongsTo'][$count]['to_table'] = $toTable;
                    $foreignKeys[$table]['belongsTo'][$toTable]['key'] = $column;
                    $foreignKeys[$table]['belongsTo'][$toTable]['to_table'] = $toTable;
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
            if ($key === 'author') {
                $key = 'user';
            }
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
        foreach ($fullNames as $tName => $columns) {
            foreach ($columns as $cName => $data) {
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
     * @param $tableName
     * @return bool
     */
    protected function haveToMake($tableName)
    {
        $only_this_table = config('alex-claimer-generator.config.only_this_table');
        if (!empty($only_this_table) && is_array($only_this_table) && in_array($tableName, $only_this_table)) {
            //bbb(__METHOD__,$tableName, empty($only_this_table) , 'only_this_table true');
            return true;
        } elseif (!empty($only_this_table)) {
            //bbb(__METHOD__,$tableName,empty($only_this_table) , 'only_this_table false');
            return false;
        }

        if (is_array($this->ignoredTables) && in_array($tableName, $this->ignoredTables)) {
            //bbb(__METHOD__,$tableName, 'ignoredTables false 111');
            return false;
        }
        if (in_array(substr($tableName, strpos($tableName, '_') + 1), $this->ignoredTables)) {
            //bbb(__METHOD__,$tableName, 'ignoredTables false 222');
            return false;
        }//??
        $without_pivot_tables = config('alex-claimer-generator.config.without_pivot_tables');

        if (($without_pivot_tables && strpos(' ' . $tableName, 'pivot_'))) {
            //bbb(__METHOD__,$tableName, 'without_pivot_tables false');
            return false;
        }

        $only_table_with_prefix = config('alex-claimer-generator.config.only_table_with_prefix');

        $table_prefix = config('alex-claimer-generator.config.table_prefix');


        if ($only_table_with_prefix && strpos(' ' . $tableName, $table_prefix) !== 1) {
            //bbb(__METHOD__,$tableName, 'only_table_with_prefix false');
            return false;
        }

        //bbb(__METHOD__,$tableName, 'end  true');
        return true;
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

        $without_pivot_tables = config('alex-claimer-generator.config.without_pivot_tables');

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
                    !($without_pivot_tables && strpos($t_name, '_pivot_'))
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

    /**
     * @return array
     */
    public function getTablesNamesData(): array
    {
        return $this->tablesNamesData;
    }

    /**
     * @return array|void
     */
    public function getAllRelations()
    {
        return $this->allRelations;
    }

    /**
     * @return array
     */
    public function getBelongsToManyKeys()
    {
        return $this->belongsToMany;
    }

    /**
     * @return array|void
     */
    public function getUniqueRelations()
    {
        return $this->uniqueRelations;
    }
}
