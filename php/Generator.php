<?php

namespace App;

use App\Art\Art;
use App\Programation\Date;
use App\Schedule\Day;

/**
 * Clase encargada de generar y distribuir las artes en las playlist DOOH.
 */
class Generator
{
    public Playlist $playlist;

    /**
     * Crea una nueva instancia de la clase.
     */
    public function __construct(
        public readonly Day $day,
        public readonly Art $defaultArt,
    ) {
        $this->playlist = new Playlist($day);
    }

    /**
     * Genera las playlist de las artes programadas.
     *
     * @param array<Date> $dates
     *
     * @return void
     */
    public function generate(array $dates)
    {
        dd(array_map(fn (Date $date) => $date->toArray(), $dates));
    }
}
