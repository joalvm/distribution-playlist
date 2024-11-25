<?php

namespace App;

use App\Components\art\Art;
use App\Components\Schedule\Day;

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
        $this->playlist = new Playlist($day->seconds());
    }

    /**
     * Genera las playlist de las artes programadas.
     *
     * @param array<Art> $arts
     *
     * @return void
     */
    public function generate(array $arts)
    {
    }
}
