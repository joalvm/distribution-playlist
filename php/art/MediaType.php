<?php

namespace App\Art;

enum MediaType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';
    case HTML = 'html';
}
