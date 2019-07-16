<?php


namespace AlexClaimer\Generator\App\Services\Generators;

use AlexClaimer\Generator\App\Services\Generators\MakeViews\MakeColumnsForViewEdit;
use AlexClaimer\Generator\App\Services\Generators\MakeViews\MakeIndexView;
//packages/AlexClaimer/Generator/src/app/Services/Generators/MakeRoutes/Route.php
use AlexClaimer\Generator\App\Services\Generators\MakeRoutes\Route;
use AlexClaimer\Generator\App\Services\Generators\MakeViews\View;
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
    protected $belongsToMany = [];


    protected $uniqueRelations = [];

    /**
     * MakeView constructor.
     * @param Table $tables
     */
    public function __construct(Table $tables)
    {
        $this->tables = $tables;
        $this->tablesNames = $tables->getTablesNames();
        $this->belongsToMany = $tables->getBelongsToManyKeys();

        $this->uniqueRelations = $tables->getUniqueRelations();

        $this->writeViews();
    }

    protected function writeViews()
    {

        //$this->writeIndexBlade();
        $this->writeEditBlade();
        $this->writeCreateBlade();

        $this->copyRoles();

        foreach ($this->tablesNames as $tName => $cNames) {
            $bladeName = "inc/columns_for_edit.blade";
            if ($this->notExist($tName, $bladeName)) {
                new MakeColumnsForViewEdit($this->tables, $tName, $cNames, $bladeName);
                $this->setAlreadyMadeViews($tName, $bladeName);
            }
            $bladeName = "index.blade";
            if ($this->notExist($tName, $bladeName)) {
                new MakeIndexView($this->tables, $tName, $cNames, $bladeName);
                $this->setAlreadyMadeViews($tName, $bladeName);
            }
        }
        $this->writeInc_AddBlade();

        $this->writeLayout();
        $this->writeInc();


        $this->writeAlreadyMade(); //uncomment//11
        //dd(__METHOD__);
    }

    /**
     * @return bool
     */
    protected function copyRoles()
    {
        $success = false;
        if ($this->notExist('Copy-Views', 'Roles')) {

            $target = Helper::makeFileDirName('view', 'index', 'auth_roles');
            $target = substr($target, 0, strpos($target, '\\\\'));

            // dd(__METHOD__, $target);

            $success = \File::copyDirectory(__DIR__ . '/Stubs/Views/auth_roles', $target);

            if ($success) {
                $this->setAlreadyMadeViews('Copy-Views', 'Roles');
            }


        }
        return $success;
    }

    protected function writeInc_AddBlade()
    {
        $bladeName = "inc/edit_add_col.blade";

        foreach ($this->tablesNames as $tName => $cNames) {
            //bbb(__METHOD__,Helper::makeNameSpace('model').Helper::className($tName), $tName, $cNames);
            // break;
            if ($this->notExist($tName, $bladeName)) {

                $output = $this->strings_replace($tName, $cNames, 'inc/1/edit_add_col.blade.stub');

                $this->setAlreadyMadeViews($tName, $bladeName);

                file_put_contents(Helper::makeFileDirName('view', $bladeName, $tName), $output);
            }
        }

        // dd(__METHOD__, $this->viewsAlreadyMade);
    }

    protected function writeLayout()
    {
        $success = false;

        $pathFileNameForApp = $this->getPathFileNameForApp('/');

        if ($this->notExist('resources/views', $pathFileNameForApp)) {

            $output = file_get_contents(__DIR__ . '/Stubs/Views/app/app.stub');

            $js = "{{ mix('" . View::getFullPathNameASSETsInBlade('js') . "') }}";
            $css = "{{ mix('" . View::getFullPathNameASSETsInBlade('css') . "') }}";

            $output = str_replace('{{ app.js for change }}', $js, $output);
            $output = str_replace('{{ app.css for change }}', $css, $output);
            $output = str_replace('{{---}}', '<!--   Styles   -->', $output);

            $success = file_put_contents(base_path('/resources/views/' . $pathFileNameForApp), $output);

            $this->setAlreadyMadeApp('resources/views', $pathFileNameForApp);
        }

        $success1 = $this->writeJS();
        $success2 = $this->writeCSS();
        $success3 = $this->writeMix();


        return $success && $success1 && $success2 && $success3;
    }

    /**
     * @return bool
     */
    protected function writeJS()
    {
        $success1 = false;
        $success2 = false;
        $success3 = false;

        $jsFullPathName = View::getFullPathNameASSETsInBlade('js');

        if ($this->notExist('resources/assets/', $jsFullPathName)) {


            Helper::makeFileDirName('', View::getFullPathNameASSETsResource('js'));
            $success1 = \File::copy(
                __DIR__ . ('/../../../../resources/views/assets/resource/js.js'),
                base_path() . '/' . View::getFullPathNameASSETsResource('js')
            );

            $this->setAlreadyMadeApp('resources/assets/', View::getFullPathNameASSETsInBlade('js'));

        }


        $jsFullPathName = View::getFullPathNameASSETsInBlade('bootstrap');

        if ($this->notExist('resources/assets/', $jsFullPathName)) {

            Helper::makeFileDirName('', View::getFullPathNameASSETsPublic('js'));
            $success2 = \File::copy(
                __DIR__ . ('/../../../../resources/views/assets/resource/bootstrap.js'),
                base_path() . '/' . View::getFullPathNameASSETsPublic('bootstrap')
            );
            $this->setAlreadyMadeApp('resources/assets/', View::getFullPathNameASSETsInBlade('bootstrap'));
        }


        $jsFullPathName = View::getFullPathNameASSETsInBlade('js');

        if ($this->notExist('public/', $jsFullPathName)) {

            Helper::makeFileDirName('', View::getFullPathNameASSETsPublic('js'));
            $success3 = \File::copy(
                __DIR__ . ('/../../../../resources/views/assets/public/js.js'),
                base_path() . '/' . View::getFullPathNameASSETsPublic('js')
            );
            $this->setAlreadyMadeApp('public/', View::getFullPathNameASSETsInBlade('js'));

        }
        return $success1 && $success2 && $success3;
    }

    /**
     * @return bool
     */
    protected function writeCSS()
    {
        $success1 = false;
        $success2 = false;
        $success3 = false;

        $jsFullPathName = View::getFullPathNameASSETsInBlade('scss');

        if ($this->notExist('resources/assets/', $jsFullPathName)) {
            Helper::makeFileDirName('', View::getFullPathNameASSETsResource('scss'));
            $success1 = \File::copy(
                __DIR__ . ('/../../../../resources/views/assets/resource/scss.scss'),
                base_path('/' . View::getFullPathNameASSETsResource('scss'))
            );
            $this->setAlreadyMadeApp('resources/assets/', $jsFullPathName);
        }


        $jsFullPathName = View::getFullPathNameASSETsInBlade('css');

        if ($this->notExist('public/', $jsFullPathName)) {

            Helper::makeFileDirName('', View::getFullPathNameASSETsPublic('css'));
            $success2 = \File::copy(
                __DIR__ . ('/../../../../resources/views/assets/public/css.css'),
                base_path('/' . View::getFullPathNameASSETsPublic('css'))
            );
            $this->setAlreadyMadeApp('public/', $jsFullPathName);
        }


        $jsFullPathName = View::getFullPathNameASSETsInBlade('variables');

        if ($this->notExist('resources/assets/', $jsFullPathName)) {

            $success3 = \File::copy(
                __DIR__ . ('/../../../../resources/views/assets/resource/_variables.scss'),
                base_path('/' . View::getFullPathNameASSETsResource('variables'))
            );
            $this->setAlreadyMadeApp('resources/assets/', $jsFullPathName);
        }
        return $success1 && $success2 && $success3;
    }

    /**
     * @return bool
     */
    protected function writeMix()
    {
        $success = false;

        if ($this->notExist('/', 'webpack.mix.stub')) {

            $output = file_get_contents(__DIR__ . ('/../../../../resources/views/assets/resource/webpack.mix.stub'));


            $jsResource = View::getFullPathNameASSETsResource('js');
            $jsPublic = View::getFullPathNameASSETsPublic('js');

            $scssResource = View::getFullPathNameASSETsResource('scss');
            $cssPublic = View::getFullPathNameASSETsPublic('css');


            $output = str_replace('{{resources_js}}', $jsResource, $output);
            $output = str_replace('{{public_js}}', $jsPublic, $output);

            $output = str_replace('{{resources_scss}}', $scssResource, $output);
            $output = str_replace('{{public_css}}', $cssPublic, $output);
            $output = str_replace('\\', '/', $output);

            $success = file_put_contents(base_path() . '/webpack.mix.stub', $output);

            $this->setAlreadyMadeApp('/', 'webpack.mix.stub');
        }
        return $success;
    }

    protected function writeInc()
    {
        $newDirName = base_path() . '/resources/views/inc';
        if (!is_dir($newDirName)) {
            mkdir($newDirName);
        }
        $newDirName = base_path() . '/resources/views/inc/form';
        if (!is_dir($newDirName)) {
            mkdir($newDirName);
        }
        $success = false;
        $inViewDir = 'inc/form/relations.blade.php';
        if ($this->notExist('resources/views/', $inViewDir)) {
            $success = \File::copy(
                __DIR__ . '/Stubs/Views/app/' . $inViewDir,
                base_path('/resources/views/' . $inViewDir)
            );
            $this->setAlreadyMadeApp('resources/views/', $inViewDir);
        }
        $inViewDir = 'inc/form/select_relations.blade.php';
        if ($this->notExist('resources/views/', $inViewDir)) {
            $postfix = lcfirst(config('alex-claimer-generator.config.namespace_postfix'));

            $postfix = substr($postfix, 0, strpos($postfix, '\\')) . '/';

            $success = \File::copy(

                __DIR__ . '/Stubs/Views/app/' . $inViewDir,
                base_path('/resources/views/' . $inViewDir)
            );
            $this->setAlreadyMadeApp('resources/views/', $inViewDir);
        }
        $inViewDir = 'inc/msg.blade.php';
        if ($this->notExist('resources/views/', $inViewDir)) {
            $postfix = lcfirst(config('alex-claimer-generator.config.namespace_postfix'));

            $postfix = substr($postfix, 0, strpos($postfix, '\\')) . '/';

            $success = \File::copy(

                __DIR__ . '/Stubs/Views/app/' . $inViewDir,
                base_path('/resources/views/' . $inViewDir)
            );
            $this->setAlreadyMadeApp('resources/views/', $inViewDir);
        }
        $inViewDir = 'inc/errors.blade.php';
        if ($this->notExist('resources/views/', $inViewDir)) {
            $postfix = lcfirst(config('alex-claimer-generator.config.namespace_postfix'));

            $postfix = substr($postfix, 0, strpos($postfix, '\\')) . '/';

            $success = \File::copy(

                __DIR__ . '/Stubs/Views/app/' . $inViewDir,
                base_path('/resources/views/' . $inViewDir)
            );
            $this->setAlreadyMadeApp('resources/views/', $inViewDir);
        }

    }

    protected function writeMenu()
    {
        $success = false;
        if ($this->notExist('resources/views/', 'app.blade')) {
            $postfix = lcfirst(config('alex-claimer-generator.config.namespace_postfix')) . '/';

            $success = \File::copy(

                __DIR__ . ('/../../../../resources/views/layouts/app.blade.php'),
                base_path('/resources/views/' . $postfix . 'app.blade.php')
            );
            $this->setAlreadyMadeApp('resources/views/', $postfix . 'app.blade');
        }
        //dd(__METHOD__, $success);
        return $success;
    }

    protected function setAlreadyMadeViews($tName, $bladeName)
    {
        $postfix = lcfirst(config('alex-claimer-generator.config.namespace_postfix'));
        // dd(__METHOD__, $postfix);
        if ($this->notExist($tName, $bladeName)) {
            $this->viewsAlreadyMade[$tName][] = $this->alreadyMade[$tName][] = View::makeNameSpaceForView($tName, $bladeName);
        }
    }

    protected function setAlreadyMadeApp($tName, $bladeName)
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
//        if ($tName == 'resources/views/'){
//            dd(__METHOD__,
//                empty($this->viewsAlreadyMade),
//                $this->viewsAlreadyMade,
//                View::makeNameSpaceForView($tName, $bladeName),
//                !Arr::exists($this->viewsAlreadyMade, $tName),
//                View::makeNameSpaceForView($tName, $bladeName),
//                $this->viewsAlreadyMade[$tName],
//                !in_array(View::makeNameSpaceForView($tName, $bladeName), $this->viewsAlreadyMade[$tName])
//            );
//        }

        return (empty($this->viewsAlreadyMade)
            || !Arr::exists($this->viewsAlreadyMade, $tName)
            || (
                !in_array(View::makeNameSpaceForView($tName, $bladeName), $this->viewsAlreadyMade[$tName])
                &&
                !in_array($bladeName, $this->viewsAlreadyMade[$tName])
            )
        );
    }

    protected function getPathFileNameForApp($type = '.')
    {
        // $type = '.' or '/'
        $postfix = lcfirst(config('alex-claimer-generator.config.namespace_postfix'));

        if (empty($postfix)) {
            return 'app';
        } else {
            if ($pos = strpos($postfix, '\\'))
                $prefix = substr($postfix, 0, $pos);
            else $prefix = $postfix;
        }
        if ($type == '.') {

            return str_replace('/', '.', str_replace('\\', '/', lcfirst($prefix)) . '.app');

        } elseif ($type == '/') {

            return str_replace('\\', '/', lcfirst($prefix) . '/app.blade.php');
        }

    }

    protected function strings_replace($tName, $cNames, $stub)
    {
        $postfix = lcfirst(config('alex-claimer-generator.config.namespace_postfix'));
        $output = file_get_contents(__DIR__ . '/Stubs/Views/' . $stub);

        if (empty($postfix)) {
            $output = str_replace('{{postfix}}', '', $output);
        } else {
            $output = str_replace('{{postfix}}', Route::make_routes_prefix(), $output);
        }

        $output = str_replace('{{dir.app}}', $this->getPathFileNameForApp(), $output);

        $output = str_replace('{{route_name_without_action_and_\')}} }}', '{{route(\'' . Route::make_routes_name($tName) . '', $output);
        $output = str_replace('{{postfix/}}', $postfix . '/', $output);
        $output = str_replace('{{table_name}}', $tName, $output);
        $output = str_replace('{{ModelNameSpace}}', Helper::fullNameSpace($tName), $output);
        $output = str_replace('{{belongsToComment}}', '', $output); //11 replace with something


        return $output;
    }


//    protected function writeIndexBlade()
//    {
//        $bladeName = "index.blade";
//
//        foreach ($this->tablesNames as $tName => $cNames) {
//            //bbb(__METHOD__,Helper::makeNameSpace('model').Helper::className($tName), $tName, $cNames);
//            // break;
//            if ($this->notExist($tName, $bladeName)) {
//
//                $output = $this->strings_replace($tName, $cNames, 'index.blade.stub');
//
//                $this->setAlreadyMadeViews($tName, $bladeName);
//
//                file_put_contents(Helper::makeFileDirName('view', $bladeName, $tName), $output);
//            }
//        }
//
//        //dd(__METHOD__, $this->viewsAlreadyMade);
//    }

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
        $nameSpace = Helper::getPostfix();
        $this->viewsAlreadyMade = Arr::sort($this->viewsAlreadyMade);
        //dd(__METHOD__, $this->viewsAlreadyMade);
        $str_viewsAlreadyMade = "<?php\r\nreturn [\r\n";
        $str_viewsAlreadyMade .= "\t'views' => [\r\n";
        foreach ($this->viewsAlreadyMade as $type => $arr) {

            $str_viewsAlreadyMade .= "\t\t'$type' => [\r\n";

            foreach ($arr as $table => $name) {

                //dd(__METHOD__, $name);

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
                '/Stubs/Views/inc/1/edit_columns/is_publishedHead.stub');
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
                '/Stubs/Views/inc/1/edit_columns/title.stub');
            $output = str_replace('{{title}}', $title, $output);
        } elseif (Arr::exists($cNames, 'name')) {
            $name = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/1/edit_columns/name.stub');
            $output = str_replace('{{title}}', $name, $output);
        } else {
            $output = str_replace('{{title}}', '', $output);
        }
        if (Arr::exists($cNames, 'content_row')) {
            //bbb(__METHOD__, $tName, $cNames);
            $content_row = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/1/edit_columns/content_row.stub');
            $output = str_replace('{{content_row}}', $content_row, $output);
        } else {
            $output = str_replace('{{content_row}}', '', $output);
        }
        if (Arr::exists($belongsTo = $this->tables->getBelongsToKeys(), $tName)) {
            $arrBelongsTo = $this->tables->getBelongsToKeys();

            foreach ($arrBelongsTo[$tName]['belongsTo'] as $arKeyToTable) {
                // if($tName == 'auth_roles')bbb(__METHOD__, $tName, $arKeyToTable);//11111111111111
                $var = ($arKeyToTable['to_table']);
                $belongsTo = file_get_contents(__DIR__ .
                    '/Stubs/Views/inc/1/edit_columns/belongsTo.stub');
                $belongsTo = str_replace('{{modelBelongsTo}}', $var, $belongsTo);
                $belongsTo = str_replace('{{BelongsToKey}}',
                    (substr($arKeyToTable['key'], 0, strpos($arKeyToTable['key'], '_'))),
                    $belongsTo);
                // if($tName == 'auth_roles')dd('-------------------------------',__METHOD__, $tName, $arrBelongsTo[$tName]['belongsTo'] , $arrBelongsTo );
                $output = str_replace('{{belongsTo}}', $belongsTo, $output);
            }
        } else {
            $output = str_replace('{{belongsTo}}', '', $output);
        }


        if (Arr::exists($cNames, 'slug')) {
            //bbb(__METHOD__, $tName, $cNames);
            $slug = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/1/edit_columns/slug.stub');
            $output = str_replace('{{slug}}', $slug, $output);
        } else {
            $output = str_replace('{{slug}}', '', $output);
        }
        if (Arr::exists($cNames, 'is_slugChange') || true) {//11?? || true
            //bbb(__METHOD__, $tName, $cNames);
            $is_slugChange = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/1/edit_columns/is_slugChange.stub');
            $output = str_replace('{{is_slugChange}}', $is_slugChange, $output);
        } else {
            $output = str_replace('{{is_slugChange}}', '', $output);
        }


        if (Arr::exists($cNames, 'excerpt')) {
            //bbb(__METHOD__, $tName, $cNames);
            $excerpt = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/1/edit_columns/excerpt.stub');
            $output = str_replace('{{excerpt}}', $excerpt, $output);
        } else {
            $output = str_replace('{{excerpt}}', '', $output);
        }

        if (Arr::exists($cNames, 'is_published')) {
            //bbb(__METHOD__, $tName, $cNames);
            $is_published = file_get_contents(__DIR__ .
                '/Stubs/Views/inc/1/edit_columns/is_published.stub');
            $output = str_replace('{{is_published}}', $is_published, $output);
        } else {
            $output = str_replace('{{is_published}}', '', $output);
        }
        return $output;
    }
}

