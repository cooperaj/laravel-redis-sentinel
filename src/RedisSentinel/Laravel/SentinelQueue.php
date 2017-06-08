<?php

namespace RedisSentinel\Laravel;

use Illuminate\Queue\RedisQueue;
use Predis\Client;

class SentinelQueue extends RedisQueue
{
    /**
     * Get the connection for the queue.
     *
     * Since we're using sentinels and Predis does not support transactions over aggregate queries then
     * make sure return only the 'master' client.
     *
     * @return \Predis\ClientInterface
     */
    protected function getConnection()
    {
        $connection = $this->redis->connection($this->connection);

        if ($connection instanceof Client) {
            // getClientFor is not in the client interface. 
            return $connection->getClientFor('master');
        }

        return $connection;
    }
}
