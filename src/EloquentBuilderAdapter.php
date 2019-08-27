<?php

namespace ElNelsonPerez\KendoGridParser;

use Illuminate\Database\Query\Builder;

class EloquentBuilderAdapter implements IKendoQueryBuilderAdapter
{

    private $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function adaptedOrderBy($column, $direction = 'asc')
    {
        return $this->builder->orderBy($column, $direction);
    }

    public function adaptedWhereNull($column, $boolean = 'and', $not = false)
    {
        return $this->builder->whereNull($column, $boolean, $not);
    }

    public function adaptedWhere($column, $operator = null, $value = null, $boolean = 'and')
    {
        return $this->builder->where($column, $operator, $value, $boolean);
    }

    public function adaptedLimit(int $limit, int $offset = null)
    {
        $this->builder->take($limit);
        $this->builder->offset($offset);
        return $this;
    }

    public function adaptedCount($columns = '*')
    {
        return $this->builder->count($columns);
    }
}