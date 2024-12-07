<?php

namespace App;

use App\Components\art\Art;
use App\Components\Programation\ProgrammedArt;
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
     * @param array<ProgrammedArt> $arts
     *
     * @return void
     */
    public function run(array $arts)
    {
        dd($arts);
        // Iniciamos la información de la playlist.
    }
}
