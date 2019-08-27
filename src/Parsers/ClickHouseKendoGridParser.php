<?php

namespace ElNelsonPerez\KendroGridParser\Parsers;
use ElNelsonPerez\KendroGridParser\Base\KendoGridParser;
use Tinderbox\ClickhouseBuilder\Query\Expression;

class ClickHouseKendoGridParser extends KendoGridParser
{
    protected $stringOps = [
        'eq'             => 'LIKE',
        'neq'            => 'NOT LIKE',
        'doesnotcontain' => 'NOT LIKE',
        'contains'       => 'LIKE',
        'startswith'     => 'LIKE',
        'endswith'       => 'LIKE',
    ];

    protected function getWhereColumn ($column) {
        $type = $this->columns[$column];
        if ($type === 'string') {
            return new Expression("lower(toString($column))");
        }
        return $column;
    }

}