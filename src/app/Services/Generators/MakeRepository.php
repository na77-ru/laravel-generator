<?php


namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MakeRepository
{

    protected $tablesNames = [];
    protected $alreadyMade = [];
    protected $realMade = [];

    /**
     * MakeRepository constructor.
     * @param Table $tables
     */
    public function __construct(Table $tables)
    {
        $this->tablesNames = $tables->getTablesNames();
        $this->writeRepositories();
        $this->writeBaseRepository();
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


    public function writeRepositories()
    {
        $arrAlreadyMade = config('alex-claimer-generator.already_made.repositories');

        foreach ($this->tablesNames as $tName => $cNames) {
            $ClassName = Helper::className($tName) . 'Repository';
            if (!is_array($arrAlreadyMade) || !in_array($ClassName, $arrAlreadyMade)) {
                // writeBaseRepository
                $this->realMade[] = $this->alreadyMade[] = $ClassName;

                $str = $this->writeRepositoriesBegin($tName);

                $str .= $this->writeRepositories_getFieldsSearchable_model($cNames);

                $str .= $this->writeRepositories_getAllWithPaginate($cNames);

                $str .= $this->writeRepositories_getForSelect($cNames, $tName);


                $str .= $this->writeRepositoriesEnd();


                file_put_contents(Helper::makeFileDirName('repository', $ClassName), $str);


            }
        }
    }

    public function writeRepositoriesBegin($tName)
    {
        // writeBaseRepository  repository  repositories
        $str = "<?php

namespace " .
            Helper::makeNameSpace('repository') . ";
            
use " . Helper::makeNameSpace('model') . '\\' . $ClassName = Helper::className($tName) . " as Model;

class " . $ClassName = Helper::className($tName) . "Repository extends BaseRepository
{
 
        ";
        return $str;

    }

    public function writeRepositories_getFieldsSearchable_model($cNames)
    {
        // writeBaseRepository  repository  repositories
        $str = "
     /**
     * Get searchable fields array
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return [\r\n";
        foreach ($cNames as $cName) {
            $str .= "            '" . $cName['name'] . "',\r\n";
        }
        $str .= "      ];
    }

    /**
     * Configure the Model
     *
     * @return string
     */
    public function model()
    {
        return Model::class;
    }       
        ";
        return $str;

    }

    public function writeRepositories_getAllWithPaginate($cNames)
    {
        // writeBaseRepository  repository  repositories
        $str = "
      /**
     * @param null \$perPage
     * @return mixed
     * @throws \Exception
     */
    public function getAllWithPaginate(\$perPage = null)
    {
        \$perPage = \$perPage ?? config('admin.perPage');

        \$columns = [";
        $arr = [];
        foreach ($cNames as $Key => $cName) {
            $arr[] = $Key;
            $str .= " '" . $cName['name'] . "',";
        }
        $str .= "];

        \$result = \$this
            ->makeModel()
            ->select(\$columns)
            //->where('id', '<>', 1)
            ->orderBy('id', 'ASC')
            ->withTrashed()";
        //dd(__METHOD__, $cNames, in_array('parent_id', $cNames));
        if (in_array('parent_id', $arr)) {
            $str .= "\r\n//            ->with('parent:id,title,slug,deleted_at')
            ->with(['parent' => function (\$request) {
                \$request->select(['id', 'title', 'slug', 'deleted_at'])->withTrashed()->where('id', '<', '99999999999');
            }
            ])";
        }

        $str .= " ->paginate(\$perPage, \$columns);

        return \$result;

    }      
        ";
        return $str;

    }

    public function writeRepositories_getForSelect($cNames, $tName)
    {
        // writeBaseRepository  repository  repositories
        $arr = [];
        foreach ($cNames as $Key => $cName) {
            $arr[] = $Key;

        }
        $str = "
    public function getFor" . Helper::className($tName) . "Select()
    {
        \$columns = implode(',', [
            'id',
            'CONCAT (id, " . ", title) AS id_title'
        ]);
        \$result = \$this
            ->makeModel()
            ->selectRaw(\$columns)
            //->toBase()";

        if (in_array('parent_id', $arr)) {
            $str .= "\r\n            ->where('can_be_parent', '=', '1')
//            ->with('parent:id,title,') //11?? ошибка
            ->with(['parent' => function (\$query) {
                \$query->select(['id', 'title']);
            }])";
        }

        $str .= "
            ->get();

        return \$result;
    }        
        ";
        return $str;

    }

    public function writeRepositoriesEnd()
    {
        // writeBaseRepository  repository  repositories
        $str = "
}
        ";
        return $str;

    }


    public function writeBaseRepository()
    {
        $arrAlreadyMade = config('alex-claimer-generator.already_made.repositories');

        $ClassName = "BaseRepository";
        if (!is_array($arrAlreadyMade) || !in_array($ClassName, $arrAlreadyMade)) {
            // writeBaseRepository
            $this->realMade[] = $this->alreadyMade[] = $ClassName;

            $str = $this->writeBaseRepositoryBegin();

            $str .= $this->writeBaseRepository__construct();
            $str .= $this->writeBaseRepository_getFieldsSearchable_model();
            $str .= $this->writeBaseRepository_startCondition();

            $str .= $this->writeBaseRepository_makeModel();
            $str .= $this->writeBaseRepository_paginate();
            $str .= $this->writeBaseRepository_allQuery();

            $str .= $this->writeBaseRepository_all();
            $str .= $this->writeBaseRepository_create();
            $str .= $this->writeBaseRepository_find();

            $str .= $this->writeBaseRepository_hasChange();
            $str .= $this->writeBaseRepository_update();
            $str .= $this->writeBaseRepository_delete();

            $str .= $this->writeBaseRepository_restore();
            $str .= $this->writeBaseRepository_getEdit();

            $str .= $this->writeBaseRepositoryEnd();


            file_put_contents(Helper::makeFileDirName('repository', $ClassName), $str);


        }
    }

    public function writeBaseRepositoryBegin()
    {
        // writeBaseRepository  repository  repositories
        $str = "<?php

namespace " .
            Helper::makeNameSpace('repository') . ";

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;


abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected \$model;

    /**
     * @var Application
     */
    protected \$app;
        ";
        return $str;

    }

    public function writeBaseRepository__construct()
    {
        // writeBaseRepository  repository  repositories
        $str = "
    /**
     * @param Application \$app
     *
     * @throws \Exception
     */
    public function __construct(Application \$app)
    {
        \$this->app = \$app;
        \$this->makeModel();
    }
        ";
        return $str;

    }

    public function writeBaseRepository_getFieldsSearchable_model()
    {
        // writeBaseRepository  repository  repositories
        $str = "
   /**
     * Get searchable fields array
     *
     * @return array
     */
    abstract public function getFieldsSearchable();

    /**
     * Configure the Model
     *
     * @return string
     */
    abstract public function model();

        ";
        return $str;

    }

    public function writeBaseRepository_startCondition()
    {
        // writeBaseRepository  repository  repositories
        $str = "

    protected function startCondition()
    {
        return clone \$this->model;
    }
        ";
        return $str;

    }

    public function writeBaseRepository_makeModel()
    {
        // writeBaseRepository  repository  repositories
        $str = "

    /**
     * Make Model instance
     *
     * @return Model
     * @throws \Exception
     *
     */
    public function makeModel()
    {
        \$model = \$this->app->make(\$this->model());

        if (!\$model instanceof Model) {
            throw new \Exception(\"Class {\$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model\");
        }

        return \$this->model = \$model;
    }
        ";
        return $str;

    }

    public function writeBaseRepository_paginate()
    {
        // writeBaseRepository  repository  repositories
        $str = "
    /**
     * Paginate records for scaffold.
     *
     * @param int \$perPage
     * @param array \$columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(\$perPage, \$columns = ['*'])
    {
        \$query = \$this->allQuery();

        return \$query->paginate(\$perPage, \$columns);
    }
        ";
        return $str;

    }

    public function writeBaseRepository_allQuery()
    {
        // writeBaseRepository  repository  repositories
        $str = "


    /**
     * Build a query for retrieving all records.
     *
     * @param array \$search
     * @param int|null \$skip
     * @param int|null \$limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allQuery(\$search = [], \$skip = null, \$limit = null)
    {
        \$query = \$this->model->newQuery();

        if (count(\$search)) {
            foreach (\$search as \$key => \$value) {
                if (in_array(\$key, \$this->getFieldsSearchable())) {
                    \$query->where(\$key, \$value);
                }
            }
        }

        if (!is_null(\$skip)) {
            \$query->skip(\$skip);
        }

        if (!is_null(\$limit)) {
            \$query->limit(\$limit);
        }

        return \$query;
    }
        ";
        return $str;

    }

    public function writeBaseRepository_all()
    {
        // writeBaseRepository  repository  repositories
        $str = "

    /**
     * Retrieve all records with given filter criteria
     *
     * @param array \$search
     * @param int|null \$skip
     * @param int|null \$limit
     * @param array \$columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all(\$search = [], \$skip = null, \$limit = null, \$columns = ['*'])
    {
        \$query = \$this->allQuery(\$search, \$skip, \$limit);

        return \$query->get(\$columns);
    }
        ";
        return $str;

    }

    public function writeBaseRepository_create()
    {
        // writeBaseRepository  repository  repositories
        $str = "


    /**
     * Create model record
     *
     * @param array \$input
     *
     * @return Model
     */
        public function create(\$input)
    {
        \$model = \$this->model->newInstance(\$input);

        \$model->save();

        return \$model;
    }
        ";
        return $str;

    }

    public function writeBaseRepository_find()
    {
        // writeBaseRepository  repository  repositories
        $str = "

    /**
     * Find model record for given id
     *
     * @param int \$id
     * @param array \$columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find(\$id, \$columns = ['*'])
    {
        \$query = \$this->model->newQuery();

        return \$query->find(\$id, \$columns);
    }
        ";
        return $str;

    }

    public function writeBaseRepository_hasChange()
    {
        // writeBaseRepository  repository  repositories
        $str = "


    /**
     * @param \$id
     * @param \$data
     * @param array \$columns
     * @return bool
     */
    public function hasChange(\$id, \$data, \$columns = [])
    {
        \$columns = !empty(\$columns) ? \$columns : \$this->model->getCompared();

        \$obj = \$this->model->find(\$id, \$columns);

        \$result = false;

        foreach (\$columns as \$i => \$key) {

            if (\$obj[\$key] != \$data[\$key]) {
                return true;
            }
        }
        return \$result;
    }
        ";
        return $str;

    }

    public function writeBaseRepository_update()
    {
        // writeBaseRepository  repository  repositories
        $str = "

    /**
     * Update model record for given id
     *
     * @param \$input
     * @param \$id
     * @return bool
     */
    public function update(\$input, \$id)
    {
        \$query = \$this->model->newQuery();

        \$model = \$query->findOrFail(\$id);

//        dd(__METHOD__,
//            \$model->updated_at,
//            \$input);//11??

        \$model->fill(\$input);
       // dd(__METHOD__, \$model);//11??
        return \$model->save();

    }

        ";
        return $str;

    }

    public function writeBaseRepository_delete()
    {
        // writeBaseRepository  repository  repositories
        $str = "


    /**
     * @param int \$id
     *
     * @return bool|mixed|null
     * @throws \Exception
     *
     */
    public function delete(\$id)
    {
        \$query = \$this->model->newQuery();

        \$model = \$query->findOrFail(\$id);

        return \$model->delete();

    }
        ";
        return $str;

    }

    public function writeBaseRepository_restore()
    {
        // writeBaseRepository  repository  repositories
        $str = "
        

    public function restore(\$id = null)
    {

        if(\$id){
            \$result = \$this->startCondition()
                ->withTrashed()
                ->where('id', '=', \$id)
                ->where('deleted_at', '<>', null)
                ->restore();
            if (\$result){
                app()->msg->setMsg(\"Запись  №\".\$id.\" успешно востановленна\");
                app()->msg->setAT(\"alert-success\");
            }
        }else{
            \$result = \$this->startCondition()
                ->withTrashed()
                ->where('deleted_at', '<>', null)
                ->restore();
            if (\$result){
                app()->msg->setMsg(\"Записи успешно востановленны\");
                app()->msg->setAT(\"alert-success\");
            }
        }


        return \$result;
    }
        ";
        return $str;

    }

    public function writeBaseRepository_getEdit()
    {
        // writeBaseRepository  repository  repositories
        $str = "

    public function getEdit(\$id)
    {
        \$result = \$this->startCondition()
            ->withTrashed()
            ->find(\$id);
        return \$result;
    }
        ";
        return $str;

    }

    public function writeBaseRepositoryEnd()
    {
        // writeBaseRepository  repository  repositories
        $str = "
}
        ";
        return $str;

    }

}

