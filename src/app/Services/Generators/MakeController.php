<?php

namespace AlexClaimer\Generator\App\Services\Generators;

use AlexClaimer\Generator\App\Services\Generators\MakeRoutes\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class MakeController
{

    protected $tablesNames = [];
    protected $alreadyMade = [];
    protected $realMade = [];
    protected $tables;
    protected $belongsToKeys = [];
    protected $belongsToMany = [];
    protected $allRelations = [];
    protected $uniqueRelations = [];

    /**
     * MakeController constructor.
     * @param Table $tables
     */
    public function __construct(Table $tables)
    {
        $this->tables = $tables;
        $this->tablesNames = $tables->getTablesNames();

        $this->belongsToKeys = $tables->getBelongsToKeys();
        $this->belongsToMany = $tables->getBelongsToManyKeys();
        $this->allRelations = $tables->getAllRelations();
        $this->uniqueRelations = $tables->getUniqueRelations();
        $this->writeControllers();
        $this->writeBaseController();

    }

    /**
     * @return array
     */
    public function getRealMade(): array
    {
        return $this->realMade;
    }

    /**
     * @return array
     */
    public function getAlreadyMade(): array
    {
        return $this->alreadyMade;
    }

    public function getColumnsForManyListForCreate($tName)
    {
        foreach ($this->tablesNames[$tName] as $columnName => $val) {
            if ($columnName === 'name') return "['id', 'name']";
        }
        foreach ($this->tablesNames[$tName] as $columnName => $val) {
            if ($columnName === 'title') return "['id', 'title']";
        }
        foreach ($this->tablesNames[$tName] as $columnName => $val) {
            if ($columnName === 'slug') return "['id', 'slug']";
        }
        return ['id'];
    }

    /**
     * @param $table
     * @return string
     */
    public function writeBelongsToManyListForCreate($tName)
    {
        if (!Arr::exists($this->uniqueRelations, $tName)) return '';

        $str = "";
        foreach ($this->uniqueRelations[$tName] as $property => $arrBelongs) {

            $columns = $this->getColumnsForManyListForCreate($arrBelongs['to_table']);

            //if ($tName == 'auth_roles') bbb($columns, $property);///comment

            $str .= "\t\t\$" . $arrBelongs['to_table'] .
                "List = \$this->" .
                lcfirst(Helper::className($arrBelongs['to_table'], 'Repository')) .
                "->getForSelect(" . $columns . ");\r\n";
        }

        //if ($tName == 'auth_roles') dd(__METHOD__, $tName);///comment

        return $str;
    }

    /**
     * @param $table
     * @return string
     */
    public function writeBelongsToManyCompact($tName)
    {
        if (!Arr::exists($this->uniqueRelations, $tName)) return '';
        $str = "compact('item', ";
        $count = count($this->uniqueRelations[$tName]);
        $i = 0;
        foreach ($this->uniqueRelations[$tName] as $property => $arrBelongs) {
            $str .= "'" . $arrBelongs['to_table'] . "List'";
            if ($i++ !== $count) {
                $str .= ",";
            }
        }
        $str .= ")\r\n\t\t";
        return $str;
    }

    /**
     * @param $table
     * @return string
     */
    public function writeBelongsToManyUse($tName, $str)
    {
        $str .= "use " . Helper::makeNameSpace('repository') . "\\" .
            Helper::className($tName, 'Repository') . ";\r\n";


        if (!Arr::exists($this->uniqueRelations, $tName)) return '';
        foreach ($this->uniqueRelations[$tName] as $property => $arrBelongs) {
            if ($tName !== $arrBelongs['to_table']) {
                $str .= "use " . Helper::makeNameSpace('repository') . "\\" .
                    Helper::className($arrBelongs['to_table'], 'Repository') . ";\r\n";
            }


        }
        return $str;
    }

    private function writeRepositoryVars($tName)
    {
        if (!Arr::exists($this->uniqueRelations, $tName)) return '';
        $str = "\tprivate \$" . lcfirst(Helper::className($tName, 'Repository')) . ";\r\n";
        foreach ($this->uniqueRelations[$tName] as $property => $arrBelongs) {
            if ($tName !== $arrBelongs['to_table']) {
                $str .= "\tprivate \$" . lcfirst(Helper::className($arrBelongs['to_table'], 'Repository')) . ";\r\n";
            }
        }
        return $str;
    }

    private function writeRepositoryVarsInConstructor($tName)
    {
        if (!Arr::exists($this->uniqueRelations, $tName)) return '';
        $str = "\t\t\$this->" . lcfirst(Helper::className($tName, 'Repository')) . " = app(" . Helper::className($tName, 'Repository') . "::class);\r\n";
        foreach ($this->uniqueRelations[$tName] as $property => $arrBelongs) {
            if ($tName !== $arrBelongs['to_table']) {
                $str .= "\t\t\$this->" . lcfirst(Helper::className($arrBelongs['to_table'], 'Repository')) . " = app(" . Helper::className($arrBelongs['to_table'], 'Repository') . "::class);\r\n";
            }
        }
        return $str;
    }

    /**
     * @return bool
     */
    public function writeControllers()
    {
        $stub = 'controller.stub';

        $arrAlreadyMade = config('alex-claimer-generator.already_made.controllers');
        foreach ($this->tablesNames as $tName => $cNames) {

            $className = Helper::className($tName) . "Controller";
            $nameSpace = Helper::makeNameSpace('model');
            $fullClassName = $nameSpace . "\\" . $className;

            if (!is_array($arrAlreadyMade) || !in_array($fullClassName, $arrAlreadyMade)) {
                $this->realMade[] = $this->alreadyMade[] = $fullClassName;

                $output = $this->strings_replace($tName, $cNames, $stub);

                file_put_contents(Helper::makeFileDirName('controller', $className), $output);
            }
        }
        return true;
    }

    /**
     * @param $tName
     * @param $cNames
     * @param $stub
     * @return false|mixed|string
     */
    protected function strings_replace($tName, $cNames, $stub)
    {
        $postfix = Helper::getPostfix();
        $output = file_get_contents(__DIR__ . '/Stubs/Controllers/' . $stub);

        $output = str_replace('{{namespace}}', "namespace " .
            Helper::makeNameSpace('controller') .
            ";", $output);

        $str_use = '';
        $classNameRequest = Helper::className($tName, "StoreRequest");
        $NameSpaceRequest = Helper::makeNameSpace('request');

        //??$str_use .= "use " . Helper::makeNameSpace('model') . '\\' . $className = Helper::className($tName) . " as Model;\r\n";
        $str_use .= "use " . $NameSpaceRequest . "\\" . $classNameRequest . ";\r\n";

        $str_use = $this->writeBelongsToManyUse($tName, $str_use);
        $output = str_replace('{{use}}', $str_use, $output);

        $output = str_replace('{{repositoryVars}}',
            $this->writeRepositoryVars($tName),
            $output);
        $output = str_replace('{{writeRepositoryVarsInConstructor}}',
            $this->writeRepositoryVarsInConstructor($tName),
            $output);

        $output = str_replace(
            '{{ $lists of belongsToMany for create, edit from Repositories }}',
            $this->writeBelongsToManyListForCreate($tName),
            $output
        );
        $output = str_replace(
            '{{ compact(...) }}',
            $this->writeBelongsToManyCompact($tName),
            $output
        );


        $output = str_replace('{{BaseControllerClassName}}',
            Helper::BaseClassName($postfix) . "Controller",
            $output);
        $output = str_replace('{{ControllerClassName}}',
            Helper::className($tName, "Controller"),
            $output);
        $output = str_replace('{{RepositoryClassName}}',
            Helper::className($tName) . "Repository",
            $output);
        $output = str_replace('{{thisRepoVar}}',
            lcfirst(Helper::className($tName, 'Repository')),
            $output);


        $output = str_replace('{{ModelClass}}',
            Helper::className($tName),
            $output);
        $output = str_replace('{{ModelNameSpace}}',
            Helper::makeNameSpace('model'),
            $output);
        $output = str_replace('{{ModelClassStoreRequest}}',
            $classNameRequest,
            $output);


        $output = str_replace('{{views_directory}}',
            Helper::make_views_directory($tName),
            $output);
        $output = str_replace('{{views_routes}}',
            Route::make_routes_name($tName),
            $output);

        return $output;
    }

    /**
     * @return false|mixed|string
     */
    protected function writeBaseController()
    {

        $stub = 'base-controller.stub';
        $arrAlreadyMade = config('alex-claimer-generator.already_made.controllers');
        $postfix = Helper::getPostfix();
        $output = file_get_contents(__DIR__ . '/Stubs/Controllers/' . $stub);

        $className = Helper::BaseClassName() . "Controller";
        $nameSpace = Helper::makeNameSpace('controller');
        $fullClassName = $nameSpace . "\\" . $className;

        if (!is_array($arrAlreadyMade) || !in_array($fullClassName, $arrAlreadyMade)) {
            $this->realMade[] = $this->alreadyMade[] = $fullClassName;

            $output = $this->strings_replace('base', 'bases', $stub);

            file_put_contents(Helper::makeFileDirName('controller', $className), $output);
        }
        return $output;
    }
}

