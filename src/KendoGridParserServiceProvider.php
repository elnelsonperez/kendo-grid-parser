<?php

namespace ElNelsonPerez\KendoGridParser;

use Illuminate\Support\ServiceProvider;

class KendoGridParserServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(KendoGridService::class, $this->app->make(EloquentKendoGridService::class));
        $this->app->bind(IKendoQueryBuilderAdapter::class, $this->app->make(EloquentBuilderAdapter::class));
    }

}