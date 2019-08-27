<?php

namespace ElNelsonPerez\KendoGridParser;
use Illuminate\Database\Query\Builder;

class EloquentBuilderAdapter extends Builder implements IKendoQueryBuilderAdapter
{

    public function adaptedOrderBy($column, $direction = 'asc')
    {
        return $this->orderBy($column, $direction);
    }

    public function adaptedWhereNull($column, $boolean = 'and', $not = false)
    {
        return $this->whereNull($column, $boolean, $not);
    }

    public function adaptedWhere($column, $operator = null, $value = null, $boolean = 'and')
    {
        return $this->where($column, $operator, $value, $boolean);
    }

    public function adaptedLimit(int $limit, int $offset = null)
    {
        $this->take($limit);
        $this->offset($offset);
        return $this;
    }

    public function adaptedCount($columns = '*')
    {
        return $this->count($columns);
    }
}