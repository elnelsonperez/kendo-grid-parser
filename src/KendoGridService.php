<?php

namespace ElNelsonPerez\KendroGridParser;

use ElNelsonPerez\KendroGridParser\Base\Contracts\IKendoQueryBuilderAdapter;
use ElNelsonPerez\KendroGridParser\Base\KendoGridParser;
use ElNelsonPerez\KendroGridParser\Exceptions\KendoGridServiceException;

class KendoGridService
{

    public function execute (array $input, array $columns, &$query) {
        $classname = (new \ReflectionClass($query))->getName();

        if (isset(config('kendo_grid_parser.adapters')[$classname])) {
            $adapter = config('kendo_grid_parser.adapters')[$classname];
        } else {
            throw new KendoGridServiceException("No query builder adapter for $classname");
        }

        if (isset(config('kendo_grid_parser.parsers')[$classname])) {
            $parser = config('kendo_grid_parser.parsers')[$classname];
        } else {
            throw new KendoGridServiceException("No kendo grid parser for $classname");
        }

        /**
         * @var $adapter IKendoQueryBuilderAdapter
         */
        $adapter = $adapter::createFromBuilder($query);

        /**
         * @var $parser KendoGridParser
         */
        $parser = new $parser();
        return $parser->execute($input, $columns, $adapter);
    }

}