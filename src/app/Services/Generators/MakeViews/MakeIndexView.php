<?php

namespace AlexClaimer\Generator\App\Services\Generators\MakeViews;

use AlexClaimer\Generator\App\Services\Generators\MakeRoutes\Route;
use AlexClaimer\Generator\App\Services\Generators\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class MakeIndexView
{
    protected $tablesNamesData = [];
    protected $allRelations = [];

    protected $allRelationsDot = [];

    /**
     * MakeIndexView constructor.
     * @param $tables
     * @param $tName
     * @param $cNames
     * @param $bladeName
     */
    public function __construct($tables, $tName, $cNames, $bladeName)
    {
        $this->tablesNamesData = $tables->getTablesNamesData();
        $this->allRelations = $tables->getAllRelations();

        $this->writeBlade($tName, $cNames, $bladeName);
    }

    /**
     * @param $tName
     * @param $cNames
     * @param $bladeName
     */
    protected function writeBlade($tName, $cNames, $bladeName): void
    {
        $output = file_get_contents(__DIR__ . '/../Stubs/Views/index.blade.stub');

        $output = $this->strings_replace($tName, $cNames, $output);

        file_put_contents(Helper::makeFileDirName('view', $bladeName, $tName), $output);
    }

    /**
     * @param $tName
     * @param $cNames
     * @param $output
     * @return mixed
     */
    protected function strings_replace($tName, $cNames, $output)
    {
        View::getPostfixPrefix($postfix, $prefix);

        if (empty($postfix)) {
            $output = str_replace('{{postfix}}', '', $output);
            $output = str_replace('{{postfix.app}}', 'app', $output);
        } else {

            $output = str_replace('{{postfix}}', Route::make_routes_prefix(), $output);
            $output = str_replace('{{postfix.app}}', lcfirst($prefix) . '.app', $output);
        }

        $output = str_replace('{{route_name_without_action_and_\')}} }}', '{{route(\'' . Route::make_routes_name($tName) . '', $output);
        $output = str_replace('{{postfix/}}', $postfix . '/', $output);
        $output = str_replace('{{table_name}}', $tName, $output);
        $output = str_replace('{{ModelNameSpace}}', Helper::fullNameSpace($tName), $output);
        $output = str_replace('{{<thead><td>}}', $this->theadIndex($tName, $cNames), $output);
        $output = str_replace('{{<tr><td>}}', $this->tdIndex($tName, $cNames), $output);

        $output = str_replace('{{belongsToComment}}', '', $output); //11 replace with something


        return $output;
    }

    /**
     * @param $tableName
     * @param string $str
     * @return string
     */
    protected function theadIndexRelations($tableName, $str = '')
    {
        if (Arr::exists($this->allRelations, $tableName)) {

            foreach ($this->allRelations[$tableName] as $property => $relData) {
                $str .= "\t\t\t\t\t\t\t\t<th class='rel'>{{ __('{$property}') }}</th>\r\n";
            }
        }

        return $str;
    }

    /**
     * @param $columns
     * @param $postfix
     * @return string
     */
    protected function theadIndex($tableName, $columns)
    {

        $ignored_columns = View::getIgnoredColumns('index');
        $str = "\t\t\t\t\t\t\t\t<th>#</th>\r\n";
        $ii = 0;
        $bRel = false;
        foreach ($columns as $column) {
            if (!in_array($column['name'], $ignored_columns)){
                if (strpos($column['name'], '_at') && $ii < count($columns)) {
                    $ii = count($columns);
                    $bRel = true;
                }
                if ($ii++ === count($columns) - 1 || $bRel) {
                    $bRel = false;
                    $str .= $this->theadIndexRelations($tableName);
                }
                if ($column['name'] != 'id' && !strpos($column['name'], '_id')) {
                    $str .= "\t\t\t\t\t\t\t\t<th class='col'>{{ __('" . $column['name'] . "') }}</th>\r\n";
                }
            }else{$ii++;}
        }
        return $str;
    }

    /**
     * @param $columns
     * @param $postfix
     * @return string
     */
    protected function tdIndex($tableName, $columns)
    {
        $ignored_columns = View::getIgnoredColumns('index');
        $str = "\t\t\t<tr @if(!empty(\$item->deleted_at) || !empty(\$item->parent->deleted_at))
                style=\"color:red;\"
            @endif>\r\n";
        $ii = 0;
        $bRel = false;
        foreach ($columns as $column) {
            if (!in_array( $column['name'], $ignored_columns)) {
                if (strpos($column['name'], '_at') && $ii < count($columns)) {
                    $ii = count($columns);
                    $bRel = true;
                }
                if ($ii++ === count($columns) - 1 || $bRel) {
                    $bRel = false;
                    $str .= $this->tdIndexRelations($tableName);
                }

                if ($column['name'] == 'id') {
                    $str .= "\t\t\t<td><a href=\"{{route('" . Route::make_routes_name($tableName, 'edit') . "', \$item->id)}}\">
                                            {{ \$item->id }}
                </a>
           </td>\r\n";
                } elseif (!strpos($column['name'], '_id')) {
                    $str .= "\t\t\t<td>{{ \$item->" . $column['name'] . " }}</td>\r\n";
                }
            }else{$ii++;}
        }
        $str .= "</tr>";
        return $str;
    }

    /**
     * @param $tableName
     * @param string $str
     * @return string
     */
    protected function tdIndexRelations($tableName, $str = '')
    {
        if (Arr::exists($this->allRelations, $tableName)) {

            foreach ($this->allRelations[$tableName] as $property => $relData) {
                $str .= "\t\t\t\t\t\t\t\t<td>\r\n";
                //if($tableName == 'auth_roles')bbb($relData);
                if ($relData['type'] == 'belongsToMany') {
                    $str .= "\t\t\t\t\t\t\t\t\t@include('inc.form.relations', ['relations' => \$item->$property]) ";
                } elseif ($relData['type'] == 'belongsTo') {
                    $str .= "\t\t\t\t\t\t\t\t\t{{\$item->" . $property . "['" .
                        View::getColumnName($this->tablesNamesData[$relData['to_table']]) .
                        "']}}";
                }


                $str .= "\r\n\t\t\t\t\t\t\t\t</td>\r\n";
            }
            //if($tableName == 'auth_roles')dd(__METHOD__, $this->allRelations[$tableName]);
        }

        return $str;
    }
}

