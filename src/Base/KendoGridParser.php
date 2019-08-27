<?php

namespace ElNelsonPerez\KendroGridParser\Base;

use ElNelsonPerez\KendroGridParser\Exceptions\KendoGridServiceException as Exception;
use ElNelsonPerez\KendroGridParser\Base\Contracts\IKendoQueryBuilderAdapter;

abstract class KendoGridParser
{
    protected $input;
    protected $columns;
    protected $sortKey  = 'sort';
    protected $filterKey = 'filter';

    protected $stringOps = [
        'eq'             => 'like',
        'neq'            => 'not like',
        'doesnotcontain' => 'not like',
        'contains'       => 'like',
        'startswith'     => 'like',
        'endswith'       => 'like',
    ];

    protected $numberOps = [
        'eq'  => '=',
        'gt'  => '>',
        'gte' => '>=',
        'lt'  => '<',
        'lte' => '<=',
        'neq' => '!=',
    ];

    /**
     * @param IKendoQueryBuilderAdapter $query
     * @param $sort_input
     * @throws Exception
     */
    private function sort(IKendoQueryBuilderAdapter &$query, $sort_input)
    {
        if (!is_array($sort_input))
            throw new Exception('Invalid sort input');

        foreach ($sort_input as $f) {
            if (!is_array($f))
                throw new Exception('Invalid sort field');
            if (!isset($this->columns[$f['field']]))
                throw new Exception('Sorting field not present in columns array');
            if (isset($f['dir']) && !in_array($f['dir'], ['asc', 'desc'], true))
                throw new Exception('Invalid sort direction');
            $query->adaptedOrderBy($f['field'],isset($f['dir']) ? $f['dir'] : 'asc');
        }

    }

    /**
     * @param IKendoQueryBuilderAdapter $query
     * @param $d
     */
    private function filter(IKendoQueryBuilderAdapter &$query, $d)
    {
        $filter_recursive = function (IKendoQueryBuilderAdapter &$query, $d, $depth, $logic) use (&$filter_recursive) {
            if ($depth >= 32)
                throw new Exception('Recursive depth > 32');
            if (!is_array($d))
                throw new Exception('Invalid filter input');

            if (isset($d['filters']) && empty($d['filters'])) {
                return;
            }

            if (isset($d['filters']) && is_array($d['filters'])) {

                if (!isset($d['logic']) || !in_array($d['logic'], ['and', 'or'], true))
                    throw new Exception('Invalid filter logic');

                $query->adaptedWhere(function ($q) use ($d, $depth, $filter_recursive, &$query) {
                    $adapted = $query::createFromBuilder($q);
                    foreach ($d['filters'] as $f) {
                        $filter_recursive($adapted, $f, $depth + 1, $d['logic']);
                    }
                }, null, null, $logic);

            } else {
                $this->filterField($query, $d, $logic);
            }
        };

        $filter_recursive($query, $d, 0, 'and');
    }

    /**
     * @param $column
     * @return mixed
     */
    protected function getWhereColumn ($column) {
        return $column;
    }

    /**
     * @param IKendoQueryBuilderAdapter $query
     * @param $filter
     * @param $logic
     * @throws Exception
     */
    private function filterField(IKendoQueryBuilderAdapter &$query, $filter, $logic)
    {
        if (!isset($filter['field']) || !isset($this->columns[$filter['field']]))
            throw new Exception('Invalid filter field or field is not in columns array');

        if ($filter['operator'] === 'isnull') {
            $query->adaptedWhereNull($filter['field'], $logic);
            return;
        }

        if ($filter['operator'] === 'isnotnull') {
            $query->adaptedWhereNull($filter['field'], $logic, true);
            return;
        }

        if ($this->columns[$filter['field']] === 'string') {
            if (!isset($filter['operator']) or !isset($this->stringOps[$filter['operator']]))
                throw new Exception('Filter operator is not a valid string operator');
            if (!isset($filter['value']) or !is_string($filter['value']))
                throw new Exception('Invalid string filter value');

            $value = $filter['value'];
            if ($filter['operator'] === 'contains' || $filter['operator'] === 'doesnotcontain')
                $value = "%$value%";
            else if ($filter['operator'] === 'startswith')
                $value = "$value%";
            else if ($filter['operator'] === 'endswith')
                $value = "%$value";
            $query->adaptedWhere($this->getWhereColumn($filter['field']), $this->stringOps[$filter['operator']], strtolower($value), $logic);
        } else if ($this->columns[$filter['field']] === 'number') {
            if (!isset($filter['operator']) || !isset($this->numberOps[$filter['operator']])) {
                throw new Exception('Filter operator is not a valid number operator');
            }
            if (!isset($filter['value']) || !is_numeric($filter['value']))
                throw new Exception('Invalid number filter value');
            $query->adaptedWhere($this->getWhereColumn($filter['field']), $this->numberOps[$filter['operator']], $filter['value'], $logic);
        } else if ($this->columns[$filter['field']] === 'boolean') {
            if (!isset($filter['operator']))
                throw new Exception('Missing boolean operator');
            if (!isset($filter['value']))
                throw new Exception('Missing boolean value');
            $query->adaptedWhere($this->getWhereColumn($filter['field']), $filter['value'] === true ? '!=' : '=', 0, $logic);
        } else if ($this->columns[$filter['field']] === 'date') {
            if (!isset($filter['operator']) || !isset($this->numberOps[$filter['operator']]))
                throw new Exception('Filter operator is not a valid number operator');
            try {
                $value = new \DateTime($filter['value']);
            } catch (\Exception $e) {
                throw new Exception('Invalid filter date value');
            }
            $query->adaptedWhere($filter['field'], $this->numberOps[$filter['operator']], $value->format('Y-m-d H:i:s'), $logic);
        } else {
            throw new Exception('Unexpected column type');
        }
    }



    /**
     * @param array $input
     * @param array $columns
     * @param IKendoQueryBuilderAdapter $query
     * @param bool $disable_limit
     * @return mixed
     * @throws Exception
     */
    public function execute(array $input, array $columns, IKendoQueryBuilderAdapter &$query, $disable_limit = false)
    {
        $this->input = $input;
        $this->columns = $columns;

        if (isset($this->input[$this->sortKey]) && is_array($this->input[$this->sortKey]))
            $this->sort($query, $this->input[$this->sortKey]);
        if (isset($this->input[$this->filterKey]) && is_array($this->input[$this->filterKey]))
            $this->filter($query, $this->input[$this->filterKey]);

        if (!$disable_limit) {
            if (isset($this->input['take']) && !isset($this->input['skip'])) {
                $query->adaptedLimit($this->input['take']);
            }
            if (isset($this->input['skip']) && isset($this->input['take'])) {
                $query->adaptedLimit($this->input['take'], $this->input['skip']);
            }
        }

        return $query->getQueryBuilder();
    }

}