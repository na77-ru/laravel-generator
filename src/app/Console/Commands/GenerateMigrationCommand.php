<?php

namespace AlexClaimer\Generator\App\Console\Commands;


use AlexClaimer\Generator\App\Services\Generator\Main;
use App\Services\Generator\MakeModel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Filesystem\Filesystem;
use AlexClaimer\Generator\App\Services\Generators\Migrations\MakeMigration;

class GenerateMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:migration{name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate migration';

    /**
     * @var Filesystem $files
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $name = $this->argument('name');

        // dd(__METHOD__, $name, strpos($name,  $name, '_prefix_'));

        if (strpos($name, '_prefix_')) {
            $arCommand = explode('_prefix_', $name);
            $name = $arCommand[0];
            $prefix = $arCommand[1];
        } else {
            $prefix = '';
        }


        $arName = [];
        $newParam['pivot'] = false;
        $newParam['only_pivot'] = false;

        if (strpos($name, '___')) {
            $arName = explode('___', $name);
            $newParam['only_pivot'] = true;
            $newParam['name_1'] = $arName[0];
            $newParam['name_2'] = $arName[1];
        } elseif (strpos($name, '__')) {
            $arName = explode('__', $name);
            $newParam['pivot'] = true;
            $newParam['name_1'] = $arName[0];
            $newParam['name_2'] = $arName[1];
        } elseif (strpos($name, '_')) {
            $arName = explode('_', $name);
            $newParam['name_1'] = $arName[0];
            $newParam['name_2'] = $arName[1];
        } elseif (!strpos($name, '_')) {
            $newParam['name_1'] = $name;
            $newParam['name_2'] = '';
        }


        $newParam['prefix'] = $prefix;


        $newParam['id_1'] = true;
        $newParam['slug_1'] = true;
        $newParam['title_1'] = true;
        $newParam['description_1'] = true;
        $newParam['active_1'] = true;
        $newParam['is_published_1'] = true;
        $newParam['published_at_1'] = true;
        $newParam['timestamps_1'] = true;
        $newParam['softDeletes_1'] = true;
        
        $newParam['id_2'] = true;
        $newParam['slug_2'] = true;
        $newParam['title_2'] = true;
        $newParam['description_2'] = true;
        $newParam['active_2'] = true;
        $newParam['is_published_2'] = true;
        $newParam['published_at_2'] = true;
        $newParam['timestamps_2'] = true;
        $newParam['softDeletes_2'] = true;

        $newParam['columns_id'] = 'parent_id user_id';

       // dd(__METHOD__, $newParam);

        $MakeMigration = new MakeMigration($newParam);
        $MakeMigration->GenerateMigration($newParam, $message);


    }
}
