<?php

namespace App\Programation;

use App\Art\Art;
use Illuminate\Contracts\Support\Arrayable;

class Date implements Arrayable
{
    public function __construct(
        private readonly int $campaignId,
        private readonly int $campaignFaceArtId,
        private readonly int $reproductions,
        private readonly DistributionType $distributionType,
        private readonly bool $autoAdjust,
        private readonly Art $art,
    ) {
    }

    public function toArray()
    {
        return [
            'campaign_id' => $this->campaignId,
            'campaign_face_art_id' => $this->campaignFaceArtId,
            'reproductions' => $this->reproductions,
            'distribution_type' => $this->distributionType->value,
            'auto_adjust' => $this->autoAdjust,
            'art' => $this->art->toArray(),
        ];
    }
}
