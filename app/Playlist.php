<?php

namespace App;

class Playlist
{
    /**
     * Segundos restantes.
     */
    private int $remainingSeconds = 0;

    public function __construct(private readonly int $totalSeconds)
    {
        $this->remainingSeconds = $totalSeconds;
    }

    public function hasRemainingSeconds(int $seconds): bool
    {
        return $this->remainingSeconds >= $seconds;
    }
}
