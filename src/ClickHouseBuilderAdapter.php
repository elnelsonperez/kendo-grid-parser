<?php

namespace ElNelsonPerez\KendoGridParser;
use Tinderbox\ClickhouseBuilder\Integrations\Laravel\Builder;
use Tinderbox\ClickhouseBuilder\Query\Expression;

class ClickHouseBuilderAdapter extends Builder implements IKendoQueryBuilderAdapter
{
    public function adaptedOrderBy($column, $direction = 'asc')
    {
        return $this->orderBy($column, $direction);
    }

    public function adaptedWhereNull($column, $boolean = 'and', $not = false)
    {
        return $this->whereRaw("$column ".(new Expression($not ? 'IS NOT null' : 'IS null')));
    }

    public function adaptedWhere($column, $operator = null, $value = null, $boolean = 'and')
    {
        return $this->where($column, $operator, $value, strtoupper($boolean));
    }

    public function adaptedLimit(int $limit, int $offset = null)
    {
        return $this->limit($limit, $offset);
    }

    public function adaptedCount($columns = '*')
    {
        return $this->count($columns);
    }
}