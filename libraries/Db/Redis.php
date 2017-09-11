<?php

namespace Nahid\FaceBot\Db;

use Predis\Client;

class Redis
{
    protected $client;

    public function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        $this->client = new Client([
            'scheme' => _env("REDIS_SCHEME", 'tcp'),
            'host'   => _env("REDIS_HOST", '127.0.0.1'),
            'port'   => _env("REDIS_PORT", 6379),
        ]);

    }


    public function set($key, $value)
    {
        return $this->client->set($key, $value);
    }

    public function get($key)
    {
        return $this->client->get($key);
    }

    public function exists($key)
    {
        return $this->client->exists($key);
    }

    public function delete($key)
    {
        return $this->client->del($key);
    }
}