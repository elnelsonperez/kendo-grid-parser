<?php


namespace ElNelsonPerez\KendoGridParser\Test;


class Helpers
{

    const OPERATORS = [
        'contains',
        'startswith',
        'endswith',
        'eq',
        'neq',
        'isnull',
        'isnotnull'
    ];

    static function  generateSortInput ($dir, $field) {
        return [
            'filter' => null,
            'sort' => [
                [
                    'dir' => $dir,
                    'field' => $field,
                ],
            ],
            'skip' => 0,
            'take' => 20,
        ];
    }


    static function  generateFilterInput ($filters, $logic = 'and') {
        return [
            'filter' =>
                [
                    'filters' => $filters,
                    'logic' => $logic,
                ],
            'sort' => [
                [
                    'dir' => 'asc',
                    'field' => 'id',
                ],
            ],
            'skip' => 0,
            'take' => 20,
        ];
    }


}