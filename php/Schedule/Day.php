<?php

namespace App\Schedule;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;

class Day implements Arrayable
{
    /**
     * Lista de rangos de tiempo.
     *
     * @var array<Time>
     */
    private array $timesList = [];

    /**
     * Tiempo mínimo de todos los rangos de tiempo.
     */
    private ?CarbonImmutable $minTime = null;

    /**
     * Tiempo máximo de todos los rangos de tiempo.
     */
    private ?CarbonImmutable $maxTime = null;

    /**
     * Total de segundos de todos los rangos de tiempo.
     */
    private int $totalSeconds = 0;

    /**
     * Constructor.
     *
     * @param string $name  Nombre del día.
     * @param array  $times Lista de rangos de tiempo.
     */
    public function __construct(
        public readonly string $name,
        public readonly CarbonImmutable $date,
        array $times,
    ) {
        foreach ($times as $time) {
            $start = $this->makeTime($time['start']);
            $end = $this->makeTime($time['end']);

            $this->timesList[] = new Time(start: $start, end: $end);

            $this->totalSeconds += $end->diffInSeconds($start, true);

            if (null === $this->minTime or $start->lt($this->minTime)) {
                $this->minTime = $start;
            }

            if (null === $this->maxTime or $end->gt($this->maxTime)) {
                $this->maxTime = $end;
            }
        }
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'times' => array_map(fn (Time $time) => $time->toArray(), $this->timesList),
            'total_seconds' => $this->totalSeconds,
        ];
    }

    public function minTime(): ?CarbonImmutable
    {
        return $this->minTime;
    }

    public function maxTime(): ?CarbonImmutable
    {
        return $this->maxTime;
    }

    /**
     * Retorna la lista de rangos de tiempo.
     *
     * @return array<Time>
     */
    public function getTimes(): array
    {
        return $this->timesList;
    }

    public function isEmpty(): bool
    {
        return empty($this->timesList);
    }

    /**
     * Retorna la cantidad de segmentos de tiempo que tiene el día.
     */
    public function totalSegments(): int
    {
        return count($this->timesList);
    }

    /**
     * Retorna el total de segundos de todos los rangos de tiempo.
     */
    public function totalSeconds(): int
    {
        return $this->totalSeconds;
    }

    /**
     * Genera una instancia de CarbonImmutable con la fecha y hora especificada.
     */
    private function makeTime(string $time): CarbonImmutable
    {
        return $this->date
            ->clone()
            ->toMutable()
            ->startOfDay()
            ->setTimeFrom($time)
            ->toImmutable()
        ;
    }
}
