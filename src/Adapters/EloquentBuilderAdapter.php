<?php

namespace ElNelsonPerez\KendroGridParser\Adapters;

use ElNelsonPerez\KendroGridParser\Base\KendoQueryBuilderAdapter;

class EloquentBuilderAdapter extends KendoQueryBuilderAdapter
{

    public function adaptedWhere($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->builder->where($column, $operator, $value, $boolean);
    }

    public function adaptedLimit(int $limit, int $offset = null)
    {
        $this->builder->take($limit);
        $this->builder->offset($offset);
    }

    public function adaptedWhereNull($column, $boolean = 'and', $not = false)
    {
        $this->builder->whereNull($column, $boolean, $not);
    }


}