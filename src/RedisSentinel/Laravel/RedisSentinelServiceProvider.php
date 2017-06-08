<?php

namespace RedisSentinel\Laravel;

use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;

class RedisSentinelServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Add the connector to the queue drivers
     */
    public function boot()
    {
        $this->registerRedisSentinelConnector($this->app['queue']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('redis', function($app) {
            return new Database($app['config']['database.redis']);
        });
    }

    protected function registerRedisSentinelConnector(QueueManager $manager)
    {
        $manager->addConnector('sentinel-redis', function() {
            return new SentinelConnector($this->app['redis']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['redis'];
    }
}
