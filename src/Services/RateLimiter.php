<?php

namespace Api\Services;

use Redis;
use Exception;

class RateLimiter
{

    protected $pdo;
    protected $redis;

    public function __construct($ip, $prefix = "rate")
    {
        $this->prefix = $prefix . $ip;
    }

    public function limitRequestsInMinutes($allowedRequests, $minutes)
    {
        /*$requests = 0;
        foreach ($this->getKeys($minutes) as $key) {
            $requestsInCurrentMinute = $this->redis->get($key);
            if (false !== $requestsInCurrentMinute)
                $requests += $requestsInCurrentMinute;
        }
        if (false === $requestsInCurrentMinute) {
            $this->redis->setex($key, $minutes * 60 + 1, 1);
        } else {
            $this->redis->incr($key, 1);
        }
        if ($requests > $allowedRequests)
            throw new Exception("Too Many Requests");*/
    }

    private function getKeys($minutes)
    {
        $keys = array();
        $now = time();
        for ($time = $now - $minutes * 60; $time <= $now; $time += 60) {
            $keys[] = $this->prefix . date("dHi", $time);
        }
        return $keys;
    }
}
