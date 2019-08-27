<?php

namespace ElNelsonPerez\KendoGridParser;
use Illuminate\Support\Facades\DB;

class EloquentKendoGridService extends KendoGridService
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