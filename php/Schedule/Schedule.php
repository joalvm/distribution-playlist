<?php

namespace App\Schedule;

use Carbon\CarbonImmutable;

class Schedule
{
    /**
     * Mapa de dias de la semana.
     *
     * @var array<string,array<array{start:string,end:string}>>
     */
    public array $daysMap = [];

    public function __construct(
        public int $id,
        public string $name,
        public bool $enabled,
        array $days,
    ) {
        $this->daysMap = $days;
    }

    public function hasDay(string $dayName): bool
    {
        return isset($this->daysMap[$dayName]);
    }

    public function getDay(string $dayName, CarbonImmutable $date): Day
    {
        return new Day(
            $dayName,
            $date,
            $this->daysMap[$dayName] ?? [],
        );
    }
}
