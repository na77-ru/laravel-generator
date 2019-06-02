<?php

namespace AlexClaimer\Generator\App\Console\Commands;


use AlexClaimer\Generator\App\Services\Generator\Main;
use App\Services\Generator\MakeModel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Filesystem\Filesystem;
use AlexClaimer\Generator\App\Services\Generators\Migrations\MakeMigration;

class MakeMigrationCommand extends Command
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
        dd(__METHOD__, $this->argument('name'));


        $param = [
            'name' => 'post___user',
            'className' => 'Post___user',
            'namespace' => null,
            'laravel' => true,
            'table' => null,
            'fields' => [],
            'foreignKeys' => [],
            'tableComment' => '',
        ];
        $MakeMigration = new MakeMigration($param);
        $result = $MakeMigration->GenerateMigration($param, $message);

        if ($result){
            return redirect('generator_create_migration')
                ->with([
                    'messages' => 'Migrations created successfully'
                ]);
        }else{
            return redirect('generator_create_migration')
                ->withErrors(['msg' => ['Migrations created error', $message]])
                ->withInput();
        }

    }
}
