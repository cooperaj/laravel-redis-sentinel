<?php

namespace RedisSentinel\Laravel;

use Illuminate\Queue\RedisQueue;
use Illuminate\Redis\Connections\PredisConnection;
use Predis\Client;

class SentinelQueue extends RedisQueue
{
    /**
     * Get the connection for the queue.
     *
     * Since we're using sentinels and Predis does not support transactions over aggregate queries then
     * make sure return only the 'master' client.
     *
     * @return \Illuminate\Redis\Connections\Connection
     */
    protected function getConnection()
    {
        $connection = $this->redis->connection($this->connection);

        /** @var \Predis\ClientInterface $client */
        $client = $connection->client();

        if ($client instanceof Client) {
            // getClientFor is not in the client interface.
            return new PredisConnection($client->getClientFor('master'));
        }

        return $connection;
    }
}
