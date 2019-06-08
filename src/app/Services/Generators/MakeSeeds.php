<?php

namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use AlexClaimer\Generator\App\Services\Generator\Table;

class MakeSeeds
{

    protected $tablesNames = [];

    protected $alreadyMade = [];
    protected $realMade = [];

    /**
     * MakeModel constructor.
     * @param Table $tables
     */
    public function __construct()
    {
        $tables = new Table();
        $this->tablesNames = $tables->getTablesNames();
    }

    public function GenerateSeeders(&$message = null)
    {
        $this->generateSeedersFromStub();

        $this->writeAlreadyMade();

        return true;
    }

    protected function generateSeedersFromStub()
    {
        $param = [];
        $this->alreadyMade['seeders'] = $arrAlreadyMade = config('alex-claimer-generator.already_made.seeders');

        foreach ($this->tablesNames as $tName => $cNames) {
            $param['ModelClassName'] = Helper::className($tName);
            $param['ModelNameSpaceUse'] = Helper::makeNameSpace('model') . '\\' . $param['ModelClassName'];
            $param['SeederClassName'] = $this->getClassNameSeeder($tName);
            $param['SeederClassName'] = $this->getClassNameSeeder($tName);
            $param['SeederDirFileName'] = $this->getDirFilePutSeeds($tName);

            $param['tableName'] = $tName;
            $param['columnNames'] = $cNames;

            if (!is_array($arrAlreadyMade) || !in_array($param['SeederClassName'], $arrAlreadyMade)) {
                $this->realMade['seeders'][] = $this->alreadyMade['seeders'][] = $param['SeederClassName'];


                $output = $this->getStubSeeder($param);

                file_put_contents($this->getDirFilePutSeeds($tName), $output);

                // dd(__METHOD__, $param, $output);
            }
        }

    }

    /**
     * @return string
     */
    protected function getVariables()
    {
        $str = "\t\t\t\$title = '';\r\n";
        $str .= "\t\t\t\$createdAt = \$faker->dateTimeBetween('-3 months', '-12 days');\r\n";
        $str .= "\t\t\t\$updatedAt = \$faker->dateTimeBetween('-2 months', '-1 days');\r\n";


        return $str;
    }

    /**
     * @param $columnNames
     * @return string
     */
    protected function getFields($columnNames)
    {
        $str = '';
        foreach ($columnNames as $column) {
            if ($column['name'] == 'title') {
                $str .= "\t\t\t\t'title' => \$title,\r\n";
            } elseif ($column['name'] == 'slug') {
                $str .= "\t\t\t\t'slug' => Str::slug(\$title),\r\n";
            } elseif ($column['name'] == 'created_at') {
                $str .= "\t\t\t\t'created_at' => \$createdAt,\r\n";
            } elseif ($column['name'] == 'updated_at') {
                $str .= "\t\t\t\t'updated_at' => \$updatedAt,\r\n";
            } elseif ($column['name'] == 'password') {
                $str .= "\t\t\t\t'password' => bcrypt('admin'),\r\n";
            } elseif ($column['name'] !== 'id' && $column['name'] !== 'deleted_at') {


                $str .= "\t\t\t\t'" . $column['name'] . "' => '',\r\n";
            }
        }
        return $str;
    }

    /**
     * @param $dirFileStubSeed
     * @param $param
     * @return string
     */
    protected function getStubSeeder($param)
    {
        //dd(__METHOD__, $dirFileStubSeed, $param);
        $output = file_get_contents($this->getDirFileStubSeed());
        $output = str_replace('{{ModelNameSpaceUse}}', $param['ModelNameSpaceUse'], $output);
        $output = str_replace('{{SeederClassName}}', $param['SeederClassName'], $output);
        $output = str_replace('{{ModelClassName}}', $param['ModelClassName'], $output);
        $output = str_replace('{{tableName}}', $param['tableName'], $output);


        $output = str_replace('{{variables}}', $this->getVariables(), $output);
        $output = str_replace('{{setFields}}', $this->getFields($param['columnNames']), $output);

        return $output;
    }


    protected function writeAlreadyMade()
    {
        $this->alreadyMade['seeders'] = Arr::sort($this->alreadyMade['seeders']);

       $arrAlreadyMade = config('alex-claimer-generator.already_made');
        $arrAlreadyMade['seeders'] = $this->alreadyMade['seeders'];

        $arrAlreadyMade = Arr::sort($arrAlreadyMade);

        $str_alreadyMade = "<?php\r\nreturn [\r\n";
        foreach ($arrAlreadyMade as $type => $arr) {

            $str_alreadyMade .= "    '$type' => [\r\n";

            foreach ($arr as $name) {
                $str_alreadyMade .= "        '" . $name . "',\r\n";

            }
            $str_alreadyMade .= "   ],\r\n";
        }
        $str_alreadyMade .= "];";


        file_put_contents(base_path() . '\config\alex-claimer-generator\already_made.php', $str_alreadyMade);

    }

    protected function getDirFileStubSeed()
    {
        return __DIR__ . '/Stubs/Seeders/seeder.stub';
    }

    protected function getDirFilePutSeeds($tableName)
    {
        return base_path() . '/database/seeds/' . $this->getFileNameSeeder($tableName);
    }

    protected function getFileNameSeeder($tableName)
    {
        $seedFileName = $this->getClassNameSeeder($tableName) . '.php';
        return $seedFileName;
    }

    protected function getClassNameSeeder($tableName)
    {
        $seedFileName = ucfirst(Str::camel($tableName)) . 'TableSeeder';
        return $seedFileName;
    }

    /**
     * @return array
     */
    public function getRealMade(): array
    {
        return $this->realMade;
    }

}

