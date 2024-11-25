<?php

namespace App\Components\Schedule;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Clase que representa un horario de programación.
 */
class Schedule implements Arrayable
{
    /**
     * Mapa de dias de la semana.
     *
     * @var array<string,array<array{start:string,end:string}>>
     */
    public array $daysMap = [];

    public function __construct(private readonly int $id, array $days)
    {
        $this->daysMap = $days;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'days' => array_map(fn (Day $day) => $day->toArray(), $this->daysMap),
        ];
    }

    /**
     * Obtiene el id del horario de programación.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Verifica si un dia está dentro del horario de programación.
     */
    public function hasDay(CarbonImmutable $date): bool
    {
        return array_key_exists(
            strtolower($date->englishDayOfWeek),
            $this->daysMap
        );
    }

    /**
     * Obtiene la clase que representa un día de la semana y sus rangos de tiempo.
     */
    public function getDay(CarbonImmutable $date): Day
    {
        $dayName = strtolower($date->englishDayOfWeek);

        return new Day(
            date: $date->copy(),
            ranges: $this->daysMap[$dayName] ?? [],
        );
    }

    public static function factory(array $data): static
    {
        return new self(
            id: $data['id'],
            days: $data['days'] ?? [],
        );
    }
}
