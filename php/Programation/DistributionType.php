<?php

namespace App\Programation;

enum DistributionType: string
{
    case IMMUTABLE = 'immutable';
    case EDITABLE = 'editable';
    case DELETABLE = 'deletable';
}
