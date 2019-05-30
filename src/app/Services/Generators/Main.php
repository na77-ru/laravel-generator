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
        if (config('alex-claimer-generator.config.generate_models')) {
            $this->setAlreadyMade(new MakeModel(), 'models');
        }
        if (config('alex-claimer-generator.config.generate_controllers')) {
            $this->setAlreadyMade(new MakeController(), 'controllers');
        }
        if (config('alex-claimer-generator.config.generate_repositories')) {
            $this->setAlreadyMade(new MakeRepository(), 'repositories');
        }
        if (config('alex-claimer-generator.config.generate_observers')) {
            $this->setAlreadyMade(new MakeObserver(), 'observers');
        }
        if (config('alex-claimer-generator.config.generate_requests')) {
            $this->setAlreadyMade(new MakeRequest(), 'requests');
        }
//        if (config('alex-claimer-generator.config.generate_views')) {
//            $this->setAlreadyMade(new MakeRequest(), 'views');
//        }


        $this->writeAlreadyMade();//11 uncomment
        echo('All classes generated successfully.');
        dd($this->realMade);//111 //11??

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
        $this->alreadyMade = Arr::sort($this->alreadyMade);

        $str_alreadyMade = "<?php\r\nreturn [\r\n";
        foreach ($this->alreadyMade as $type => $arr) {

            $str_alreadyMade .= "    '$type' => [\r\n";

            foreach ($arr as $name) {
                $str_alreadyMade .= "        '" . $name . "',\r\n";

            }
            $str_alreadyMade .= "   ],\r\n";
        }
        $str_alreadyMade .= "];";


        file_put_contents(base_path() . '\config\alex-claimer-generator\already_made.php', $str_alreadyMade);

    }


}
