<?php

namespace Imovs\Repository;

use Illuminate\Support\ServiceProvider;
use Imovs\Repository\Cacheable\Model\Observer;
use Imovs\Repository\Cacheable\CacheableService;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Imovs\Repository\Cacheable\Contracts\CacheableService as CacheableServiceContract;

/**
 * Class RepositoryServiceProvider
 *
 * @package Imovs\Repository
 * @author Jefferson Agostinho <jefferson.andrade.agostinho@gmail.com>
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/repository.php' => config_path('repository.php')
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../config/repository.php', 'repository');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CacheableServiceContract::class, function () {
            return new CacheableService(app(CacheRepository::class));
        });
    }
}
