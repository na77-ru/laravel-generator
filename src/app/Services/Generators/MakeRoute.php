<?php
namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class MakeRoute
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
        $this->writeRoutes();
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
    public function writeRoutes()
    {

        $output = file_get_contents(base_path() . 'routes/web.php');
        dd(__METHOD__, $output);
        $arrAlreadyMade = config('alex-claimer-generator.already_made.controllers');
        foreach ($this->tablesNames as $tName => $cNames) {
            $ClassName = Helper::className($tName) . "Route";

            if (!is_array($arrAlreadyMade) || !in_array($ClassName, $arrAlreadyMade)) {
                $this->realMade[] = $this->alreadyMade[] = $ClassName;

                

               // file_put_contents(Helper::makeFileDirName('v', $ClassName), $output);
            }
        }
        return true;
    }



}

