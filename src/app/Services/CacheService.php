<?php

namespace App\Services;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class CacheService
{
    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        try {
            return $this->cache->get($key);
        } catch (InvalidArgumentException $e) {
            //..
        }

        return null;
    }

    /**
     * @param string $key
     * @param $data
     * @param int $expiresAt
     *
     * @return bool|null
     */
    public function set(string $key, $data, int $expiresAt = 43200)
    {
        try {
            return $this->cache->set($key, $data, $expiresAt);
        } catch (InvalidArgumentException $e) {
            //..
        }

        return null;
    }

    /**
     * @param string $key
     *
     * @return bool|null
     */
    public function delete(string $key)
    {
        try {
            return $this->cache->delete($key);
        } catch (InvalidArgumentException $e) {
        }

        return null;
    }
}
