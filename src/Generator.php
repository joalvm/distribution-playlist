<?php

namespace App;

use Carbon\CarbonImmutable;

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
        public CarbonImmutable $date,
    ) {
        $this->playlist = new Playlist($date);
    }
}
