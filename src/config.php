<?php
return [
    /**
     * Relation for which query builder adapter and parser must be used for a query builder instance
     */
    'adapters' => [
        \Illuminate\Database\Query\Builder::class => \ElNelsonPerez\KendoGridParser\Adapters\EloquentBuilderAdapter::class,
        \Illuminate\Database\Eloquent\Builder::class => \ElNelsonPerez\KendoGridParser\Adapters\EloquentBuilderAdapter::class,

        \Tinderbox\ClickhouseBuilder\Integrations\Laravel\Builder::class => \ElNelsonPerez\KendoGridParser\Adapters\ClickHouseBuilderAdapter::class,
    ],
    'parsers' => [
        \Illuminate\Database\Query\Builder::class => \ElNelsonPerez\KendoGridParser\Parsers\EloquentKendoGridParser::class,
        \Illuminate\Database\Eloquent\Builder::class => \ElNelsonPerez\KendoGridParser\Parsers\EloquentKendoGridParser::class,

        \Tinderbox\ClickhouseBuilder\Integrations\Laravel\Builder::class => \ElNelsonPerez\KendoGridParser\Parsers\ClickHouseKendoGridParser::class,
    ]
];