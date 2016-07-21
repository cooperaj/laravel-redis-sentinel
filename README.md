Laravel Redis Sentinel
============

This provides a Sentinel aware driver for Laravel queues. A Redis cluster with Sentinels supports a high availability Master/Slave architecture that provides automatic failover should a node stop working. 

Add the Service provider to your `config/app.php`:

```
'providers' => [

    ...
    
    RedisSentinel\Laravel\RedisSentinelServiceProvider::class,
]
```

Add a connection to your `config/queue.php` file:

```
'connections' => [

    ...
    
    'sentinel' => [
        'driver' => 'sentinel-redis',
        'connection' => 'default',
        'queue' => 'default',
        'expire' => 90,
    ],
],
```

Configure your env file to use the new driver:

```
QUEUE_DRIVER=sentinel
```

Of course, point your Redis database at a set of Redis Sentinels. Change the `redis` part of your `config/database.php` to:

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
        ]
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
```
