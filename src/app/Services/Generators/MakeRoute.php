<?php

namespace AlexClaimer\Generator\App\Services\Generators;

use AlexClaimer\Generator\App\Services\Generators\MakeRoutes\Route;
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
        Route::setOutputWeb($output_web);

        $arrAlreadyMade = config('alex-claimer-generator.already_made.routes');

        $output = "".Route::getRoutesBeginComment();
        $groupClassName = Route::getGroupForAllRoutesClassName();
        if (!is_array($arrAlreadyMade) || !in_array($groupClassName, $arrAlreadyMade)) {
            $this->realMade[] = $this->alreadyMade[] = $groupClassName;
            $output .= "\r\n\t" . Route::getGroupForAllRoutes(true)."";
        }else{
            $output .= "\r\n\t" . Route::getGroupForAllRoutes(false)."";
        }

        foreach ($this->tablesNames as $tName => $cNames) {

            $fullClassName = Route::getFullClassName($tName);

            if (!is_array($arrAlreadyMade) || !in_array($fullClassName, $arrAlreadyMade)) {
                $this->realMade[] = $this->alreadyMade[] = $fullClassName;

                $output .= "\r\n\r\n\t\t".Route::getModelRoutesBeginComment($tName)."\r\n";
                $output .= file_get_contents(__DIR__ . '/Stubs/Routes/route.stub');
                $output .= "\t\t".Route::getModelRoutesEndComment($tName);


                $output = str_replace('{{ControllerClassName}}',
                    Helper::getPostfix() . '\\' . Helper::className($tName, 'Controller'),
                    $output);


                $output = str_replace('{{make_views_routes_url}}',
                    Route::make_routes_url($tName),
                    $output);


                $output = str_replace('{{make_views_routes_name}}',
                    Route::make_routes_name($tName),
                    $output);

            }else{
                $output .= "\r\n\r\n\t\t".Route::getModelRoutesFromWebPhp($tName);
            }
        }
        $output .= "\t".Route::getEndGroupForAllRoutes();
        $output .= "\r\n".Route::getRoutesEndComment();
        //dd(__METHOD__, Route::getOutputWeb(), $output);
        file_put_contents(base_path() . '\routes\web.php', Route::getOutputWeb() . $output);
        return true;
    }


}

