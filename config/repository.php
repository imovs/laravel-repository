
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
     | Cache clean: boolean
     | Cache time: integer
     | Cache repository: Instance of Illuminate\Contracts\Cache\Repository
     */

    'cache' => [

        /*
         |--------------------------------------------------------------------------
         | Cache enabled
         |--------------------------------------------------------------------------
         |
         | Enable or disable cache
         | Default: false
         */

        'enabled' => false,

        /*
         |--------------------------------------------------------------------------
         | Cache clean
         |--------------------------------------------------------------------------
         |
         | Enable or disable cache clean
         | Default: true
         */

        'clean' => true,

        /*
         |--------------------------------------------------------------------------
         | Cache time
         |--------------------------------------------------------------------------
         |
         | Time of expiration cache in minutes
         | Default: 30 minutes
         */

        'time' => 30,

        /*
         |--------------------------------------------------------------------------
         | Cache repository
         |--------------------------------------------------------------------------
         |
         | Instance of Illuminate\Contracts\Cache\Repository
         | Default: Illuminate\Support\Facades\Cache::class
         */

        'repository' => Illuminate\Support\Facades\Cache::class,
    ]
];
