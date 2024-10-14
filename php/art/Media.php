<?php

namespace App\Art;

use Illuminate\Contracts\Support\Arrayable;

class Media implements Arrayable
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

    public function toArray()
    {
        return [
            'id' => $this->id,
            'art_id' => $this->artId,
            'type' => $this->type->value,
            'path' => $this->path,
            'hash' => $this->hash,
            'duration' => $this->duration,
            'position' => $this->position,
        ];
    }
}
