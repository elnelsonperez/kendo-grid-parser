# Laravel Kendo Grid Parser

Takes a Kendo Angular Grid state and applies all its filters to a Laravel query builder instance.

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
            // The query builder instance to apply the filters to
            $query
        );

        return response([
            'data'  => $result_query->get(),
            'total' => $result_query->count()
        ]);
    }
```


