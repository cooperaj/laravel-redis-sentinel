<?php

namespace RedisSentinel\Laravel;

use Illuminate\Support\ServiceProvider;

class RedisSentinel extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app['queue']->addConnector('sentinel-redis', function () use ($app) {
            return new SentinelConnector($app['redis']);
        });
    }
}
