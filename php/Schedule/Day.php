<?php

namespace App\Schedule;

use Carbon\CarbonImmutable;

class Day
{
    /**
     * Lista de rangos de tiempo.
     *
     * @var array<array{start:string,end:string}>
     */
    private $timesList = [];

    /**
     * Total de segundos de todos los rangos de tiempo.
     */
    private $totalSeconds = 0;

    /**
     * Constructor.
     *
     * @param string $name  Nombre del día.
     * @param array  $times Lista de rangos de tiempo.
     */
    public function __construct(
        public string $name,
        public CarbonImmutable $date,
        array $times,
    ) {
        foreach ($times as $time) {
            $start = $this->makeTime($time['start']);
            $end = $this->makeTime($time['end']);

            $this->timesList[] = new Time(
                start: $start,
                end: $end,
            );

            $this->totalSeconds += $end->diffInSeconds($start);
        }
    }

    public function isEmpty(): bool
    {
        return empty($this->timesList);
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
