<?php

namespace App\Common;

use Illuminate\Support\Facades\Redis;

class RedisHelper
{
    public function get($key)
    {
        return Redis::get($key);
    }

    public function set($key, $value, $expiration = 3600)
    {
        return Redis::set($key, $value, 'EX', $expiration);
    }

    public function delete($key)
    {
        return Redis::del($key);
    }

    public function lock($key, $ttl = 10)
    {
        return Redis::set($key, 1, 'NX', 'EX', $ttl);
    }

    public function unlock($key)
    {
        return Redis::del($key);
    }

    public function retry($callback, $retries = 3, $sleep = 100)
    {
        $attempts = 0;

        while ($attempts < $retries) {
            try {
                return $callback();
            } catch (\Exception $e) {
                $attempts++;
                if ($attempts >= $retries) {
                    throw $e;
                }
                usleep($sleep * 1000);
            }
        }
    }

    public function setWithRetries($key, $value, $expiration = 3600, $retries = 3, $sleep = 100)
    {
        return $this->retry(function () use ($key, $value, $expiration) {
            return $this->set($key, $value, $expiration);
        }, $retries, $sleep);
    }

    public function getWithRetries($key, $retries = 3, $sleep = 100)
    {
        return $this->retry(function () use ($key) {
            return $this->get($key);
        }, $retries, $sleep);
    }

    public function countKeys()
    {
        return Redis::dbsize();
    }

    // Method to get the names of all keys in Redis
    public function getKeyNames($pattern = '*')
    {
        return Redis::keys($pattern);
    }

    public function exists($key)
    {
        return Redis::exists($key);
    }
}
