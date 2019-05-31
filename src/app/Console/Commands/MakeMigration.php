<?php

namespace AlexClaimer\Generator\App\Console\Commands;


use AlexClaimer\Generator\App\Services\Generator\Main;
use App\Services\Generator\MakeModel;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Filesystem\Filesystem;

class MakeMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MakeModelsFromDB for MakeModel from DB';

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
        new Main();

    }
}
