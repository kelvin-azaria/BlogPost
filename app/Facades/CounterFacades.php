<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * A facade do contract
 * @method static int Increment(string $key, array $tags = null)
 */

class CounterFacades extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'App\Contracts\CounterContract';
    }
}