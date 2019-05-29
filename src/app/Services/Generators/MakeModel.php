<?php


namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
class MakeModel
{

    protected $tablesNames = [];
    protected $belongsToKeys = [];

    protected $alreadyMade = [];


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
        $this->belongsToKeys = $tables->getBelongsToKeys();
        $this->writeModels();
        $this->writeBaseModel();
    }

    public function writeBelongsTo($table)
    {
        $str = "";
        $strrr = "
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/\r\n";
      // if ($table == 'users') dd(__METHOD__, $this->belongsToKeys, $table, Arr::has($this->belongsToKeys, $table));//11
       // else ccc(__METHOD__, $this->belongsToKeys, $table);//11
        //if (($this->belongsToKeys[$table]) !== null) {
            if (Arr::has($this->belongsToKeys, $table) ) {
            foreach ($this->belongsToKeys[$table]['belongsTo'] as $belongsTable) {

                //dd(__METHOD__,$this->belongsToKeys[$table]['belongsTo'], $belongsTable, $table, Helper::className($belongsTable['to_table']));
                $str .= $strrr;
                $str .= "    public function " . Helper::makeFuncBelongsTo($belongsTable['key']) . "()\r\n";
                $str .= "    {\r\n";

                $str .= "        return \$this->belongsTo(";
                $str .= Helper::className($belongsTable['to_table']);
                $str .= "::class, '";
                $str .= $belongsTable['key'];
                $str .= "');\r\n";

                $str .= "    }\r\n";
            }
            return $str;
        }

        return "";
    }

    /**
     * @return string
     */
    public function writeModels()
    {

        $str = "";

        // $str = File::prepend( __DIR__.'\GeneratorMiddleware\Templates\Model\ModelBegin.php', 'ssssssssss');//11??
        $arrAlreadyMade = config('alex-claimer-generator.already_made.models');
        foreach ($this->tablesNames as $tName => $cNames) {
            $ClassName = Helper::className($tName);

            if (!is_array($arrAlreadyMade) || !in_array($ClassName, $arrAlreadyMade)) {
                $this->alreadyMade[] = $ClassName;
                $str = "<?php\r\nnamespace " . Helper::makeNameSpace('model') .
                    ";\r\n\r\n";

                $str .= "/**\r\n";
                $str .= " * This is the model class for table \"{{%$tName}}\".\r\n *\r\n";
                //$str .= File::prepend( __DIR__.'\GeneratorMiddleware\Templates\Model\ModelBegin.php', $tName);
                foreach ($cNames as $cName) {
                    $str .= " * @property " . $cName['type'] . " " . $cName['name'] . "\r\n";
                }
                $str .= " */\r\n";


                $str .= "class " . $ClassName . " extends BaseModel
{   
    protected \$table = '$tName';
    public \$timestamps = false;
    
    /**
     * @var array
     */
    protected \$fillable = [\r\n";

                foreach ($cNames as $cName) {
                    $str .= "        '" . $cName['name'] . "',\r\n";
                }

                $str .= "       'is_slugChange', //is`t in table\r\n";

                $str .= "];\r\n\r\n";

                $str .= "    /**
    * @var array
    */
    protected \$comparable = [\r\n";

                foreach ($cNames as $cName) {
                    $str .= "        '" . $cName['name'] . "',\r\n";
                }


                $str .= "    ];\r\n";

                $str .= $this->writeBelongsTo($tName);

                $str .= "\r\n}";
                file_put_contents(Helper::makeFileDirName('model', $ClassName), $str);

            }
        }
        return $str;
    }

    public function writeBaseModel()
    {

        $str = "";

        // $str = File::prepend( __DIR__.'\GeneratorMiddleware\Templates\Model\ModelBegin.php', 'ssssssssss');//11??
        $arrAlreadyMade = config('alex-claimer-generator.already_made.models');

        $ClassName = "BaseModel";

        if (!is_array($arrAlreadyMade) || !in_array($ClassName, $arrAlreadyMade)) {
            $this->alreadyMade[] = $ClassName;
            $str = "<?php\r\nnamespace " . Helper::makeNameSpace('model') . ";\r\n\r\n";
            $str .= "use Illuminate\Database\Eloquent\Model;\r\n";
            $str .= "use Illuminate\Database\Eloquent\SoftDeletes;\r\n\r\n";


            $str .= "abstract class " . $ClassName . " extends Model
{ 
        use SoftDeletes;  
}";

            file_put_contents(Helper::makeFileDirName('model', $ClassName), $str);
        }
        return $str;
    }
}

