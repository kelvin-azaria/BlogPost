<?php

namespace App\Contracts;

interface CounterContract
{
    public function Increment(String $key, array $tags = null): int;
}