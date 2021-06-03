<?php

namespace AwStudio\Indexer;

use Illuminate\Support\ServiceProvider;
use AwStudio\Indexer\Commands\CreateIndexCommand;

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

        $this->app->bind(PageIndex::class, function ($searchterm) {
            return new PageIndex($searchterm);
        });
    }
}
