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
    protected $realMade = [];

    /**
     * MakeModel constructor.
     * @param Table $tables
     */
    public function __construct(Table $tables)
    {
        $this->tablesNames = $tables->getTablesNames();
        $this->belongsToKeys = $tables->getBelongsToKeys();
        $this->writeModels();
        $this->writeBaseModel();
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
     * @param $table
     * @return string
     */
    public function writeBelongsTo($table)
    {
        $str = "";
        $strComment = "
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/\r\n";

        if (Arr::has($this->belongsToKeys, $table)) {
            foreach ($this->belongsToKeys[$table]['belongsTo'] as $belongsTable) {

                $str .= $strComment;
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
                $this->realMade[] = $this->alreadyMade[] = $ClassName;
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
               // dd(__METHOD__, Helper::makeFileDirName('model', $ClassName));
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
            $this->realMade[] = $this->alreadyMade[] = $ClassName;
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

