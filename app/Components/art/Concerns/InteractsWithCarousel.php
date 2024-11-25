<?php

namespace App\Components\art\Concerns;

use App\Enums\ArtType;

trait InteractsWithCarousel
{
    /**
     * Índice que maneja las artes de tipo carrusel.
     *
     * Las artes de tipo carrusel son aquellas que contienen varias medias
     * pero solo se muestra una a la vez y se va cambiando cada que se invoca.
     */
    private int $carouselIndex = -1;

    /**
     * Actualiza la posición del carrusel.
     */
    public function updateCarouselIndex(): void
    {
        if (ArtType::CAROUSEL !== $this->type) {
            return;
        }

        ++$this->carouselIndex;

        if ($this->carouselIndex >= count($this->itemsList)) {
            $this->carouselIndex = 0;
        }
    }

    /**
     * Retorna el arte actual del carrusel.
     *
     * @return array<static>
     */
    private function getCarouselItem(): array
    {
        $this->updateCarouselIndex();

        $item = $this->itemsList[$this->carouselIndex];

        $this->duration = $item['duration'];

        return [self::factory($item)];
    }
}
