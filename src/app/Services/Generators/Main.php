<?php

namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Arr;

class Main
{

    /**
     * @var array
     */
    protected $alreadyMade = [];

    protected $realMade = [];

    /**
     * Main constructor.
     */
    public function __construct()
    {
        $tableObj = new Table();

        if (config('alex-claimer-generator.config.generate_models')) {
            $this->setAlreadyMade(new MakeModel($tableObj), 'models');
        }
        if (config('alex-claimer-generator.config.generate_controllers')) {
            $this->setAlreadyMade(new MakeController($tableObj), 'controllers');
        }
        if (config('alex-claimer-generator.config.generate_repositories')) {
            $this->setAlreadyMade(new MakeRepository($tableObj), 'repositories');
        }
        if (config('alex-claimer-generator.config.generate_observers')) {
            $this->setAlreadyMade(new MakeObserver($tableObj), 'observers');
        }
        if (config('alex-claimer-generator.config.generate_requests')) {
            $this->setAlreadyMade(new MakeRequest($tableObj), 'requests');
        }
        if (config('alex-claimer-generator.config.generate_views')) {
            $this->setAlreadyMade(new MakeView($tableObj), 'views');
        }
        if (config('alex-claimer-generator.config.generate_routes')) {
            $this->setAlreadyMade(new MakeRoute($tableObj), 'routes');
        }
        if (config('alex-claimer-generator.config.generate_providers')) {
        $this->setAlreadyMade(new MakeProvider($tableObj), 'providers');
    }


        $this->writeAlreadyMade();//11 uncomment


        // cd packages/AlexClaimer/Generator
        // cd ../../../

    }

    protected function setAlreadyMade($class, $class_type)
    {
        $this->alreadyMade[$class_type] = Arr::sort(Helper::addArr(
            config('alex-claimer-generator.already_made.' . $class_type),
            $class->getAlreadyMade()
        ));

        $this->realMade[$class_type] = Arr::sort($class->getRealMade());
        // bbb(__METHOD__, $class_type, $class);

    }


    /**
     *
     */
    protected function writeAlreadyMade()
    {
        $arrAlreadyMadeSeeders = config('alex-claimer-generator.already_made.seeders');
        $this->alreadyMade['seeders'] = $arrAlreadyMadeSeeders;

        $this->alreadyMade = Arr::sort($this->alreadyMade);
        //dd(__METHOD__, $this->alreadyMade);
        $str_alreadyMade = "<?php\r\nreturn [\r\n";
        foreach ($this->alreadyMade as $type => $arr) {


            if ($arr !== null) {
                $str_alreadyMade .= "    '$type' => [\r\n";

                foreach ($arr as $name) {

                    if (is_array($name)) {
                        foreach ($name as $blade) {

                            dd(__METHOD__, $this->alreadyMade, $blade);
                            $str_alreadyMade .= "\t\t[\r\n";
                            $str_alreadyMade .= "        '" . $blade . "',\r\n";
                            $str_alreadyMade .= "\t\t],\r\n";
                        }
                    } else {
                        $str_alreadyMade .= "        '" . $name . "',\r\n";
                    }


                }
                $str_alreadyMade .= "   ],\r\n";
            }
        }
        $str_alreadyMade .= "];";


        file_put_contents(base_path() . '\config\alex-claimer-generator\already_made.php', $str_alreadyMade);

    }


}
