<?php

namespace App\Art;

enum ArtType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';
    case HTML = 'html';
    case CAROUSEL = 'carousel';
    case SEQUENCE = 'sequence';
}
