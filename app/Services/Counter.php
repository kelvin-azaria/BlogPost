<?php

namespace App\Services;

use App\Contracts\CounterContract;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session;

class Counter implements CounterContract
{

    private $timeout, $cache, $session, $supportsTags;

    public function __construct(Cache $cache, Session $session,int $timeout)
    {
        $this->session = $session;
        $this->cache = $cache;
        $this->timeout = $timeout;
        $this->supportsTags = method_exists($cache, 'tags');
    }

    public function Increment(String $key, array $tags = null): int
    {
        $session_id = $this->session->getId();
        $counter_key = "{$key}-counter";
        $users_key = "{$key}-users";

        $cache = $this->supportsTags && null !== $tags ? 
            $this->cache->tags($tags) : $this->cache;

        $users = $cache->get($users_key, []);
        $users_update = [];
        $difference = 0;
        $now = now();

        foreach($users as $session => $last_visit){
            if($now->diffInMinutes($last_visit) >= $this->timeout){
                $difference--;
            }else{
                $users_update[$session] = $last_visit;
            }
        }

        if(!array_key_exists($session_id, $users)
            ||$now->diffInMinutes($users[$session_id]) >= $this->timeout){
            $difference++;
        }

        $users_update[$session_id] = $now;
        $cache->forever($users_key, $users_update);

        if(!$cache->has($counter_key)){
            $cache->forever($counter_key, 1);
        } else {
            $cache->increment($counter_key, $difference);
        }
        $counter = $cache->get($counter_key);

        return $counter;
    }
}