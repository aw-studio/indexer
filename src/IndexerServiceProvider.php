<?php

namespace AwStudio\Indexer;

use Illuminate\Support\ServiceProvider;
use AwStudio\Indexer\Commands\RunCommand;
use AwStudio\Indexer\Commands\CreateIndexCommand;
use AwStudio\Indexer\Contracts\HtmlLoader;

class IndexerServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHtmlLoader();
        $this->registerRunCommand();

        if ($this->app->runningInConsole()) {
            $this->commands(['indexer.commands.run']);
        }
    }

    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/indexer.php' => config_path('indexer.php'),
        ], 'indexer');

        $this->publishes([
            __DIR__.'/../migrations' => database_path('migrations'),
        ], 'indexer');
    }

    /**
     * Register indexer:run command.
     *
     * @return void
     */
    protected function registerRunCommand()
    {
        $this->app->singleton('indexer.commands.run', function ($app) {
            return new RunCommand($app['indexer.loader']);
        });
    }

    /**
     * Register html loader.
     *
     * @return void
     */
    protected function registerHtmlLoader()
    {
        $this->app->singleton('indexer.loader', function ($app) {
            $parser = $app['config']['indexer.html_loader'];

            return new $parser;
        });

        // Enable automated dependency injection on the interface.
        $this->app->alias('indexer.loader', HtmlLoader::class);
    }
}
