<?php


namespace AlexClaimer\Generator\App\Services\Generators;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MakeObserver
{

    protected $tablesNames = [];
    protected $belongsToKeys = [];

    protected $alreadyMade = [];
    protected $realMade = [];

    /**
     * MakeObserver constructor.
     * @param Table $tables
     */
    public function __construct(Table $tables)
    {
        $this->tablesNames = $tables->getTablesNames();
        $this->belongsToKeys = $tables->getBelongsToKeys();
        $this->writeObservers();
        $this->writeBaseObserver();
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
     * @return string
     */
    public function writeObservers()
    {

        $str = "";

        // $str = File::prepend( __DIR__.'\GeneratorMiddleware\Templates\Observer\ObserverBegin.php', 'ssssssssss');//11??
        $arrAlreadyMade = config('alex-claimer-generator.already_made.observers');
        foreach ($this->tablesNames as $tName => $cNames) {
            $className = Helper::className($tName) . "Observer";
            $nameSpace = Helper::makeNameSpace('model');
            $fullClassName = $nameSpace . "\\" . $className;

            if (!is_array($arrAlreadyMade) || !in_array($fullClassName, $arrAlreadyMade)) {
                $this->realMade[] = $this->alreadyMade[] = $fullClassName;
                $str = "<?php\r\nnamespace " . Helper::makeNameSpace('observer') .
                    ";\r\n\r\n";
                $str .= "use " . Helper::makeNameSpace('model') . '\\' . Helper::className($tName) . ";\r\n\r\n";
                $str .= "class " . $className . " extends " . Helper::BaseClassName() . "Observer
{   ";

                $str .= "                
    /**
     * @param BlogAdminCategory \$model
     */
    public function creating(\$model)
    {
        //\$this->setHtml(\$model);// uncomment if you need in setHtml(\$model) too
        parent::creating(\$model);
    }


    /**
     * @param BlogAdminCategory \$model
     * @return mixed
     */
    public function updating(\$model)
    {
        //\$this->requestClassName =  '";

                //$str .= "App\Http\Requests\Blog\BlogCategoryStoreRequest'";

                $str .= Helper::makeNameSpace('request');

                $str .= ";
        return parent::updating(\$model);

    }
                ";
                $str .= "
//    /**
//     * @param BlogAdminPost \$model
//     */
//    protected function setHtml(\$model): void // uncomment if you need in creating(\$model) too
//    {
//        if (\$model->isDirty('content_row')) {
//            // it will be markdown
//            \$model->content_html = \$model->content_row;
//        }
//
//    }                
                ";
                $str .= "\r\n}";

                // dd(__METHOD__, Helper::makeFileDirName('observer', $className), $str);
                file_put_contents(Helper::makeFileDirName('observer', $className), $str);

            }
        }
        return $str;
    }

    public function writeBaseObserver()
    {
        $str = "";

        $arrAlreadyMade = config('alex-claimer-generator.already_made.observers');

        $className = Helper::BaseClassName() . "Observer";
        $nameSpace = Helper::makeNameSpace('model');
        $fullClassName = $nameSpace . "\\" . $className;

        if (!is_array($arrAlreadyMade) || !in_array($fullClassName, $arrAlreadyMade)) {
            $this->realMade[] = $this->alreadyMade[] = $fullClassName;
            $str = "<?php\r\nnamespace " . Helper::makeNameSpace('observer') . ";\r\n\r\n";

            $str .= "use Carbon\Carbon;\r\n";
            $str .= "use Illuminate\Support\Str;\r\n\r\n";

            $str .= "abstract class " . $className;
            $str .= "\r\n{ ";
            $str .= $this->write_base_creating();
            $str .= $this->write_base_updating();
            $str .= $this->write_base_setUpdatedAt();
            $str .= $this->write_base_setPublishedAt();
            $str .= $this->write_base_setSlug();

            $str .= "";

            $str .= "\r\n}";

            file_put_contents(Helper::makeFileDirName('observer', $className), $str);
        }
        return $str;
    }

    /**
     * @return string
     */
    protected function write_base_creating()
    {
        $str = "
           /**
     * @param \$model
     * @return bool
     */
      protected function creating(\$model, \$bSlug = false)
      {
        \$this->setPublishedAt(\$model);
        if (\$bSlug){
            \$this->setSlug(\$model);
        }

        \$model->created_at = Carbon::now();
        \$model->updated_at = Carbon::now();
        //\$this->setUser();//11?? ошибка нет user in categories
        return true;
      }        
            ";
        return $str;
    }

    /**
     * @param $model
     * @return string
     */
    protected function write_base_updating()
    {
        $str = "
    /**
     * @param \$model
     * @return bool
     */
    protected function updating(\$model, \$bSlug = false)
    {        
        \$this->setPublishedAt(\$model);
        \$this->setUpdatedAt(\$model);
        if (\$bSlug){
            \$this->setSlug(\$model);
        }

        return true;
    }            
            ";


        return $str;
    }

    /**
     * @param $model
     * @return string
     */
    protected function write_base_setUpdatedAt()
    {
        $str = "
    /**
     * @param \$model
     */
    protected function setUpdatedAt(\$model): void
    {        
         \$comparable = \$model->getComparable();
        \$bChanged = \$model->isDirty(\$comparable);

        //\$arrChangedFields = \$model->getDirty();//11??

        if (\$bChanged) {
            \$model->updated_at = Carbon::now();
            app()->msg->setMsg(\"Статья  №\" . \$model->getOriginal('id') . \" успешно изменена\");
            app()->msg->setAT(\"alert-success\");

        } else {
            app()->msg->setMsg(\"В статье №\" . \$model->getOriginal('id') . \" не было изменений\");
            app()->msg->setAT(\"alert-primary\");
        }
    }          
            ";


        return $str;
    }

    /**
     * @return string
     */
    protected function write_base_setPublishedAt()
    {
        $str = "
     /**
     * @param \$model
     */
    protected function setPublishedAt(\$model): void
    {       
        if (empty(\$model->published_at) && \$model->is_published) {
            \$model->published_at = Carbon::now();
        }
    }           
            ";


        return $str;
    }

    /**
     * @return string
     */
    protected function write_base_setSlug()
    {
        $str = "

    /**
     * @param \$model
     */
    protected function setSlug(\$model): void
    {
        // EXIST IN DB   AND  SET TO CHANGE  AND EMPTY IN FORM
        if (!empty(\$model->getOriginal('slug')) && \$model->is_slugChange && empty(\$model->slug)) {

            \$model->slug = Str::slug(\$model->title);
        }

        // EXIST IN DB   AND     NOT SET TO CHANGE    AND    NOT EMPTY IN FORM
        if (!empty(\$model->getOriginal('slug')) && !\$model->is_slugChange && !empty(\$model->slug)) {

            \$model->slug = \$model->getOriginal('slug');
        }

        // EXIST IN DB   AND     SET TO CHANGE    AND    NOT EMPTY IN FORM
        // NOTHING TO DO .....

        // NOT  EXIST IN DB   AND EMPTY IN FORM
        if (empty(\$model->getOriginal('slug')) && empty(\$model->slug)) {

            \$model->slug = Str::slug(\$model->title);
        }


        // NOT  EXIST IN DB   AND NOT EMPTY IN FORM
        // NOTHING TO DO .....


        unset(\$model->is_slugChange); //this column is`t in table
    }          
            ";


        return $str;
    }

}

