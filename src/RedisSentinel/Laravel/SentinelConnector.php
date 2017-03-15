<?php

namespace RedisSentinel\Laravel;

use Illuminate\Queue\Connectors\RedisConnector;
use Illuminate\Support\Arr;

class SentinelConnector extends RedisConnector
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        $queue = new SentinelQueue(
            $this->redis, $config['queue'], Arr::get($config, 'connection', $this->connection)
        );

        return $queue;
    }
}
