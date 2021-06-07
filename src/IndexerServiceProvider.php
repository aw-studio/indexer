<?php

namespace AwStudio\Indexer;

use AwStudio\Indexer\Commands\CreateIndexCommand;
use Illuminate\Support\ServiceProvider;

class IndexerServiceProvider extends ServiceProvider
{
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

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateIndexCommand::class,
            ]);
        }
    }
}
