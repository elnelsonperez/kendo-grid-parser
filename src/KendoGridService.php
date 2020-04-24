<?php

namespace ElNelsonPerez\KendoGridParser;

use ElNelsonPerez\KendoGridParser\Base\Contracts\IKendoQueryBuilderAdapter;
use ElNelsonPerez\KendoGridParser\Base\KendoGridParser;
use ElNelsonPerez\KendoGridParser\Exceptions\KendoGridServiceException;

class KendoGridService
{

    public function execute (array $input, array $columns, $query) {
        $cloned_query = clone $query;
        $classname = (new \ReflectionClass($cloned_query))->getName();

        $adapter = $this->getAdapter($classname);
        $parser = $this->getParser($classname);

        /**
         * @var $adapter IKendoQueryBuilderAdapter
         */
        $adapter = $adapter::createFromBuilder($cloned_query);

        /**
         * @var $parser KendoGridParser
         */
        $parser = new $parser();
        return $parser->execute($input, $columns, $adapter);
    }

    public function getAdapter ($builder) {
        if (isset(config('kendo_grid_parser.adapters')[$builder])) {
           return config('kendo_grid_parser.adapters')[$builder];
        } else {
            throw new KendoGridServiceException("No query builder adapter for $builder");
        }
    }

    public function getParser(string $classname)
    {
        if (isset(config('kendo_grid_parser.parsers')[$classname])) {
            return config('kendo_grid_parser.parsers')[$classname];
        } else {
            throw new KendoGridServiceException("No kendo grid parser for $classname");
        }
    }

}