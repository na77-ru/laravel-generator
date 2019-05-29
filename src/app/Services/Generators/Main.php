<?php

namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Arr;

class Main
{

    /**
     * @var array
     */
    protected $alreadyMade = [];

    /**
     * Main constructor.
     */
    public function __construct()
    {
        $this->setAlreadyMade(new MakeModel(), 'models');
        $this->setAlreadyMade(new MakeController(), 'controllers');
        $this->setAlreadyMade(new MakeRepository(), 'repositories');
        $this->setAlreadyMade(new MakeObserver(), 'observers');
        $this->setAlreadyMade(new MakeRequest(), 'requests');
        //$this->setAlreadyMade(new MakeView(), 'views');


        $this->writeAlreadyMade();
        exit('All classes generated successfuly.');
        dd(__METHOD__, $this->alreadyMade);

    }

    protected function setAlreadyMade($class, $class_type)
    {
        $this->alreadyMade[$class_type] = Arr::sort(Helper::addArr(
            config('alex-claimer-generator.already_made.' . $class_type),
            $class->getAlreadyMade()
        ));
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
