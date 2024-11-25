<?php

namespace App\Enums;

/**
 * Enumera los tipos de distribución que las artes pueden tener dentro de una playlist.
 */
enum DistributionType: string
{
    /**
     * El Arte no modifica su posición en el tiempo de reproducción.
     */
    case IMMUTABLE = 'immutable';

    /**
     * El Arte puede modificar su posición en el tiempo de reproducción.
     */
    case EDITABLE = 'editable';

    /**
     * El Arte puede ser reemplazado en caso de que se crucen con otro.
     */
    case DELETABLE = 'deletable';
}
