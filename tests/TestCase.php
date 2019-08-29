<?php


namespace ElNelsonPerez\KendoGridParser\Test;

use ElNelsonPerez\KendoGridParser\KendoGridParserServiceProvider;


class TestCase extends \Orchestra\Testbench\TestCase
{


    protected function getPackageProviders($app)
    {
        return  [KendoGridParserServiceProvider::class];
    }

}