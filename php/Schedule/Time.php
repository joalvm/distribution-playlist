<?php

namespace App\Schedule;

use Carbon\CarbonImmutable;

class Time
{
    public function __construct(
        private readonly CarbonImmutable $start,
        private readonly CarbonImmutable $end,
    ) {
    }
}
