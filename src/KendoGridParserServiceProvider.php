<?php

namespace ElNelsonPerez\KendoGridParser;

use Illuminate\Support\ServiceProvider;

class KendoGridParserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('kendo_grid_parser.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config.php', 'kendo_grid_parser'
        );

    }
}
