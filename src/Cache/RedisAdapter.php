<?php

declare(strict_types=1);

namespace CliFyi\Cache;

use Predis\Client;
use Psr\SimpleCache\CacheInterface;

class RedisAdapter implements CacheInterface
{
    /** @var Client */
    private $redis;

    /**
     * @param Client $redis
     */
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if ($value = $this->redis->get($key)) {
            return unserialize($value);
        }

        return $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     *
     * @return int
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->redis->setex($key, $ttl, serialize($value));
    }

    /**
     * @param string $key
     *
     * @return bool|int
     */
    public function delete($key)
    {
        return $this->redis->del($key);
    }

    public function clear()
    {
    }

    public function getMultiple($keys, $default = null)
    {
    }

    public function setMultiple($values, $ttl = null)
    {
    }

    public function deleteMultiple($keys)
    {
    }

    public function has($key)
    {
    }
}
