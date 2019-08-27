<?php

namespace ElNelsonPerez\KendoGridParser;
use Tinderbox\ClickhouseBuilder\Query\Expression;

class ClickHouseBuilderAdapter implements IKendoQueryBuilderAdapter
{

    /**
     * @var Tinderbox\ClickhouseBuilder\Integrations\Laravel\Builder
     */
    private $builder;

    public function __construct(Tinderbox\ClickhouseBuilder\Integrations\Laravel\Builder $builder)
    {
        $this->builder = $builder;
    }

    public function adaptedOrderBy($column, $direction = 'asc')
    {
        return $this->builder->orderBy($column, $direction);
    }

    public function adaptedWhereNull($column, $boolean = 'and', $not = false)
    {
        return $this->builder->whereRaw("$column ".(new Expression($not ? 'IS NOT null' : 'IS null')));
    }

    public function adaptedWhere($column, $operator = null, $value = null, $boolean = 'and')
    {
        return $this->builder->where($column, $operator, $value, strtoupper($boolean));
    }

    public function adaptedLimit(int $limit, int $offset = null)
    {
        return $this->builder->limit($limit, $offset);
    }

    public function adaptedCount($columns = '*')
    {
        return $this->builder->count($columns);
    }
}