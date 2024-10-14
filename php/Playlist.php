<?php

namespace App;

use App\Schedule\Day;

class Playlist
{
    public function __construct(private Day $day)
    {
    }
}
