<?php

namespace AlexClaimer\Generator\App\Services\Generator\MakeViews;

use AlexClaimer\Generator\App\Services\Generator\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class MakeColumnsForViewEdit
{
    protected $tablesNamesData = [];
    protected $allRelations = [];

    protected $allRelationsDot = [];

    /**
     * MakeView constructor.
     * @param Table $tables
     */
    public function __construct($tables, $tName, $cNames, $bladeName)
    {

        $this->tablesNamesData = $tables->getTablesNamesData();
        $this->allRelations = $tables->getAllRelations();

        $this->allRelationsDot = Arr::dot($tables->getAllRelations());

        $this->writeBlade($tName, $cNames, $bladeName);
    }

    protected function writeBlade($tName, $cNames, $bladeName)
    {
        $output = file_get_contents(__DIR__ . '/../Stubs/Views/inc/1/columns_for_edit.blade.stub');

        $output = str_replace(
            '{{ModelNameSpace}}',
            Helper::makeNameSpace('model') . '\\' . Helper::className($tName),
            $output
        );

        $output = str_replace(
            '{{columns}}',
            $this->getColumns($tName),
            $output
        );

        $output = $this->getHeadIsPublished($cNames, $output);

        file_put_contents(Helper::makeFileDirName('view', $bladeName, $tName), $output);

    }


    protected function getColumns($tName)
    {
        $str = "";
        //  ignored_columns_in_edit_create_views  from config
        $ignored_columns = config('alex-claimer-generator.config.ignored_columns_in_edit_create_views');

        foreach ($this->tablesNamesData[$tName] as $colName => $data) {

            if (!in_array($colName, $ignored_columns)) {

                //NOT 'id', 'is_published', 'slug', '*_id'
                if ($colName != 'id' && !strpos($colName, '_id') && $colName != 'is_published' && $colName != 'slug') {

                    $required = $data['required'];
                    // FOR  checkbox
                    if (strpos(' ' . $data['Type'], 'tinyint')) {
                        $str .= $this->getColumnsCheckBoxData($colName, $required);
                    } // FOR input text
                    elseif (strpos(' ' . $data['Type'], 'varchar') || strpos(' ' . $data['Type'], 'int')) {
                        $str .= $this->getColumnsVarCharData($colName, $required);
                    } // FOR  textarea
                    elseif (strpos(' ' . $data['Type'], 'text')) {
                        $str .= $this->getColumnsTextareaData($colName, $required);
                    }


                } elseif ($colName == 'slug') {

                    $str .= $this->getColumnSlug(); //FOR 'slug'

                } elseif ($colName == 'is_published') {

                    $str .= $this->getColumnIsPublished();; //FOR  'is_published'
                } else {

                    $str .= ''; //FOR 'id', '*_id'
                }

            }
        }
        //Relations
        $str .= $this->getRelations($tName, $str);
        //dd(__METHOD__, $this->allRelations, $this->tablesNamesData, $str);
        return $str;
    }

    /**
     * @param $colName
     * @param $required
     * @return false|mixed|string
     */
    protected function getColumnsCheckBoxData($colName, $required)
    {
        $stub = file_get_contents(__DIR__ .
            '/../Stubs/Views/inc/1/form/checkbox.stub');

        $stub = str_replace('{{name}}', $colName, $stub);
        $stub = str_replace('{{Name}}', Str::ucfirst($colName), $stub);
        $stub = str_replace('required', $required, $stub);

        return $stub;
    }

    /**
     * @param $colName
     * @param $required
     * @return false|mixed|string
     */
    protected function getColumnsVarCharData($colName, $required)
    {
        $stub = file_get_contents(__DIR__ .
            '/../Stubs/Views/inc/1/form/input_text.stub');

        $stub = str_replace('{{name}}', $colName, $stub);
        $stub = str_replace('{{Name}}', Str::ucfirst($colName), $stub);
        $stub = str_replace('required', $required, $stub);

        return $stub;
    }

    /**
     * @param $colName
     * @param $required
     * @return false|mixed|string
     */
    protected function getColumnsTextareaData($colName, $required)
    {
        $stub = file_get_contents(__DIR__ .
            '/../Stubs/Views/inc/1/form/textarea.stub');

        $stub = str_replace('{{name}}', $colName, $stub);
        $stub = str_replace('{{Name}}', Str::ucfirst($colName), $stub);
        $stub = str_replace('required', $required, $stub);

        return $stub;
    }

    /**
     * @return false|string
     */
    protected function getColumnIsPublished()
    {
        $stub = file_get_contents(__DIR__ .
            '/../Stubs/Views/inc/1/form/is_published.stub');

        return $stub;
    }

    /**
     * @param $cNames
     * @param $output
     * @return mixed
     */
    protected function getHeadIsPublished($cNames, $output)
    {
        if (Arr::exists($cNames, 'is_published')) {
            $is_publishedHead = file_get_contents(__DIR__ .
                '/../Stubs/Views/inc/1/form/is_publishedHead.stub');
            $output = str_replace('{{is_publishedHead}}', $is_publishedHead, $output);
        } else {
            $output = str_replace('{{is_publishedHead}}', '', $output);
        }
        return $output;
    }

    protected function getColumnFromPropertyForSelect($tName)
    {
        if (Arr::exists($this->tablesNamesData, $tName)) {

            if (Arr::exists($this->tablesNamesData[$tName], 'title')) {
                return 'title';
            } elseif (Arr::exists($this->tablesNamesData[$tName], 'name')) {
                return 'name';
            } elseif (Arr::exists($this->tablesNamesData[$tName], 'slug')) {
                return 'slug';
            }
        }
        return 'id';
    }

    /**
     * @param $tName
     * @return string
     */
    protected function getRelations($tName)
    {
        $output = '';
        if (Arr::exists($this->allRelations, $tName)) {

            foreach ($this->allRelations[$tName] as $cName => $data) {

                //dd(__METHOD__, $tName, $cName, $data, $this->allRelations);

                if ($data['type'] === 'belongsTo') {
                    $output .= $this->getBelongsTo($cName, $data);

                } elseif ($data['type'] === 'belongsToMany') {
                    $output .= $this->getBelongsToMany($cName, $data);

                } elseif ($data['type'] === 'hasMany') {
                    $output .= $this->getHasMany($cName, $data);
                }
            }
        }
        return $output;
    }


    /**
     * @param $property
     * @param $data
     * @return false|mixed|string
     */
    protected function getBelongsTo($property, $relData)
    {

        //if ($property == 'user') dd(__METHOD__, $property, $relData, $this->allRelations);
        $fullClassName = Helper::makeNameSpace('model') . Helper::className($relData['to_table']);
        $comments = "\t\t\t\t@php /**@var " . $fullClassName . " \$" . $relData['to_table'] . "Option */ @endphp\r\n";
        $comments .= "\t\t\t\t@php /**@var " . $fullClassName . " \$item->" . $property . " */ @endphp";

        $output = file_get_contents(__DIR__ . '/../Stubs/Views/inc/1/form/belongsTo.stub');

        $column = $this->getColumnFromPropertyForSelect($relData['to_table']);
        $output = str_replace('{{column}}', $column, $output);
        $output = str_replace('{{old_column}}', $relData['to_table'] . '_' . $column, $output);

        $output = str_replace('{{modelBelongsToComments}}', $comments, $output);
        if ($relData['required'] === '') {
            $output = str_replace('{{option_for_null_value}}', "\t\t\t\t<option value=\"\" selected></option>", $output);
        } else {
            $output = str_replace('{{option_for_null_value}}', "", $output);
        }

        $output = str_replace('{{modelBelongsTo}}', $relData['to_table'], $output);
        $output = str_replace('{{Property}}', $property, $output);

        return $output;
    }

    /**
     * @param $property
     * @param $data
     * @return string
     */
    protected function getBelongsToMany($property, $data)
    {
        //dd($data);
        $arrColumns = $this->tablesNamesData[$data['to_table']];

        if (Arr::exists($arrColumns, 'name')) {
            $name = 'name';
        } elseif (Arr::exists($arrColumns, 'title')) {
            $name = 'title';
        } elseif (Arr::exists($arrColumns, 'slug')) {
            $name = 'slug';
        } elseif (Arr::exists($arrColumns, 'id')) {
            $name = 'id';
        } elseif (Arr::exists($arrColumns, 'comment')) {
            $name = 'comment';
        } else {
            $name = null;
        }
        $output = "\r\n\t\t\t\t\t\t\t@include('inc.form.select_relations',
                         [
                         'relationsList' => \$" . $data['to_table'] . "List,
                            'relationName' => '" . $property . "',
                            'columnName' => '" . $name . "'
                          ]
                          )";
        return $output;
    }

    /**
     * @param $tName
     * @param $output
     * @return string
     */
    protected function getHasMany($property, $data)
    {
        $output = '';
        return $output;
    }


    /**
     * @return false|string
     */
    protected function getColumnSlug()
    {
        $stub = file_get_contents(__DIR__ .
            '/../Stubs/Views/inc/1/form/slug.stub');

        return $stub;
    }

}

