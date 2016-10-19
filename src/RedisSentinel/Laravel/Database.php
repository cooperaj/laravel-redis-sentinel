<?php

namespace RedisSentinel\Laravel;

use Illuminate\Support\Arr;
use Predis\Client;
use Illuminate\Redis\Database as LaravelRedisDatabase;

class Database extends LaravelRedisDatabase
{
    /**
     * Create an array of single connection or sentinel clients
     *
     * @param  array  $servers
     * @param  array  $options
     * @return array
     */
    protected function createSingleClients(array $servers, array $options = [])
    {
        $clients = [];

        foreach ($servers as $key => $server) {
            $options = array_merge($options, Arr::pull($server, 'options'));

            $clients[$key] = new Client($server, $options);

            if (isset($options['update_sentinels']) && boolval($options['update_sentinels']) === true) {
                ($clients[$key])->getConnection()->setUpdateSentinels(true);
            }
        }

        return $clients;
    }
}
