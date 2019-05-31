<?php

namespace AlexClaimer\Generator\App\Providers;

use Illuminate\Support\ServiceProvider;
use AlexClaimer\Generator\App\Console\Commands\MakeClassesFromDB;
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
                return new MakeClassesFromDB($app['files']);
            }
        );
        $this->commands(
            'command.make.classes'
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

