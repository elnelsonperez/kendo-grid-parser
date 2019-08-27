<?php
namespace ElNelsonPerez\KendoGridParser\Base;

 use ElNelsonPerez\KendoGridParser\Base\Contracts\IKendoQueryBuilderAdapter;

 abstract class KendoQueryBuilderAdapter implements IKendoQueryBuilderAdapter
{
     /**
      * @var \Illuminate\Database\Query\Builder | \Illuminate\Database\Eloquent\Builder
      */
     protected $builder;

     public function __construct($builder = null)
     {
         $this->builder = $builder;
     }

     public function getQueryBuilder()
     {
         return $this->builder;
     }

     public function adaptedOrderBy($column, $direction = 'asc')
     {
         return $this->builder->orderBy($column, $direction);
     }

     public static function createFromBuilder($builder)
     {
         return new static($builder);
     }

 }