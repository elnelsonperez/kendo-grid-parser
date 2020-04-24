<?php

namespace ElNelsonPerez\KendoGridParser\Adapters;

use ElNelsonPerez\KendoGridParser\Base\KendoQueryBuilderAdapter;
use Illuminate\Support\Facades\DB;

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

    public function adaptedWhereDate($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->builder->whereDate($column, $operator, $value, $boolean);
    }
}