<?php

namespace App\Art;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Art implements Arrayable
{
    /**
     * Lista de medias que contiene el arte.
     *
     * @var array<Media>
     */
    private array $mediasList = [];

    /**
     * Cantidad total de medias que contiene el arte.
     */
    public int $totalMedias = 0;

    /**
     * Duración total del arte.
     */
    public int $duration = 0;

    /**
     * Índice que maneja las artes de tipo carrusel.
     *
     * Las artes de tipo carrusel son aquellas que contienen varias medias
     * pero solo se muestra una a la vez y se va cambiando cada que se invoca.
     */
    private int $carouselIndex = 0;

    public function __construct(
        public readonly int $id,
        public readonly int $mediaId,
        public readonly ArtType $type,
        array $items,
    ) {
        $this->handleItems($items);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'media_id' => $this->mediaId,
            'type' => $this->type->value,
            'duration' => $this->duration,
            'medias' => array_map(
                fn (Media $media) => $media->toArray(),
                $this->mediasList
            ),
        ];
    }

    public function getMedias(): array
    {
        if (ArtType::CAROUSEL === $this->type) {
            return $this->getCarouselMedias();
        }

        return $this->mediasList;
    }

    private function getCarouselMedias(): array
    {
        $media = $this->mediasList[$this->carouselIndex];

        ++$this->carouselIndex;

        if ($this->carouselIndex >= count($this->mediasList)) {
            $this->carouselIndex = 0;
        }

        $this->duration = $media->duration;

        return [$media];
    }

    private function handleItems(array $items): void
    {
        foreach (Arr::sort($items, 'position') as $item) {
            if ($item['duration'] <= 0) {
                $item['duration'] = 10;
            }

            $media = new Media(
                id: $item['media_id'],
                artId: $item['id'],
                type: MediaType::tryFrom($item['type']),
                path: $item['path'],
                hash: $item['hash'],
                duration: $item['duration'],
                position: $item['position'],
            );

            $this->mediasList[] = $media;

            ++$this->totalMedias;

            $this->duration += $media->duration;
        }

        if (ArtType::CAROUSEL === $this->type) {
            $this->duration = $this->mediasList[$this->carouselIndex]->duration;
        }
    }
}
