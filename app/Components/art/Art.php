<?php

namespace App\Components\art;

use App\Enums\ArtType;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Art implements Arrayable
{
    use Concerns\InteractsWithCarousel;

    /**
     * Lista de medias que contiene el arte.
     *
     * @var array<array{id:int,type:string,path:string,duration:int,position:int}>
     */
    private array $itemsList = [];

    /**
     * Cantidad total de medias que contiene el arte.
     */
    public int $totalItems = 0;

    public function __construct(
        public readonly int $id,
        public readonly ArtType $type,
        public readonly ?string $path = null,
        public int $duration = 0,
        public int $position = 1,
        array $items = [],
    ) {
        if (ArtType::isGrouping($this->type)) {
            $this->handleItems($items);
        }
    }

    public function toArray()
    {
        if (ArtType::isGrouping($this->type)) {
            return [
                'id' => $this->id,
                'type' => $this->type->value,
                'items' => $this->itemsList,
            ];
        }

        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'path' => $this->path,
            'duration' => $this->duration,
        ];
    }

    /**
     * Retorna la lista de medias.
     *
     * @return array<self>
     */
    public function items()
    {
        if (ArtType::CAROUSEL === $this->type) {
            return $this->getCarouselItem();
        }

        return array_map(fn ($item) => self::factory($item), $this->itemsList);
    }

    public static function factory(array $data): self
    {
        if (ArtType::isGrouping($data['type'])) {
            return new self(
                id: $data['id'],
                type: ArtType::tryFrom($data['type']),
                items: $data['items'],
            );
        }

        return new self(
            id: $data['id'],
            type: ArtType::tryFrom($data['type']),
            path: $data['path'],
            duration: $data['duration'],
            position: $data['position'] ?? 1,
        );
    }

    private function handleItems(array $items): void
    {
        foreach (Arr::sort($items, 'position') as $item) {
            if (!$item['duration']) {
                $item['duration'] = 10;
            }

            $this->totalItems = array_push($this->itemsList, $item);

            $this->duration += $item['duration'];
        }
    }
}
