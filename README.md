Laravel Redis Sentinel
============

[![Build Status](https://scrutinizer-ci.com/g/cooperaj/laravel-redis-sentinel/badges/build.png?b=master)](https://scrutinizer-ci.com/g/cooperaj/laravel-redis-sentinel/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cooperaj/laravel-redis-sentinel/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cooperaj/laravel-redis-sentinel/?branch=master)

This provides a Sentinel aware driver for Laravel. A Redis cluster with Sentinels supports a high availability Master/Slave architecture that provides automatic failover should a node stop working.

It's simple code and merely allows you to configure Sentinels correctly by changing some assumptions Laravel makes about how you're using Redis.

###Compatibility

Version | Supported Laravel Version
------- | -------------------------
^0.0 | ^5.2
^1.0 | ^5.3

###Installation

Add the Service provider to your `config/app.php`, you should also comment out (or remove) the default `illuminate` Redis driver:

```
'providers' => [

    // Illuminate\Redis\RedisServiceProvider::class,

    ...

    RedisSentinel\Laravel\RedisSentinelServiceProvider::class,
]
```

Point your Redis database at a set of Redis Sentinels. Change the `redis` part of your `config/database.php` to something like:

```
'redis' => [

    'cluster' => false,

    'default' => [
        [
            'host' => env('REDIS_SENTINEL_1'),
            'port' => 26379
        ],
        [
            'host' => env('REDIS_SENTINEL_2'),
            'port' => 26379
        ],
        [
            'host' => env('REDIS_SENTINEL_3'),
            'port' => 26379
        ],
        'options' => [
            'replication' => 'sentinel',
            'service' => 'mymaster',
            'parameters' => [
                'database' => 0,
                'password' => env('REDIS_PASSWORD', null)
            ]
        ]
    ],

    // optional configuration for a separate Redis 'database' for just a cache
    'cache' => [
        [
            'host' => env('REDIS_SENTINEL_1'),
            'port' => 26379
        ],
        [
            'host' => env('REDIS_SENTINEL_2'),
            'port' => 26379
        ],
        [
            'host' => env('REDIS_SENTINEL_3'),
            'port' => 26379
        ],
        'options' => [
            'replication' => 'sentinel',
            'service' => 'mymaster',
            'parameters' => [
                'database' => 1, // note the differing 'database' number
                'password' => env('REDIS_PASSWORD', null)
            ]
        ]
    ],

    'options' => [
    ]

],
```

Optionally you can add a configuration option that causes Predis to interrogate a given Sentinel for a complete list of Sentinels. If you do this then you only need to provide a single Sentinel in the configuration. Predis will ensure that the Sentinel list is kept up to date on subsequent queries.

```
'default' => [
    [
        'host' => env('REDIS_SENTINEL'),
        'port' => 26379
    ],
    'options' => [
        'replication' => 'sentinel',
        'service' => 'mymaster',
        'update_sentinels' => true,
        'parameters' => [
            'database' => 0,
            'password' => env('REDIS_PASSWORD', null)
        ]
    ]
],
```

###Queue

Add a connection to your `config/queue.php` file:

```
'connections' => [

    ...

    'sentinel' => [
        'driver' => 'sentinel-redis',
        'connection' => 'default', // or any other named 'database' you define in database.php
        'queue' => 'default',
        'expire' => 90,
    ],
],
```

Configure your env file to use the new driver:

```
QUEUE_DRIVER=sentinel
```

### Cache

Laravel will quite happily use Redis as a cache location. What they don't tell you is that clearing your cache does a simplistic `FLUSHDB` command. Something you don't want to use if you're also using queues in Redis. *"Oh no, all my queued jobs have disappeared"*.

To fix this setup a cache database configuration as shown in the example `config/database.php` snippet above, ensuring that you use a different database number and change the Redis section of `config/cache.php` to read:

```
'redis' => [
    'driver' => 'redis',
    'connection' => 'cache', \\ make sure this matches the name you gave your 'database'
],
```
