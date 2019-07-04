<?php


namespace AlexClaimer\Generator\App\Services\Generator;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class MakeView
{

    protected $tablesNames = [];
    protected $alreadyMade = [];
    protected $viewsAlreadyMade = [];
    protected $realMade = [];
    protected $tables;

    /**
     * MakeView constructor.
     * @param Table $tables
     */
    public function __construct(Table $tables)
    {
        $this->tables = $tables;
        $this->tablesNames = $tables->getTablesNames();
        $this->writeViews();
    }

    protected function writeViews()
    {
        $this->writeIndexBlade();
        $this->writeEditBlade();
        $this->writeCreateBlade();
        $this->writeInc_MainBlade();
        $this->writeInc_AddBlade();

        $this->writeLayout();

        $this->writeAlreadyMade();
        //dd(__METHOD__);
    }

    protected function writeInc_MainBlade()
    {
        $bladeName = "inc/edit_main_col.blade";

        foreach ($this->tablesNames as $tName => $cNames) {
            // bbb(__METHOD__, Helper::makeNameSpace('model') . Helper::className($tName), $tName, $cNames);
            // break;
            if ($this->notExist($tName, $bladeName)) {

                $output = $this->strings_replace($tName, $cNames, 'inc/edit_main_col.blade.stub');

                $output = $this->strings_replace_inc_main($tName, $cNames, $output);

                $this->setAlreadyMadeViews($tName, $bladeName);

                file_put_contents(Helper::makeFileDirName('view', $bladeName, $tName), $output);
            }
        }

        // dd(__METHOD__, $this->viewsAlreadyMade);
    }


    protected function writeInc_AddBlade()
    {
        $bladeName = "inc/edit_add_col.blade";

        foreach ($this->tablesNames as $tName => $cNames) {
            //bbb(__METHOD__,Helper::makeNameSpace('model').Helper::className($tName), $tName, $cNames);
            // break;
            if ($this->notExist($tName, $bladeName)) {

                $output = $this->strings_replace($tName, $cNames, 'inc/edit_add_col.blade.stub');

                $this->setAlreadyMadeViews($tName, $bladeName);

                file_put_contents(Helper::makeFileDirName('view', $bladeName, $tName), $output);
            }
        }

        // dd(__METHOD__, $this->viewsAlreadyMade);
    }

    protected function writeLayout()
    {
        $success = false;
        if ($this->notExist('app', 'app.blade')) {
            $postfix = lcfirst(config('alex-claimer-generator.config.namespace_postfix')) . '/';

            $success = \File::copy(

                __DIR__ . ('/../../../../resources/views/layouts/app.blade.php'),
                base_path('/resources/views/' . $postfix . 'app.blade.php')
            );
            $this->setAlreadyMadeViews('app', 'app.blade');
        }
        //dd(__METHOD__, $success);
        return $success;
    }

    protected function setAlreadyMadeViews($tName, $bladeName)
    {
        if ($this->notExist($tName, $bladeName)) {
            $this->viewsAlreadyMade[$tName][] = $this->alreadyMade[$tName][] = $bladeName;
        }
    }

    protected function notExist($tName, $bladeName)
    {
        if (empty($this->viewsAlreadyMade)) {
            $this->viewsAlreadyMade = config('alex-claimer-generator.already_made_views.views');
        }
        return (empty($this->viewsAlreadyMade)
            || !Arr::exists($this->viewsAlreadyMade, $tName)
            || !in_array($bladeName, $this->viewsAlreadyMade[$tName]));
    }


    protected function strings_replace($tName, $cNames, $stub)
    {
        $postfix = lcfirst(config('alex-claimer-generator.config.namespace_postfix'));
        $output = file_get_contents(__DIR__ . '/Stubs/Views/' . $stub);

        if (empty($postfix)) {
            $output = str_replace('{{postfix}}', '', $output);
        } else {
            $output = str_replace('{{postfix}}', Helper::make_views_routes_prefix(), $output);
        }

        $output = str_replace('{{route_name_without_action_and_\')}} }}', '{{route(\''.Helper::make_views_routes_name($tName).'', $output);
        $output = str_replace('{{postfix/}}', $postfix . '/', $output);
        $output = str_replace('{{table_name}}', $tName, $output);
        $output = str_replace('{{ModelNameSpace}}',
            Helper::makeNameSpace('model') . '\\' . Helper::className($tName), $output);
        $output = str_replace('{{<thead><td>}}', $this->theadIndex($tName, $cNames, $postfix), $output);
        $output = str_replace('{{<tr><td>}}', $this->tdIndex($tName, $cNames, $postfix), $output);

        $output = str_replace('{{belongsToComment}}', '', $output); //11 replace with something




        return $output;
    }


    protected function writeIndexBlade()
    {
        $bladeName = "index.blade";

        foreach ($this->tablesNames as $tName => $cNames) {
            //bbb(__METHOD__,Helper::makeNameSpace('model').Helper::className($tName), $tName, $cNames);
            // break;
            if ($this->notExist($tName, $bladeName)) {

                $output = $this->strings_replace($tName, $cNames, 'index.blade.stub');

                $this->setAlreadyMadeViews($tName, $bladeName);

                file_put_contents(Helper::makeFileDirName('view', $bladeName, $tName), $output);
            }
        }

        //dd(__METHOD__, $this->viewsAlreadyMade);
    }

    protected function writeCreateBlade()
    {
        $str = "";
        $bladeName = "create.blade";

        foreach ($this->tablesNames as $tName => $cNames) {

            if ($this->notExist($tName, $bladeName)) {
                $output = $this->strings_replace($tName, $cNames, 'create.blade.stub');
                $this->setAlreadyMadeViews($tName, $bladeName);

                file_put_contents(Helper::makeFileDirName('view', $bladeName, $tName), $output);
            }
        }

        // dd(__METHOD__, $this->viewsAlreadyMade);
    }

    protected function writeEditBlade()
    {
        $str = "";
        $bladeName = "edit.blade";

        foreach ($this->tablesNames as $tName => $cNames) {
            if ($this->notExist($tName, $bladeName)) {

                $output = $this->strings_replace($tName, $cNames, 'edit.blade.stub');

                $this->setAlreadyMadeViews($tName, $bladeName);

                file_put_contents(Helper::makeFileDirName('view', $bladeName, $tName), $output);
            }
        }

        // dd(__METHOD__, $this->viewsAlreadyMade);
    }

    protected function writeAlreadyMade()
    {
        $this->viewsAlreadyMade = Arr::sort($this->viewsAlreadyMade);
        //dd(__METHOD__, $this->viewsAlreadyMade);
        $str_viewsAlreadyMade = "<?php\r\nreturn [\r\n";
        $str_viewsAlreadyMade .= "\t'views' => [\r\n";
        foreach ($this->viewsAlreadyMade as $type => $arr) {

            $str_viewsAlreadyMade .= "\t\t'$type' => [\r\n";

            foreach ($arr as $table => $name) {

                $str_viewsAlreadyMade .= "\t\t\t'" . $name . "',\r\n";

            }
            $str_viewsAlreadyMade .= "\t\t],\r\n";
        }
        $str_viewsAlreadyMade .= "\t]\r\n];";
        //return;//111 uncomment
        file_put_contents(base_path() .
            '\config\alex-claimer-generator\already_made_views.php',
            $str_viewsAlreadyMade);
    }

    /**
     * @param $columns
     * @param $postfix
     * @return string
     */
    protected function theadIndex($tableName, $columns, $postfix)
    {
        $str = "<th>#</th>\r\n";
        foreach ($columns as $column) {
            // dd(__METHOD__, $column);
            if ($column['name'] != 'id')
                $str .= "<th>\t\t\t{{ __('" . $column['name'] . "') }}</th>\r\n";
        }
        return $str;
    }

    /**
     * @param $columns
     * @param $postfix
     * @return string
     */
    protected function tdIndex($tableName, $columns, $postfix)
    {
        $str = "\t\t\t<tr @if(!empty(\$item->deleted_at) || !empty(\$item->parent->deleted_at))
                style=\"color:red;\"
            @endif>\r\n";
        foreach ($columns as $column) {
            // dd(__METHOD__, $column);
            if ($column['name'] == 'id') {
                $str .= "\t\t\t<td><a href=\"{{route('" . Helper::make_views_routes_name($tableName, 'edit')."', \$item->id)}}\">
                                            {{ \$item->id }}
                </a>
           </td>\r\n";
            } else {
                $str .= "\t\t\t<td>{{ \$item->" . $column['name'] . " }}</td>\r\n";
            }

        }
        $str .= "</tr>";
        return $str;
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
        return ['see already_made_views.php'];
        return $this->alreadyMade;
    }

    /**
     * @param $tName
     * @param $cNames
     * @param $output
     * @return mixed
     */
    protected function strings_replace_inc_main($tName, $cNames, $output)
    {

        if (Arr::exists($cNames, 'is_published')) {
            //bbb(__METHOD__, $tName, $cNames);
            $is_publishedHead = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/edit_columns/is_publishedHead.stub');
            $output = str_replace('{{is_publishedHead}}', $is_publishedHead, $output);
        } else {
            $output = str_replace('{{is_publishedHead}}', '', $output);
        }

        $modelVarComment = "@php /**@var " .
            Helper::makeNameSpace('model') .
            Helper::className($tName) .
            " \$item */ @endphp\r\n";
        $output = str_replace('{{ModelComment}}', $modelVarComment, $output);

        if (Arr::exists($belongsTo = $this->tables->getBelongsToKeys(), $tName)) {
            $belongsTo = $this->tables->getBelongsToKeys();
            $belongsToComment = '';
            foreach ($belongsTo[$tName]['belongsTo'] as $arKeyToTable) {

                $var = Str::singular($arKeyToTable['to_table']);
                $belongsToComment .= "@php /**@var \Illuminate\Database\Eloquent\Collection \$" . $var . "List */ @endphp\r\n";
            }
            $output = str_replace('{{belongsToComment}}', $belongsToComment, $output);
        }

        if (Arr::exists($cNames, 'title')) {
            $title = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/edit_columns/title.stub');
            $output = str_replace('{{title}}', $title, $output);
        } elseif (Arr::exists($cNames, 'name')) {
                $name = file_get_contents(__DIR__ .
                    '/Stubs/Views/inc/edit_columns/name.stub');
                $output = str_replace('{{title}}', $name, $output);
            } else {
            $output = str_replace('{{title}}', '', $output);
        }
        if (Arr::exists($cNames, 'content_row')) {
            //bbb(__METHOD__, $tName, $cNames);
            $content_row = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/edit_columns/content_row.stub');
            $output = str_replace('{{content_row}}', $content_row, $output);
        } else {
            $output = str_replace('{{content_row}}', '', $output);
        }
        if (Arr::exists($belongsTo = $this->tables->getBelongsToKeys(), $tName)) {
            $arrBelongsTo = $this->tables->getBelongsToKeys();
            $belongsToComment = '';
            foreach ($arrBelongsTo[$tName]['belongsTo'] as $arKeyToTable) {
                // bbb(__METHOD__, $tName, $arKeyToTable);
                $var = Str::singular($arKeyToTable['to_table']);
                $belongsTo = file_get_contents(__DIR__ .
                    '/Stubs/Views/inc/edit_columns/belongsTo.stub');
                $belongsTo = str_replace('{{modelBelongsTo}}', $var, $belongsTo);
                $belongsTo = str_replace('{{BelongsToKey}}', $arKeyToTable['key'], $belongsTo);

                $output = str_replace('{{belongsTo}}', $belongsTo, $output);
            }
        } else {
            $output = str_replace('{{belongsTo}}', '', $output);
        }


        if (Arr::exists($cNames, 'slug')) {
            //bbb(__METHOD__, $tName, $cNames);
            $slug = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/edit_columns/slug.stub');
            $output = str_replace('{{slug}}', $slug, $output);
        } else {
            $output = str_replace('{{slug}}', '', $output);
        }
        if (Arr::exists($cNames, 'is_slugChange') || true) {//11?? || true
            //bbb(__METHOD__, $tName, $cNames);
            $is_slugChange = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/edit_columns/is_slugChange.stub');
            $output = str_replace('{{is_slugChange}}', $is_slugChange, $output);
        } else {
            $output = str_replace('{{is_slugChange}}', '', $output);
        }


        if (Arr::exists($cNames, 'excerpt')) {
            //bbb(__METHOD__, $tName, $cNames);
            $excerpt = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/edit_columns/excerpt.stub');
            $output = str_replace('{{excerpt}}', $excerpt, $output);
        } else {
            $output = str_replace('{{excerpt}}', '', $output);
        }

        if (Arr::exists($cNames, 'is_published')) {
            //bbb(__METHOD__, $tName, $cNames);
            $is_published = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/edit_columns/is_published.stub');
            $output = str_replace('{{is_published}}', $is_published, $output);
        } else {
            $output = str_replace('{{is_published}}', '', $output);
        }
        return $output;
    }
}

