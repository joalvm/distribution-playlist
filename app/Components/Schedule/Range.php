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

    /**
     * Verifica si el rango actual se solapa con otro rango.
     */
    public function overlapsWith(Range $range): bool
    {
        return $this->start()->between($range->start(), $range->end())
            or $this->end()->between($range->start(), $range->end());
    }

    /**
     * Verifica si el rango actual está dentro de otro rango.
     */
    public function isInsideOf(Range $range): bool
    {
        return $this->start()->greaterThanOrEqualTo($range->start())
            and $this->end()->lessThanOrEqualTo($range->end());
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
