<?php
namespace AlexClaimer\Generator\App\Services\Generator;

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

    /**
     * MakeController constructor.
     * @param Table $tables
     */
    public function __construct(Table $tables)
    {
        $this->tables = $tables;
        $this->tablesNames = $tables->getTablesNames();
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
        $classNameRepository = Helper::className($tName, "Repository");

        $NameSpaceRequest = Helper::makeNameSpace('request');
        $NameSpaceRepository = Helper::makeNameSpace('repository');

        $str_use .= "use " . Helper::makeNameSpace('model') . '\\' . $className = Helper::className($tName) . " as Model;\r\n";
        $str_use .= "use " . $NameSpaceRequest . "\\" . $classNameRequest . ";\r\n";
        $str_use .= "use " . $NameSpaceRepository . "\\" . $classNameRepository . ";\r\n";

        $output = str_replace('{{use}}', $str_use, $output);


        $output = str_replace('{{BaseControllerClassName}}',
            Helper::BaseClassName($postfix) . "Controller",
            $output);
        $output = str_replace('{{ControllerClassName}}',
            Helper::className($tName, "Controller"),
            $output);
        $output = str_replace('{{RepositoryClassName}}',
            Helper::className($tName) . "Repository",
            $output);
        $output = str_replace('{{repositoryVar}}',
            lcfirst(Helper::className($tName) . "Repository"),
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
            Helper::make_views_routes_name($tName),
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

