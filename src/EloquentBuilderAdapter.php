<?php

namespace ElNelsonPerez\KendoGridParser;

class EloquentBuilderAdapter implements IKendoQueryBuilderAdapter
{
    /**
     * @var Illuminate\Database\Query\Builder
     */
    private $builder;

    public function __construct(Illuminate\Database\Query\Builder $builder)
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