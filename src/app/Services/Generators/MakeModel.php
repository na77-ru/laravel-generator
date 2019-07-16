<?php


namespace AlexClaimer\Generator\App\Services\Generators;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class MakeModel
{

    protected $tablesNames = [];

    protected $alreadyMade = [];
    protected $realMade = [];

    protected $belongsToKeys = [];
    protected $belongsToMany = [];

    /**
     * MakeModel constructor.
     * @param Table $tables
     */
    public function __construct(Table $tables)
    {
       // dd(__METHOD__);
        $this->tablesNames = $tables->getTablesNames();
        $this->belongsToKeys = $tables->getBelongsToKeys();
        $this->belongsToMany = $tables->getBelongsToManyKeys();
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
     * @param $table
     * @return string
     */
    public function writeBelongsToManyUse($tName, $str)
    {
        if (!Arr::exists($this->belongsToMany, $tName))return '';
        foreach ($this->belongsToMany[$tName] as $property => $arrBelongs) {
            $str .= "use " . Helper::makeNameSpace('model') . "\\" . Helper::className($arrBelongs['to_table']) . ";\r\n";

        }
        return $str;
    }

    /**
     * @param $table
     * @return string
     */
    public function writeBelongsToManyFunc($tName)
    {
        if (!Arr::exists($this->belongsToMany, $tName))return '';

        $str = "
    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     **/\r\n";


        foreach ($this->belongsToMany[$tName] as $property => $arr) {

            $str .= "    public function " . $property . "()\r\n";
            $str .= "    {\r\n";

            $str .= "\t\treturn \$this->belongsToMany(";
            $str .= "\r\n\t\t\t".$arr['related_class']."::class, ";
            $str .= "\r\n\t\t\t'".$arr['pivot_table']."',";
            $str .= "\r\n\t\t\t'".$arr['foreignPivotKey']."',";
            $str .= "\r\n\t\t\t'".$arr['relatedPivotKey']."',";
            $str .= "\r\n\t\t\t'id',";
            $str .= "\r\n\t\t\t'id'";
            $str .= "\r\n\t\t);\r\n";

            $str .= "    }\r\n";
        }

        return $str;
    }

    /**
     * @return string
     */
    public function writeModels()
    {
        $str = "";

        $arrAlreadyMade = config('alex-claimer-generator.already_made.models');
        //dd(__METHOD__, $this->tablesNames,$arrAlreadyMade);
        foreach ($this->tablesNames as $tName => $cNames) {

            $className = Helper::className($tName);
            $nameSpace = Helper::makeNameSpace('model');
            $fullClassName = $nameSpace . "\\" . $className;

            if (!is_array($arrAlreadyMade) || !in_array($fullClassName, $arrAlreadyMade)) {
                $this->realMade[] = $this->alreadyMade[] = $fullClassName;
                $str = "<?php\r\nnamespace " . $nameSpace .
                    ";\r\n\r\n";

//                if (Arr::exists($this->belongsToMany, $tName)) {
//                    $str .= $this->writeBelongsToManyUse($tName, $str);
//                }

                $str .= "/**\r\n";
                $str .= " * This is the model class for table \"{{%$tName}}\".\r\n *\r\n";
                //$str .= File::prepend( __DIR__.'\GeneratorMiddleware\Templates\Model\ModelBegin.php', $tName);
                foreach ($cNames as $cName) {
                    $str .= " * @property " . $cName['type'] . " " . $cName['name'] . "\r\n";
                }
                $str .= " */\r\n";

                $str .= "class " . $className . " extends " . Helper::BaseClassName() . "Model
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

                $str .= $this->writeBelongsToManyFunc($tName);
                if(strpos($tName, 'roles')){
                    $str .= file_get_contents(__DIR__ .
                        '/Stubs/Models/rolesHasAccess.stub');
                }
                if(strpos($tName, 'users')){
                    $str .= file_get_contents(__DIR__ .
                        '/Stubs/Models/usersHasAccess.stub');
                }
                $str .= "   /**
     * @return array
     */
    public function getComparable(): array
    {
        return \$this->comparable;
    }";


                $str .= "\r\n}";
                // dd(__METHOD__, Helper::makeFileDirName('model
                file_put_contents(Helper::makeFileDirName('model', $className), $str);

            }
        }
        return $str;
    }

    public function writeBaseModel()
    {

        $str = "";

        // $str = File::prepend( __DIR__.'\GeneratorMiddleware\Templates\Model\ModelBegin.php', 'ssssssssss');//11??
        $arrAlreadyMade = config('alex-claimer-generator.already_made.models');

        $className = Helper::BaseClassName() . "Model";
        $nameSpace = Helper::makeNameSpace('model');
        $fullClassName = $nameSpace . "\\" . $className;

        if (!is_array($arrAlreadyMade) || !in_array($fullClassName, $arrAlreadyMade)) {
            $this->realMade[] = $this->alreadyMade[] = $nameSpace . "\\" . $className;
            $str = "<?php\r\nnamespace " . $nameSpace . ";\r\n\r\n";
            $str .= "use Illuminate\Database\Eloquent\Model;\r\n";
            $str .= "use Illuminate\Database\Eloquent\SoftDeletes;\r\n\r\n";


            $str .= "abstract class " . $className . " extends Model
{ 
        use SoftDeletes;  
}";

            file_put_contents(Helper::makeFileDirName('model', $className), $str);
        }
        return $str;
    }
}

