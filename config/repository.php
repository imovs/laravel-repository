
<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Imovs Repository
     |--------------------------------------------------------------------------
     |
     | A Laravel repository pattern
     |
     | Configure the repository package
     */

    'pagination' => [

        /*
         |--------------------------------------------------------------------------
         | Pagination limit
         |--------------------------------------------------------------------------
         |
         | Set a default items per page
         | Default: 15 items per page
         */

        'limit' => 15
    ],

    /*
     |--------------------------------------------------------------------------
     | Cache config
     |--------------------------------------------------------------------------
     |
     | Configure cache
     |
     | Cache enabled: boolean
     | Cache repository: Instance of Illuminate\Contracts\Cache\Repository
     */

    'cacheable' => [

        /*
         |--------------------------------------------------------------------------
         | Cache enabled
         |--------------------------------------------------------------------------
         |
         | Define whether caching is enabled
         | Default: false
         */

        'enabled' => true,

        /*
         |--------------------------------------------------------------------------
         | Cache duration
         |--------------------------------------------------------------------------
         |
         | Define the default cache duration in seconds
         | Default: 1800 || 30 minutes
         */

        'duration' => 1800,
    ],

    /*
     |--------------------------------------------------------------------------
     | Search config
     |--------------------------------------------------------------------------
     |
     | Configure searchable
     |
     | Searchable params: array
     */

    'searchable' => [

      /*
       |--------------------------------------------------------------------------
       | Searchable params
       |--------------------------------------------------------------------------
       |
       | Sets accepted search parameters
       */

        'params' => [
            'search'  => 'search',
            'order'   => 'order',
            'sort'    => 'sort',
            'with'    => 'with',
        ]
    ],

];
