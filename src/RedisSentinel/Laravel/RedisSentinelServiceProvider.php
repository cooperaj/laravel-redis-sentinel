<?php

namespace RedisSentinel\Laravel;

use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;

class RedisSentinelServiceProvider extends ServiceProvider
{
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
    public function register() {}

    protected function registerRedisSentinelConnector(QueueManager $manager)
    {
        $manager->addConnector('sentinel-redis', function () {
            return new SentinelConnector($this->app['redis']);
        });
    }
}
