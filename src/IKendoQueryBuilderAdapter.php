<?php
namespace ElNelsonPerez\KendoGridParser;

interface IKendoQueryBuilderAdapter
{
    public function adaptedOrderBy($column, $direction = 'asc');
    public function adaptedWhereNull($column, $boolean = 'and', $not = false);
    public function adaptedWhere($column, $operator = null, $value = null, $boolean = 'and');
    public function adaptedLimit(int $limit, int $offset = null);
    public function adaptedCount($columns = '*');
}