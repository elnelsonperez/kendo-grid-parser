<?php


namespace ElNelsonPerez\KendoGridParser\Test;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;

class NativeParserTest extends ParserTestBase
{

    public function setUpDatabase (Application $app) {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['db']->connection()->getSchemaBuilder()->create('owners', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('owner_name');
        });

        $app['db']->connection()->getSchemaBuilder()->create('dogs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('owner_id');
            $table->string('dog_name');
            $table->string('breed');
        });

        DB::table('owners')->insert([
            ['owner_name' => "Nelson", "id" => 1],
            ['owner_name' => "Jose", "id" => 2],
        ]);

        DB::table('dogs')->insert([
            ['dog_name' => "Blackie", "breed" => 'Bulldog', 'owner_id' => 1],
            ['dog_name' => "Firulais", "breed" => 'Poodle', 'owner_id' => 1],
            ['dog_name' => "Perrito", "breed" => 'Boxer', 'owner_id' => 2],
        ]);

    }

    public function getBaseFilterQuery()
    {
        return DB::query()->fromSub(
            DB::table('owners as o')
                ->selectRaw('o.id owner_id, owner_name, dog_name, breed')
                ->join('dogs as d', 'd.owner_id', '=', 'o.id')
            , 'T');
    }

}