<?php

namespace App\Enums;

/**
 * Enumera los tipos de arte que pueden existir.
 */
enum ArtType: string
{
    /**
     * Arte de tipo imagen (jpg, png, gif, etc).
     */
    case IMAGE = 'image';

    /**
     * Arte de tipo video (mp4, webm, etc).
     */
    case VIDEO = 'video';

    /**
     * Arte de tipo HTML (puede ser un iframe, un archivo HTML, etc).
     */
    case HTML = 'html';

    /**
     * Arte de tipo carrusel.
     *
     * Las artes de tipo carrusel son aquellas que agrupan otras artes
     * y se van mostrando, por orden, de una en una y por cada iteración.
     */
    case CAROUSEL = 'carousel';

    /**
     * Arte de tipo secuencia.
     *
     * Las artes de tipo secuencia son aquellas que agrupan otras artes
     * y se muestran todas al mismo tiempo, en el orden asignado y en cada iteración.
     */
    case SEQUENCE = 'sequence';

    /**
     * Indica si el tipo de arte es de agrupación.
     *
     * @param ArtType $type
     */
    public static function isGrouping(ArtType|string $type): bool
    {
        if (is_string($type)) {
            $type = self::tryFrom($type);
        }

        return match ($type) {
            self::CAROUSEL, self::SEQUENCE => true,
            default => false,
        };
    }
}
