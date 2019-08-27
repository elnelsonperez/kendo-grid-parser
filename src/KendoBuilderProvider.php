<?php

namespace ElNelsonPerez\KendroGridParser;

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
            app_path('Services/KendoGrid/config.php') => config_path('kendo_grid_parser.php'),
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
            app_path('Services/KendoGrid/config.php'), 'kendo_grid_parser'
        );

    }
}
