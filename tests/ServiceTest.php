<?php

namespace ElNelsonPerez\KendoGridParser\Test;

use ElNelsonPerez\KendoGridParser\Exceptions\KendoGridServiceException;
use ElNelsonPerez\KendoGridParser\KendoGridService;

class ServiceTest extends TestCase
{
    /** @test */
    public function it_resolves_adapters_and_parsers_for_supported_builders()
    {
        $supported = [
            \Illuminate\Database\Query\Builder::class,
            \Illuminate\Database\Eloquent\Builder::class,
            \Tinderbox\ClickhouseBuilder\Integrations\Laravel\Builder::class
        ];

        /**
         * @var $service KendoGridService
         */
        $service = $this->app->make(KendoGridService::class);

        foreach ($supported as $s) {
            try {
                $service->getAdapter($s);
            } catch (KendoGridServiceException $e) {
                $this->assertTrue(false);
            }

            try {
                $service->getParser($s);
            } catch (KendoGridServiceException $e) {
                $this->assertTrue(false);
            }
        }

        $this->assertTrue(true);
    }
}