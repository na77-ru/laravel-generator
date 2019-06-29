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

        $output_web = file_get_contents(base_path() . '\routes\web.php');
        $output = '';
        $stub = 'route.stub';
        // dd(__METHOD__, $output_web);
        $arrAlreadyMade = config('alex-claimer-generator.already_made.routes');
        foreach ($this->tablesNames as $tName => $cNames) {
            $ClassName = Helper::className($tName) . "Route";

            if (!is_array($arrAlreadyMade) || !in_array($ClassName, $arrAlreadyMade)) {
                $this->realMade[] = $this->alreadyMade[] = $ClassName;
                $output .= file_get_contents(__DIR__ . '/Stubs/Routes/' . $stub);


                $output = str_replace('{{ModelClassName}}',
                    Helper::className($tName),
                    $output);


                $output = str_replace('{{ControllerClassName}}',
                    Helper::getPostfix() . '\\' . Helper::className($tName, 'Controller'),
                    $output);


                $output = str_replace('{{make_views_routes_url}}',
                    Helper::make_views_routes_url($tName),
                    $output);


                $output = str_replace('{{make_views_routes_name}}',
                    Helper::make_views_routes_name($tName),
                    $output);

            }
        }
        file_put_contents(base_path() . '\routes\web.php', $output_web . $output);
        return true;
    }


}

