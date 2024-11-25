<?php

namespace App\Components\Schedule;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Clase que representa un rango de tiempo en un día.
 */
class Range implements Arrayable
{
    private int $totalSeconds = 0;

    public function __construct(
        private readonly CarbonImmutable $startTime,
        private readonly CarbonImmutable $endTime,
    ) {
        $this->totalSeconds = $this->endTime->diffInSeconds($this->startTime, true);
    }

    public function toArray()
    {
        return [
            'start_time' => $this->startTime->toTimeString(),
            'end_time' => $this->endTime->toTimeString(),
            'total_seconds' => $this->totalSeconds,
        ];
    }

    public function start(): CarbonImmutable
    {
        return $this->startTime;
    }

    public function end(): CarbonImmutable
    {
        return $this->endTime;
    }

    public function seconds(): int
    {
        return $this->totalSeconds;
    }
}
