# Laravel Kendo Grid Parser

Takes a Kendo Angular Grid state and applies all its filters to a Laravel query builder instance.
Works with any of these query builders out of the box:
- `\Illuminate\Database\Query\Builder::class`
- ``\Illuminate\Database\Query\Builder::class``
- `\Tinderbox\ClickhouseBuilder\Integrations\Laravel\Builder::class` (https://github.com/the-tinderbox/ClickhouseBuilder)

Tested with `@progress/kendo-angular-grid v3.X.X` and Laravel 5.6 and up.

## Instalation 
`composer require elnelsonperez/kendo-grid-parser`

The package will be auto-discovered by Laravel 5.5+

## Usage 

##### Example grid state as JSON
```json
{
  "filter": {
    "filters": [
      {
        "field": "name",
        "operator": "contains",
        "value": "John"
      }
    ],
    "logic": "and"
  },
  "group": [],
  "skip": 0,
  "sort": [
    {
      "field": "user_id",
      "dir": "asc"
    }
  ],
  "take": 50
}
```

This would be sent from the client and into the controller below.

##### Example Controller 

```php
    public function list(Request $request, KendoGridService $service)
    {
        $valid = $this->validate($request, [
            'skip'   => 'numeric|nullable',
            'take'   => 'numeric|nullable',
            'sort'   => 'array|nullable',
            'filter' => 'array|nullable'
        ]);

        // Base query. It's convenient to wrap the inner query before applying any grid filters
        $query = DB::query()->fromSub(
            User::selectRaw('users.id user_id, users.name, addresses.address')
                ->leftJoin('addresses', 'addresses.user_id', '=','users.id'), 'T'
        );

        $result_query = $service->execute(
            // Grid State
            $valid,
            // Grid columns and types (string, number, date, or boolean)
            [
                'user_id'                           => 'number',
                'name'                              => 'string',
                'address'                           => 'string',
            ],
            // The query builder instance to apply the filters to. 
            // The service detects the query builder instance class and chooses which adapter to use to build the resulting
            // query builder based on the packages configuration
            $query
        );

        return response([
            'data'  => $result_query->get(),
            'total' => $result_query->count()
        ]);
    }
```

The returned query builder instance would have all the filters received applied to it, but you can continue using it as you please.
## Configuration

You can publish the packages configuration to customize/extend the implementations used by doing

`php artisan vendor:publish --provider="ElNelsonPerez\KendoGridParser\KendoGridParserServiceProvider"`

Further configuration options could be provided upon request.

## Motivation
I pulled this code from another project to reuse between projects as I deal with Kendo Grid and Laravel often enough, 
and figured it would be useful to someone else.
