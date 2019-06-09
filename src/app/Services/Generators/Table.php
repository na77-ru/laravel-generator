<?php


namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Table
{
    protected $tablesNames = [];
    protected $allTablesNames = [];
    protected $belongsToKeys = [];
    protected $ignoredTables = [];

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


    public function __construct()
    {
        $this->ignoredTables = config('alex-claimer-generator.config.ignored_tables');

        $tables = $this->findTableNames();
        $this->tablesNames = $this->findColumnNames($tables);
        $allTables = $this->findAllTableNames();
        $this->allTablesNames = $this->findColumnNames($allTables);

        $this->belongsToKeys = $this->findBelongsToNames($tables, $allTables);

//        dd(__METHOD__,
////            $this->ignoredTables,
////            $this->tablesNames,
////            $this->belongsToKeys );//11
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
     * @return array
     */
    protected function findColumnNames($tables)
    {
        $names = [];

        foreach ($tables as $table) {
            $names[$table] = DB::getSchemaBuilder()->getColumnListing($table);

        }

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

                if (!in_array($table->$db_name_key, $this->ignoredTables) &&
                     !($not_with_link_tables && strpos($table->$db_name_key , '_link_')) ) {
                    $arTablesNames[] = $table->$db_name_key;

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
