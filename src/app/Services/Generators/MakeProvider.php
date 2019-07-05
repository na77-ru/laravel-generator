<?php

namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class MakeProvider
{

    protected $tablesNames = [];
    protected $alreadyMade = [];
    protected $realMade = [];
    protected $tables;

    /**
     * MakeRoute constructor.
     * @param Table $tables
     */
    public function __construct(Table $tables)
    {
        $this->tables = $tables;
        $this->tablesNames = $tables->getTablesNames();
        $this->writeProviders();
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
    public function writeProviders()
    {
        $stub = 'provider.stub';
        $output = file_get_contents(__DIR__ . '/Stubs/Providers/' . $stub);


        $arrAlreadyMade = config('alex-claimer-generator.already_made.providers');

        $className = Helper::BaseClassName() . "ServiceProvider";
        $nameSpace = Helper::makeNameSpace('provider');
        $fullClassName = $nameSpace . "\\" . $className;

        if (!is_array($arrAlreadyMade) || !in_array($fullClassName, $arrAlreadyMade)) {
            $this->realMade[] = $this->alreadyMade[] = $fullClassName;

            $output = str_replace('{{namespace}}', "namespace " .
                Helper::makeNameSpace('provider') .
                ";", $output);
            $str_use = '';
            foreach ($this->tablesNames as $tabName => $colNames) {
                $str_use .= "use " . Helper::makeNameSpace('model') . "\\" . Helper::className($tabName) . ";\r\n";
                $str_use .= "use " . Helper::makeNameSpace('observer') . "\\" . Helper::className($tabName, 'Observer') . ";\r\n";
            }
            $output = str_replace('{{use}}', $str_use, $output);

            $output = str_replace('{{ClassServiceProvider}}', $className, $output);
            $str_use = '';
            foreach ($this->tablesNames as $tabName => $colNames) {
                $str_use .= "\t\t" . Helper::className($tabName) . "::observe(" . Helper::className($tabName, 'Observer') . "::class);\r\n";
            }
            $output = str_replace('{{includes_in_boot}}', $str_use, $output);

            //{{ModelClassName}}::observe({{ObserverClassName}}::class);


        //dd(__METHOD__, Helper::makeFileDirName('provider', Helper::getPostfix() . "ServiceProvider"));
        file_put_contents(Helper::makeFileDirName('provider', $className),
            $output);

        }

        return true;
    }


}

