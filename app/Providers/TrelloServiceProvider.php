<?php

namespace App\Providers;

use App\Services\TrelloApiService;
use App\Services\TrelloDbService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TrelloServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('trello-api', function () {
            return new TrelloApiService(config('trello'));
        });

        $this->app->singleton('trello-db', function () {
            return new TrelloDbService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['trello'];
    }
}
