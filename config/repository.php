
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
         | Cache repository
         |--------------------------------------------------------------------------
         |
         | Instance of Illuminate\Contracts\Cache\Repository
         | Default: Illuminate\Contracts\Cache\Repository::class
         */

         'repository' => Illuminate\Contracts\Cache\Repository::class,
    ]
];
