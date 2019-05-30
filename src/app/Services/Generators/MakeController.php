<?php


namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MakeController
{

    protected $tablesNames = [];
    protected $alreadyMade = [];
    protected $realMade = [];

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

    public function __construct()
    {
        $tables = new Table();
        $this->tablesNames = $tables->getTablesNames();
        $this->writeControllers();
        $this->writeBaseController();

    }


    public function writeControllers()
    {

        $str = "";

        $arrAlreadyMade = config('alex-claimer-generator.already_made.controllers');
        foreach ($this->tablesNames as $tName => $cNames) {
            $ClassName = Helper::className($tName) . "Controller";

            if (!is_array($arrAlreadyMade) || !in_array($ClassName, $arrAlreadyMade)) {
                $this->realMade[] = $this->alreadyMade[] = $ClassName;
                $str = "<?php\r\nnamespace " . Helper::makeNameSpace('controller') .
                    ";\r\n\r\n";

                $str .= "use " . Helper::makeNameSpace('model') . '\\' . Helper::className($tName) . ";\r\n\r\n";


                $str .= "class " . $ClassName . " extends BaseController
{   
 
    
}";

                file_put_contents(Helper::makeFileDirName('controller', $ClassName), $str);

            }
        }
        return $str;
    }

    public function writeBaseController()
    {

        $str = "";

        // $str = File::prepend( __DIR__.'\GeneratorMiddleware\Templates\Model\ModelBegin.php', 'ssssssssss');//11??
        $arrAlreadyMade = config('alex-claimer-generator.already_made.controllers');

        $ClassName = "Base" . "Controller";

        if (!is_array($arrAlreadyMade) || !in_array($ClassName, $arrAlreadyMade)) {
            $this->realMade[] = $this->alreadyMade[] = $ClassName;
            $str = "<?php\r\nnamespace " . Helper::makeNameSpace('controller') . ";\r\n\r\n";
            $str .= "";
            $str .= "use App\Http\Controllers\Controller;\r\n\r\n";


            $str .= "abstract class " . $ClassName . " extends Controller
{ 
         
}";

            file_put_contents(Helper::makeFileDirName('controller', $ClassName), $str);
        }
        return $str;
    }
}

