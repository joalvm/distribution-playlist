<?php

namespace App\Components\Schedule;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Clase que representa un día con sus rangos de tiempo.
 */
class Day implements Arrayable
{
    /**
     * Lista de rangos de tiempo.
     *
     * @var array<Range>
     */
    private array $rangesList = [];

    /**
     * Total de rangos de tiempo.
     */
    private int $totalRanges = 0;

    /**
     * Total de segundos de todos los rangos de tiempo.
     */
    private int $totalSeconds = 0;

    /**
     * Constructor.
     */
    public function __construct(
        public readonly CarbonImmutable $date,
        array $ranges,
    ) {
        foreach ($ranges as $rangeTime) {
            $range = new Range(
                startTime: $this->date->copy()->setTimeFromTimeString($rangeTime['start']),
                endTime: $this->date->copy()->setTimeFromTimeString($rangeTime['end']),
            );

            $this->totalSeconds += $range->seconds();

            $this->totalRanges = array_push($this->rangesList, $range);
        }
    }

    public function toArray()
    {
        return [
            'date' => $this->date,
            'total_seconds' => $this->totalSeconds,
            'ranges' => array_map(
                fn (Range $time) => $time->toArray(),
                $this->rangesList
            ),
        ];
    }

    /**
     * Retorna el nombre del día.
     */
    public function name(): string
    {
        return strtolower($this->date->englishDayOfWeek);
    }

    /**
     * Retorna la lista de rangos de tiempo.
     *
     * @return array<Range>
     */
    public function ranges(): array
    {
        return $this->rangesList;
    }

    /**
     * Retorna el total de rangos de tiempo en el día.
     */
    public function totalRanges(): int
    {
        return $this->totalRanges;
    }

    /**
     * Verifica si el dia no tiene rangos de tiempo.
     */
    public function isEmpty(): bool
    {
        return 0 === $this->totalRanges;
    }

    /**
     * Retorna el total de segundos de todos los rangos de tiempo.
     */
    public function seconds(): int
    {
        return $this->totalSeconds;
    }
}
