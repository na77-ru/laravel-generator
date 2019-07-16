<?php


namespace AlexClaimer\Generator\App\Services\Generators;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class MakeMiddleWare
{

    protected $tablesNames = [];

    protected $alreadyMade = [];
    protected $realMade = [];

    protected $belongsToKeys = [];
    protected $belongsToMany = [];

    /**
     * MakeModel constructor.
     * @param Table $tables
     */
    public function __construct(Table $tables)
    {
        // dd(__METHOD__);
        $this->tablesNames = $tables->getTablesNames();
        $this->belongsToKeys = $tables->getBelongsToKeys();
        $this->belongsToMany = $tables->getBelongsToManyKeys();
        $this->writeMiddleWare();

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
     * @return string
     */
    public function writeMiddleWare()
    {
        $str = "";

        $arrAlreadyMade = config('alex-claimer-generator.already_made.Middleware');
        $tablePrefix = config('alex-claimer-generator.config.table_prefix');


        $nameSpace = config('alex-claimer-generator.config.middleware.namespace')."\\".Helper::makeNameSpace('Middleware');
        $className = ucfirst($tablePrefix) . "PermissionMiddleware";

        $fullClassName = $nameSpace . "\\" . $className;
        Helper::makeFileDirName('middleware', $fullClassName);


        if (!is_array($arrAlreadyMade) || !in_array($fullClassName, $arrAlreadyMade)) {
            $this->realMade[] = $this->alreadyMade[] = $nameSpace . "\\" . $className;


            $str .= file_get_contents(__DIR__ .
                '/Stubs/MiddleWare/permissionMiddleware.stub');
            $str = str_replace('{{ namespace }}', $nameSpace, $str);
            $str = str_replace('{{ className }}', $className, $str);

            // app/Http/Middleware/AdminAuthPermission.php
            file_put_contents(base_path() . $fullClassName . ".php", $str);

        }
        //dd(__METHOD__, $nameSpace, $className, $fullClassName);

    }


}

