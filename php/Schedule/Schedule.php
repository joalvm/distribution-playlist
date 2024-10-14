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
        private readonly int $id,
        private readonly string $name,
        private readonly bool $enabled,
        array $days,
    ) {
        $this->daysMap = $days;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function hasDay(string $dayName): bool
    {
        return array_key_exists($dayName, $this->daysMap) and !empty($this->daysMap[$dayName]);
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
