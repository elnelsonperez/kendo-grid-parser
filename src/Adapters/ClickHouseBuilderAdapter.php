<?php

namespace ElNelsonPerez\KendoGridParser\Adapters;
use ElNelsonPerez\KendoGridParser\Base\KendoQueryBuilderAdapter;
use Tinderbox\ClickhouseBuilder\Integrations\Laravel\Builder;
use Tinderbox\ClickhouseBuilder\Query\Expression;

class ClickHouseBuilderAdapter extends KendoQueryBuilderAdapter
{

    /**
     * @var Builder
     */
    protected $builder;

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

    public function adaptedWhereDate($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->builder->where(raw("toDate($column)"), $operator, $value, $boolean);
    }

}