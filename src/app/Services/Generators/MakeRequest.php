<?php


namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MakeRequest
{

    protected $tablesNames = [];
    protected $belongsToKeys = [];

    protected $alreadyMade = [];
    protected $realMade = [];

    protected $tablesNamesData = [];
    protected $allRelations = [];

    /**
     * MakeRequest constructor.
     * @param Table $tables
     */
    public function __construct(Table $tables)
    {
        $this->tablesNames = $tables->getTablesNames();
        $this->belongsToKeys = $tables->getBelongsToKeys();

        $this->tablesNamesData = $tables->getTablesNamesData();
        $this->allRelations = $tables->getAllRelations();

        $this->writeRequests();
        $this->writeBaseRequest();
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


    public function writeBelongsTo($table)
    {
        $str = "";
        $strComments = "
    /**
     * 
     **/\r\n";
        if (is_array($this->belongsToKeys[$table])) {
            foreach ($this->belongsToKeys[$table]['belongsTo'] as $belongsTable) {
                $str .= $strComments;

                return $str;
            }
        }

        return "";
    }


    /**
     * @param $tName
     * @return false|mixed|string
     */
    protected function writeRequestsRules($tName)
    {
        $output = file_get_contents(__DIR__ . '/Stubs/Requests/rules.stub');
        $columns_rules = '';
        $arrTimeStamps = ['deleted_at', 'created_at', 'updated_at', 'email_verified_at'];
        if (Arr::exists($this->tablesNamesData, $tName)) {
            foreach ($this->tablesNamesData[$tName] as $cName => $data) {

                if ($cName === 'id'  || in_array($cName, $arrTimeStamps)) { // || strpos($cName, '_id')
                    $columns_rules .= '';
                } elseif ($data['unique'] === 'unique') {
                    $columns_rules .= $this->uniqueRule($tName, $data);
                } elseif (strpos(' ' . $data['Type'], 'varchar')) {
                    $columns_rules .= $this->varCharRule($data);
                } elseif (strpos(' ' . $data['Type'], 'int')) {
                    $columns_rules .= $this->intRule($data);
                } else {
                    $columns_rules .= $this->otherRule($data);
                }

            }
        }

        $output = str_replace(
            '{{columns_rules}}',
            $columns_rules,
            $output);

        return $output;
    }

    protected function otherRule($data)
    {
        $rule = "\t\t\t";

        $rule .= "'" . $data['Field'] . "' => '" .
            $data['request_required'] .
            $this->getMax($data) .
            $this->getMin($data) .
            "|||',\r\n";

        return $rule;
    }

    protected function intRule($data)
    {
        $rule = "\t\t\t";

        $rule .= "'" . $data['Field'] . "' => '" .
            $data['request_required'] .
            $this->getMax($data) .
            $this->getMin($data) .
            "',\r\n";

        return $rule;
    }

    protected function varCharRule($data)
    {
        $rule = "\t\t\t";
        $rule .= "'" . $data['Field'] . "' => '" .
            $data['request_required'] .
            $this->getMax($data) .
            $this->getMin($data) .
            "',\r\n";

        return $rule;
    }

    /**
     * @param $tName
     * @param $data
     * @return string
     */
    protected function uniqueRule($tName, $data)
    {
        $rule = "\t\t\t";
        $rule .= "'" . $data['Field'] . "' => '" .
            $data['request_required'] .
            $this->getMax($data) .
            $this->getMin($data) .
            "unique:" .
            $tName .
            "," .
            $data['Field'] .
            ",' . \$this->request->all()['id'] . ',id',\r\n";
        return $rule;
    }

    protected function getMax($data)
    {
        if (strpos(' ' . $data['Type'], 'varchar')) {
            return 'max:191|';
        } elseif (strpos(' ' . $data['Type'], 'text')) {
            return 'max:10000|';
        } elseif (strpos(' ' . $data['Type'], '(11)')) {
            return 'max:11|';
        } elseif (strpos(' ' . $data['Type'], '(20)')) {
            return 'max:20|';
        } else {
            return '';
        }
    }

    protected function getMin($data)
    {
        if ($data['required'] == 'required') {
            if (strpos(' ' . $data['Type'], 'varchar')) {
                return 'min:5|';
            } elseif (strpos(' ' . $data['Type'], 'text')) {
                return 'min:100|';
            } else {
                return '';
            }
        } else {
            return '';
        }
    }


    /**
     * @return string
     */
    public function writeRequests()
    {

        $str = "";

        // $str = File::prepend( __DIR__.'\GeneratorMiddleware\Templates\Request\RequestBegin.php', 'ssssssssss');//11??
        $arrAlreadyMade = config('alex-claimer-generator.already_made.requests');
        foreach ($this->tablesNames as $tName => $cNames) {
            $className = Helper::className($tName) . "StoreRequest";
            $nameSpace = Helper::makeNameSpace('request');
            $fullClassName = $nameSpace . "\\" . $className;

            if (!is_array($arrAlreadyMade) || !in_array($fullClassName, $arrAlreadyMade)) {
                $this->realMade[] = $this->alreadyMade[] = $fullClassName;
                $str = "<?php\r\nnamespace " . Helper::makeNameSpace('request') .
                    ";\r\n\r\n";
                $str .= "use " . Helper::makeNameSpace('model') . '\\' . Helper::className($tName) . ";\r\n\r\n";
                $str .= "class " . $className . " extends " . Helper::BaseClassName() . "Request
{   ";


                $str .= $this->writeRequests_authorize();
                //$str .= $this->writeRequests_rules($tName, $cNames);
                $str .= $this->writeRequestsRules($tName, $cNames);
                $str .= $this->writeRequests_messages();
                $str .= $this->writeRequests_attributes();


                $str .= "\r\n}";

                //bbb(__METHOD__, $tName);
                file_put_contents(Helper::makeFileDirName('request', $className), $str);

            }
        }
        return $str;
    }

    /**
     * @return string
     */
    protected function writeRequests_messages()
    {
        return "
        
    public function messages()
    {
        return [
            'title.required' => 'Введите загловок статьи',
            'content_raw.min' => 'Минимальная длина статьи [:min] символов',
        ];
    }
        ";
    }

    /**
     * @param $tableName
     * @param $columnNames
     * @return string
     */
    protected function writeRequests_rules($tableName, $columnNames)//comment
    {
        $str = "
     /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [\r\n";
        if ($tableName != 'users') {
            $str .= "            'slug' => 'max:191|unique:" . $tableName . ",slug,' . \$this->request->all()['id'] . ',id',
            'title' => 'required|string|max:191|min:3',
            'description' => 'string|max:500|min:3',";
        } elseif ($tableName == 'users') {
            $str .= "\r\n            'email' => 'required|email|unique:users,email,' . \$this->request->all()['id'] . ',id',";
            $str .= "\r\n            'name' => 'required|string|max:31|min:3|unique:users,name,' . \$this->request->all()['id'] . ',id',";
        }
        $excludedColumns = ['slug', 'title', 'description', 'email', 'email_verified_at', 'remember_token'];
        $commentColumns = ['id', 'parent_id', 'active', 'created_at', 'updated_at', 'deleted_at', 'name'];
        foreach ($columnNames as $cName) {
            $bStrpos = (strpos($cName['name'], '_id') || strpos($cName['name'], 'parent'));

            if (!in_array($cName['name'], $excludedColumns)) {
                $str .= "\r\n            ";
                // bbb(__METHOD__, $tableName, $cName);
                if (in_array($cName['name'], $commentColumns) || $bStrpos) {
                    $str .= "//";
                }

                $str .= "'" . $cName['name'] . "' => 'required|" . $cName['Type'] . "|max:191|min:3',";
            }
        }
        $str .= "
        ];
    }
        ";
        return $str;
    }

    /**
     * @return string
     */
    protected function writeRequests_attributes()
    {
        return "
        
    public function attributes()
    {
        return [
            'title' => 'Заголовок',
            'content_row' => 'Текст статьи',
            'content_html' => 'Html текст статьи',
        ];
    }
        ";
    }

    /**
     * @return string
     */
    public function writeBaseRequest()
    {
        $str = "";

        $arrAlreadyMade = config('alex-claimer-generator.already_made.requests');


        $className = Helper::BaseClassName() . "Request";
        $nameSpace = Helper::makeNameSpace('model');
        $fullClassName = $nameSpace . "\\" . $className;

        if (!is_array($arrAlreadyMade) || !in_array($fullClassName, $arrAlreadyMade)) {
            $this->realMade[] = $this->alreadyMade[] = $fullClassName;
            $str = "<?php\r\nnamespace " . Helper::makeNameSpace('request') . ";\r\n\r\n";

            $str .= "use Illuminate\Foundation\Http\FormRequest;\r\n";
            $str .= "\r\n";

            $str .= "abstract class " . $className . " extends FormRequest";
            $str .= "\r\n{ ";
//            $str .= $this->write_base_creating();
//            $str .= $this->write_base_updating();
//            $str .= $this->write_base_setUpdatedAt();
//            $str .= $this->write_base_setPublishedAt();
//            $str .= $this->write_base_setSlug();

            $str .= "";

            $str .= "\r\n}";

            file_put_contents(Helper::makeFileDirName('request', $className), $str);
        }
        return $str;
    }

    /**
     * @return string
     */
    protected function writeRequests_authorize()
    {
        return "
     /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
        ";
    }
}

