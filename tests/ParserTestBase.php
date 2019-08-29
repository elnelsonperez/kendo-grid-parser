<?php

namespace ElNelsonPerez\KendoGridParser\Test;

use ElNelsonPerez\KendoGridParser\Exceptions\KendoGridServiceException;
use ElNelsonPerez\KendoGridParser\KendoGridService;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;

abstract class ParserTestBase extends TestCase
{

    /**
     * @var $service KendoGridService
     */
    public $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
        $this->service = $this->app->make(KendoGridService::class);
    }

    /**
     * @return Builder | \Tinderbox\ClickhouseBuilder\Integrations\Laravel\Builder
     */
    public abstract function getBaseFilterQuery ();

    public abstract function setUpDatabase (Application $app);

    /** @test */
    public function it_sorts_desc () {
        $query = DB::table('owners');
        $res = $this->service->execute(
            Helpers::generateSortInput('desc', 'id'),
            [
                'owner_name' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->first()->owner_name === 'Jose');

    }

    /** @test */
    public function it_sorts_asc () {
        $query = DB::table('owners');
        $res = $this->service->execute(
            Helpers::generateSortInput('asc', 'id'),
            [
                'owner_name' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->first()->owner_name === 'Nelson');

    }

    /** @test */
    public function it_sorts_by_string_field () {
        $query = DB::table('owners');
        $res = $this->service->execute(
            Helpers::generateSortInput('asc', 'owner_name'),
            [
                'owner_name' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->first()->owner_name === 'Jose');
    }

    /** @test */
    public function it_filters_using_contains () {
        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'owner_name',
                    'operator' => 'contains',
                    'value' => 'ose',
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 1);
        $this->assertTrue($res->first()->owner_name === 'Jose');

        $query = $this->getBaseFilterQuery();
        /**
         * @var $res Builder
         */
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'breed',
                    'operator' => 'contains',
                    'value' => 'ull',
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 1);
        $this->assertTrue($res->first()->breed === 'Bulldog');

    }

    /** @test */
    public function it_filters_using_eq () {
        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'owner_name',
                    'operator' => 'eq',
                    'value' => 'Jose',
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 1);
        $this->assertTrue($res->first()->owner_name === 'Jose');

        $query = $this->getBaseFilterQuery();
        /**
         * @var $res Builder
         */
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'breed',
                    'operator' => 'eq',
                    'value' => 'Bulldog',
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 1);
        $this->assertTrue($res->first()->breed === 'Bulldog');

    }

    /** @test */
    public function it_filters_using_neq () {
        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'owner_name',
                    'operator' => 'neq',
                    'value' => 'Jose',
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 2);
        $this->assertTrue($res->first()->owner_name === 'Nelson');

        $query = $this->getBaseFilterQuery();
        /**
         * @var $res Builder
         */
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'breed',
                    'operator' => 'neq',
                    'value' => 'Bulldog',
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 2);
    }

    /** @test */
    public function it_filters_using_startswith () {
        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'owner_name',
                    'operator' => 'startswith',
                    'value' => 'Jo',
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 1);
        $this->assertTrue($res->first()->owner_name === 'Jose');

        $query = $this->getBaseFilterQuery();
        /**
         * @var $res Builder
         */
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'breed',
                    'operator' => 'startswith',
                    'value' => 'Bull',
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 1);
        $this->assertTrue($res->first()->breed === 'Bulldog');

    }

    /** @test */
    public function it_filters_using_endswith () {
        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'owner_name',
                    'operator' => 'endswith',
                    'value' => 'se',
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 1);
        $this->assertTrue($res->first()->owner_name === 'Jose');

        $query = $this->getBaseFilterQuery();
        /**
         * @var $res Builder
         */
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'breed',
                    'operator' => 'endswith',
                    'value' => 'xer',
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 1);
        $this->assertTrue($res->first()->breed === 'Boxer');

    }

    /** @test */
    public function it_filters_using_isnull_and_notnull () {
        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'owner_name',
                    'operator' => 'isnull',
                    'value' => null,
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 0);

        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'breed',
                    'operator' => 'isnotnull',
                    'value' => null,
                ],
            ]),
            [
                'owner_name' => 'string',
                'dog_name' => 'string',
                'breed' => 'string',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 3);

    }

    /** @test */
    public function it_limits () {
        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute([
            'filter' => null,
            'sort' => [],
            'skip' => 0,
            'take' => 2,
        ], [
            'owner_name' => 'string',
            'dog_name' => 'string',
            'breed' => 'string',
            'id' => 'number'
        ], $query);

        $this->assertTrue($res->get()->count() === 2);

        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute([
            'filter' => null,
            'sort' => [],
            'skip' => 0,
            'take' => 1,
        ], [
            'owner_name' => 'string',
            'dog_name' => 'string',
            'breed' => 'string',
            'id' => 'number'
        ], $query);

        $this->assertTrue($res->get()->count() === 1);

        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute([
            'filter' => null,
            'sort' => [],
            'skip' => 1,
            'take' => 100,
        ], [
            'owner_name' => 'string',
            'dog_name' => 'string',
            'breed' => 'string',
            'id' => 'number'
        ], $query);

        $this->assertTrue($res->get()->count() === 2);

    }

    /** @test */
    public function it_validates_column_type () {
        $query = $this->getBaseFilterQuery();

        try {
            $res = $this->service->execute(
                Helpers::generateFilterInput([
                    [
                        'field' => 'owner_name',
                        'operator' => 'contains',
                        'value' => 'ose',
                    ],
                ]),
                [
                    'owner_name' => 'test',
                    'dog_name' => 'string',
                    'breed' => 'string',
                    'id' => 'number'
                ], $query);
        } catch (KendoGridServiceException $e) {
            $this->assertTrue(true);
            return;
        }

        $this->assertTrue(false);

    }

    /** @test */
    public function it_validates_column_name () {
        $query = $this->getBaseFilterQuery();

        try {
            $res = $this->service->execute(
                Helpers::generateFilterInput([
                    [
                        'field' => 'owner_name',
                        'operator' => 'contains',
                        'value' => 'ose',
                    ],
                ]),
                [
                    'asdasd' => 'string',
                    'dog_name' => 'string',
                    'breed' => 'string',
                    'id' => 'number'
                ], $query);
        } catch (KendoGridServiceException $e) {
            $this->assertTrue(true);
            return;
        }

        $this->assertTrue(true);

    }

    /** @test */
    public function it_validates_sorting_field_not_in_columns_array()
    {
        $query = $this->getBaseFilterQuery();
        try {
            $res = $this->service->execute(
                Helpers::generateFilterInput([]),
                [
                    'owner_name' => 'string'
                ], $query);
        } catch (KendoGridServiceException $e) {
            $this->assertTrue(true);
            return;
        }

        $this->assertTrue(false);
    }

    /** @test */
    public function it_filters_using_number_gt () {
        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'owner_id',
                    'operator' => 'gt',
                    'value' => 1,
                ],
            ]),
            [
                'owner_id' => 'number',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->count() === 1);
        $this->assertTrue($res->first()->owner_name === 'Jose');


    }

    /** @test */
    public function it_filters_using_number_lte () {
        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'owner_id',
                    'operator' => 'lte',
                    'value' => 1,
                ],
            ]),
            [
                'owner_id' => 'number',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->get()->count() === 2);
        $this->assertTrue($res->first()->owner_name === 'Nelson');

    }
    /** @test */
    public function it_filters_using_number_lt () {
        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'owner_id',
                    'operator' => 'lt',
                    'value' => 2,
                ],
            ]),
            [
                'owner_id' => 'number',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->get()->count() === 2);
        $this->assertTrue($res->first()->owner_name === 'Nelson');

    }

    /** @test */
    public function it_filters_using_number_gte () {
        $query = $this->getBaseFilterQuery();
        $res = $this->service->execute(
            Helpers::generateFilterInput([
                [
                    'field' => 'owner_id',
                    'operator' => 'lte',
                    'value' => 1,
                ],
            ]),
            [
                'owner_id' => 'number',
                'id' => 'number'
            ], $query);

        $this->assertTrue($res->get()->count() === 2);
        $this->assertTrue($res->first()->owner_name === 'Nelson');

    }

    /** @test */
    public function it_fails_when_using_number_operator_on_string_field () {

        $numberOps = [
            'gt',
            'gte',
            'lt',
            'lte',
        ];

        foreach ($numberOps as $op) {
            try {
                $query = $this->getBaseFilterQuery();
                $this->service->execute(
                    Helpers::generateFilterInput([
                        [
                            'field' => 'owner_name',
                            'operator' => $op,
                            'value' => 'test',
                        ],
                    ]),
                    [
                        'owner_id' => 'number',
                        'owner_name' => 'string',
                        'id' => 'number'
                    ], $query);
            } catch (KendoGridServiceException $e) {
                if ($e->getMessage() === "Filter operator is not a valid string operator") {
                    $this->assertTrue(true);
                    continue;
                }
            }
            $this->assertTrue(false);
        }


    }

    /** @test */
    public function it_fails_when_using_string_operator_on_number_field () {

        $stringOps = [
            'doesnotcontain',
            'contains'      ,
            'startswith'    ,
            'endswith'      ,
        ];

        foreach ($stringOps as $op) {
            try {
                $query = $this->getBaseFilterQuery();
                $this->service->execute(
                    Helpers::generateFilterInput([
                        [
                            'field' => 'owner_id',
                            'operator' => $op,
                            'value' => 1,
                        ],
                    ]),
                    [
                        'owner_id' => 'number',
                        'id' => 'number'
                    ], $query);

            } catch (KendoGridServiceException $e) {
                if ($e->getMessage() === "Filter operator is not a valid number operator") {
                    $this->assertTrue(true);
                    continue;
                }
            }
            $this->assertTrue(false);
        }


    }

}