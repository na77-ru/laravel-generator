<?php

namespace AlexClaimer\Generator\App\Providers;

use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Illuminate\Support\ServiceProvider;
use AlexClaimer\Generator\App\Console\Commands\MakeClassesCommand;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Publishes configuration file.
     *
     * @return  void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../../../resources/views', 'generator_views');

        //dd(__METHOD__);
        $this->publishes([__DIR__ . '/../../../resources/css/app.css'
        => base_path('public/vendor/alex-claimer/generator/css/app.css')],
            'alex-claimer-generator-config');
        $this->publishes([__DIR__ . '/../../../resources/js/app.js'
        => base_path('public/vendor/alex-claimer/generator/js/app.js')],
            'alex-claimer-generator-config');
        $this->publishes([__DIR__ . '/../../../config/config.php' => config_path('alex-claimer-generator/config.php')],
            'alex-claimer-generator-config');


    }

    /**
     * Make config publishment optional by merging the config from the package.
     *
     * @return  void
     */
    public function register()
    {
        //dd(__METHOD__);
        $this->mergeConfigFrom(__DIR__ . '/../../../config/config.php', 'alex-claimer-generator.config');




        $localViewFactory = $this->createLocalViewFactory();

        $this->app->singleton(
            'command.make.classes',
            function ($app) use ($localViewFactory) {
                return new MakeClassesCommand($app['files']);
            }
        );

//        $this->app->singleton(
//            'command.generate:migration',
//            function ($app) {
//                return new MakeMigrationCommand($app['files']);
//            }
//        );
        $this->commands(
            'command.make.classes'
           // 'command.generate:migration'
        );
    }
    /**
     * @return Factory
     */
    private function createLocalViewFactory()
    {
        $resolver = new EngineResolver();
        $resolver->register('php', function () {
            return new PhpEngine();
        });
        $finder = new FileViewFinder($this->app['files'], [__DIR__ . '/../resources/views']);
        $factory = new Factory($resolver, $finder, $this->app['events']);
        $factory->addExtension('php', 'php');

        return $factory;
    }
}

