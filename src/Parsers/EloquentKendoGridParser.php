<?php

namespace ElNelsonPerez\KendoGridParser\Parsers;
use ElNelsonPerez\KendoGridParser\Base\KendoGridParser;
use Illuminate\Support\Facades\DB;

class EloquentKendoGridParser extends KendoGridParser
{
    protected $stringOps = [
        'eq'             => 'like',
        'neq'            => 'not like',
        'doesnotcontain' => 'not like',
        'contains'       => 'like',
        'startswith'     => 'like',
        'endswith'       => 'like',
    ];

    protected function getWhereColumn ($column) {
        $type = $this->columns[$column];
        if ($type === 'string') {
            return DB::raw("LOWER($column)");
        }
        return $column;
    }

}