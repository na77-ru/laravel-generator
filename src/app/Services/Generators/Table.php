<?php


namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Table
{
    protected $tablesNames = [];
    protected $belongsToKeys = [];

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

    protected $ignoredTables = [];

    public function __construct()
    {
        $this->ignoredTables = config('alex-claimer-generator.config.ignored_tables');

        $tables = $this->findTableNames();

        $this->tablesNames = $this->findColumnNames($tables);

        $this->belongsToKeys = $this->findBelongsToNames($tables);

//        dd(__METHOD__,
////            $this->ignoredTables,
////            $this->tablesNames,
////            $this->belongsToKeys );//11
    }

    protected function findBelongsToNames($tables)
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
                        $this->getTableNameFromKeyBelongsTo($tables, $table, $column);
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
    protected function getTableNameFromKeyBelongsTo($tables, $tab, $key)
    {
        $key = substr($key, 0, strpos($key, '_id'));

        if ($key == 'parent') {

            return $tab;
        }
        foreach ($tables as $table) {

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
    protected
    function findColumnNames($tables)
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
    protected
    function findTableNames()
    {
        $arTablesNames = [];

        $tables = DB::select('SHOW TABLES');
        $db_name_key = 'Tables_in_' . config('database.connections.mysql.database');

        $only_with_table_prefix = config('alex-claimer-generator.config.only_with_table_prefix');

        $table_prefix = config('alex-claimer-generator.config.table_prefix');

        foreach ($tables as $table) {

            if (!$only_with_table_prefix ||
                ($only_with_table_prefix && strpos('_'. $table->$db_name_key, $table_prefix . '_'))) {

                if (!in_array($table->$db_name_key, $this->ignoredTables)) {
                    $arTablesNames[] = $table->$db_name_key;

                }
            }

        }
        return $arTablesNames;
    }
}
