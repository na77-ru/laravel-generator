<?php

namespace AlexClaimer\Generator\App\Services\Generators\Migrations;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class MakeMigration
{

    protected $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function GenerateMigration($param, &$message = null)
    {

        if (($param['pivot'] || $param['only_pivot']) && (empty($param['name_1']) || empty($param['name_2']))) {
            $message = 'pivot table need two tables to ref';
            return false;
        }

        $param['short_name_1'] = $param['name_1'];
        $param['short_name_2'] = $param['name_2'];

        if (!empty(trim($param['prefix']))) {
            $param['prefix'] .= '_';
        }


        $output = $this->generateMigrationsFromStub($param);

        return true;
    }

    protected function generateMigrationsFromStub($param)
    {
        $newParam = [];
        $stubDirOneTable = $this->getStubDirOneTable();
        $stubDirPivotTable = $this->getStubDirPivotTable();

        $notOnlyPivot = !$param['only_pivot'];
//dd(__METHOD__, $notOnlyPivot);
        if ($notOnlyPivot) {
            for ($i = 1; $i <= 2; $i++) {
                if (!empty($param['name_' . $i])) {
                    $newParam['name'] = $param['prefix'] . $param['name' . '_' . $i];
                    $newParam['prefix'] = $param['prefix'];
                    $newParam['columns_id'] = $param['columns_id_' . $i];
                    $newParam['id'] = $param['id' . '_' . $i];
                    $newParam['only_pivot'] = $param['only_pivot'];
                    $newParam['slug'] = $param['slug' . '_' . $i];
                    $newParam['title'] = $param['title' . '_' . $i];
                    $newParam['description'] = $param['description' . '_' . $i];
                    $newParam['active'] = $param['active' . '_' . $i];
                    $newParam['is_published'] = $param['is_published' . '_' . $i];
                    $newParam['published_at'] = $param['published_at' . '_' . $i];
                    $newParam['timestamps'] = $param['timestamps' . '_' . $i];
                    $newParam['softDeletes'] = $param['softDeletes' . '_' . $i];

                    $newParam['className'] = $this->getClassName($newParam['name']);

                    $output = $this->getStubMigrationOneTable($stubDirOneTable, $newParam);

                    $migrationDirName = $this->getMigrationDirName($newParam['name'], $i);

                    file_put_contents($migrationDirName, $output);


                }
            }
        }
        if ($param['pivot'] || $param['only_pivot']) {
            //if ($param['pivot'])

            $param['name_1'] = $param['prefix'] . $param['name_1'];
            $param['name_2'] = $param['prefix'] . $param['name_2'];

            $param['name'] = $this->getPivotTableName($param);
            $param['className'] = $this->getPivotClassName($param['name']);

            $output = $this->getStubMigrationPivotTable($stubDirPivotTable, $param);

            $migrationDirName = $this->getMigrationDirName($param['name'], 3);

            file_put_contents($migrationDirName, $output);
        }

        //dd(__METHOD__, $param);//11
    }

    protected function getStubMigrationOneTable($stubDirOneTable, $param)
    {
        $output = file_get_contents($stubDirOneTable);
        $output = str_replace('{{className}}', $param['className'], $output);
        $output = str_replace('{{tableName}}', $param['name'], $output);

        $columns = $this->getColumns($param);
        $output = str_replace('{{columns}}', $columns, $output);

        return $output;
    }

    protected function getStubMigrationPivotTable($stubDirOneTable, $param)
    {
        $output = file_get_contents($stubDirOneTable);
        $output = str_replace('{{className}}', $param['className'], $output);
        $output = str_replace('{{tableName}}', $param['name'], $output);

        $columns = $this->getPivotColumns($param);
        $output = str_replace('{{columns}}', $columns, $output);

        return $output;
    }

    protected function getColumns($param)
    {
        $columns = '';
        if ($param['id']) $columns .= "\$table->bigIncrements('id');\r\n";

        if (!empty($param['columns_id'])) {

            $arColumns = explode(' ', $param['columns_id']);

            foreach ($arColumns as $column_id) {
                if ($column_id == 'parent_id') {
                    $columns .= "\t\t\t\$table->bigInteger('parent_id')->unsigned();\r\n";
                    $columns .= "\t\t\t\$table->foreign('parent_id')->references('id')
                ->on('" . $param['name'] . "');\r\n";
                } else {
                    $tableForeignName = Str::plural(str_replace('_id', '', $column_id));

                    $tableForeignName = $param['prefix'] . $tableForeignName;

                    $columns .= "\t\t\t\$table->bigInteger('" . $column_id . "')->unsigned();\r\n";
                    $columns .= "\t\t\t\$table->foreign('" . $column_id . "')->references('id')->on('" . $tableForeignName . "');\r\n";
                }
            }
        }

        if ($param['slug']) $columns .= "\t\t\t\$table->string('slug')->unique();\r\n";
        if ($param['title']) $columns .= "\t\t\t\$table->string('title');\r\n";
        if ($param['description']) $columns .= "\t\t\t\$table->string('description')->nullable();\r\n";
        if ($param['active']) $columns .= "\t\t\t\$table->boolean('active')->default(false);\r\n";
        if ($param['is_published']) $columns .= "\t\t\t\$table->boolean('is_published')->default(false);\r\n";
        if ($param['published_at']) $columns .= "\t\t\t\$table->timestamp('published_at')->nullable();\r\n";
        if ($param['timestamps']) $columns .= "\t\t\t\$table->timestamps();\r\n";
        if ($param['softDeletes']) $columns .= "\t\t\t\$table->softDeletes();\r\n";

        return $columns;
    }

    protected function getPivotColumns($param)
    {
        $name1 = Str::singular($param['short_name_1']);
        $name2 = Str::singular($param['short_name_2']);
        $name_1 = $param['prefix'] . $param['short_name_1'];
        $name_2 = $param['prefix'] . $param['short_name_2'];

        $columns = '';
        $columns .= "\$table->bigInteger('" . $name1 . "_id')->unsigned();\r\n";
        $columns .= "\t\t\t\$table->bigInteger('" . $name2 . "_id')->unsigned();\r\n";
        $columns .= "\t\t\t\$table->string('comment')->default('')->comment('комментарий');\r\n";
        $columns .= "\t\t\t\$table->text('description')->comment('описание');\r\n";
        $columns .= "\t\t\t\$table->tinyInteger('active')->default(0);\r\n";
        $columns .= "\t\t\t\$table->timestamp('published_at')->nullable();\r\n";
        $columns .= "\t\t\t\$table->timestamps();\r\n";
        $columns .= "\t\t\t\$table->softDeletes();\r\n\r\n";

        $columns .= "\t\t\t\$table->primary(['" . $name1 . "_id', '" . $name2 . "_id']);\r\n";
        $columns .= "\t\t\t\$table->index('" . $name1 . "_id', 'idx-" . $name_1 . "-" . $name1 . "_id');\r\n";
        $columns .= "\t\t\t\$table->foreign('" . $name1 . "_id')->references('id')->on('" . $name_1 . "')->onDelete('restrict');\r\n";
        $columns .= "\t\t\t\$table->index('" . $name2 . "_id', 'idx-" . $name_2 . "-" . $name2 . "_id');\r\n";
        $columns .= "\t\t\t\$table->foreign('" . $name2 . "_id')->references('id')->on('" . $name_2 . "')->onDelete('restrict');\r\n";

        $columns .= "\t\t\t\$table->engine = 'InnoDB';\r\n";

        return $columns;
    }

    protected function getClassName($name)
    {
        return 'Create' . ucfirst(Str::camel($name)) . 'Table';
    }

    protected function getPivotClassName($name)
    {
        return 'Create' . ucfirst(Str::camel($name)) . 'Table';
    }


    protected function getPivotTableName($param)
    {
        return $param['prefix'] . 'pivot_' . Str::singular($param['short_name_1']) . '_' . $param['short_name_2'];
    }

    /**
     * Parse the name and format.
     *
     * @param string $name
     * @return string
     */
    protected function getMigrationFileName($name, $i)
    {
        return Carbon::now()->addSecond($i)->format('Y_m_d_his') . '_create_' . $name . '_table.php';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStubDirOneTable()
    {
        return __DIR__ . '/stubs/one_table.stub';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStubDirPivotTable()
    {
        return __DIR__ . '/stubs/pivot_table.stub';
    }

    /**
     * @param $name
     * @return string
     */
    protected function getMigrationDirName($name, $i)
    {
        return base_path() . '\database\migrations\\' . $this->getMigrationFileName($name, $i);
    }


    /**
     * Renders a view file as a PHP script.
     *
     * This method treats the view file as a PHP script and includes the file.
     * It extracts the given parameters and makes them available in the view file.
     * The method captures the output of the included view file and returns it as a string.
     *
     * This method should mainly be called by view renderer or [[renderFile()]].
     *
     * @param string $_file_ the view file.
     * @param array $_params_ the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @return string the rendering result
     * @throws \Exception
     * @throws \Throwable
     */
    public function getFromPhpTemplate($_file_, $_params_ = [])
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {

            require $_file_;
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}
