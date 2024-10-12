<?php

namespace App\Art;

class Media
{
    public function __construct(
        public readonly int $id,
        public readonly int $artId,
        public readonly MediaType $type,
        public readonly string $path,
        public readonly string $hash,
        public readonly int $duration,
        public readonly int $position,
    ) {
    }
}
