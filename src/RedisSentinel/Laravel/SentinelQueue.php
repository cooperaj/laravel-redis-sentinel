<?php

namespace RedisSentinel\Laravel;

use Illuminate\Queue\RedisQueue;

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
        return $this->redis->connection($this->connection)->getClientFor('master');
    }
}
