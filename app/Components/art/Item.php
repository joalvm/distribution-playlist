<?php

namespace App\Components\art;

use App\Enums\ArtType;
use Illuminate\Contracts\Support\Arrayable;

class Item implements Arrayable
{
    public function __construct(
        public readonly int $id,
        public readonly ArtType $type,
        public readonly string $path,
        public readonly int $duration,
        public readonly ?int $position = null,
    ) {
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'path' => $this->path,
            'duration' => $this->duration,
            'position' => $this->position,
        ];
    }
}
