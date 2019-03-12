# Laravel Repository

> Repository pattern to data access abstraction.

## Requirements

This package require you to use cache storage which supports tags like memcached or redis. You will get errors if you use this package while using any cache storage which does not support tags.

## Setup

### Composer

Install the package via Composer:

```sh
$ composer require imovs/laravel-repository
```

> If you are using Laravel version 5.5+ then you can skip registering the service provider and package alias in your application.

### Register The Service Provider

Add the package service provider in your ``config/app.php``

```php
'providers' => [
    // ...
    \Imovs\Repository\RepositoryServiceProvider::class,
];
```

### Register The Package Alias

Add the package alias in your ``config/app.php``

```php
'aliases' => [
    // ...
    'Cacheable' => \Imovs\Repository\Cacheable\Facade::class,
];
```

### Configuration

Publish configuration file using ``php artisan`` command

```sh
php artisan vendor:publish --provider "Imovs\Repository\RepositoryServiceProvider"
```

The command above would copy a new configuration file to ``/config/repository.php``

```php
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
    ]
];
```
