<?php

namespace App\Programation;

class Campaign
{
    public function __construct(
        public readonly int $id,
        public readonly int $faceArtId,
        public readonly SaleType $saleType,
        public readonly int $saleValue,
    ) {
    }
}
