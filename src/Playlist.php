<?php

namespace App;

use Carbon\CarbonImmutable;

class Playlist
{
    public function __construct(
        public CarbonImmutable $currentDate,
    ) {
    }
}
