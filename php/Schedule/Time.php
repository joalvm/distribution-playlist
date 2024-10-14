<?php

namespace App\Schedule;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;

class Time implements Arrayable
{
    private int $totalSeconds = 0;

    public function __construct(
        public readonly CarbonImmutable $start,
        public readonly CarbonImmutable $end,
    ) {
        $this->totalSeconds = $this->end->diffInSeconds($this->start, true);
    }

    public function toArray()
    {
        return [
            'start' => $this->start->format('H:i:s'),
            'end' => $this->end->format('H:i:s'),
            'total_seconds' => $this->totalSeconds,
        ];
    }

    public function totalSeconds(): int
    {
        return $this->totalSeconds;
    }
}
