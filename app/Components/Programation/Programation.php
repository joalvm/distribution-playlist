<?php

namespace App\Components\Programation;

use App\Components\Schedule\Day;
use Carbon\CarbonImmutable;

class Programation
{
    /** @var array<string,array{start_date:CarbonImmutable,finish_date:CarbonImmutable,art:array,custom_schedule:?array}> */
    private array $programationList = [];

    public function __construct(array $programation)
    {
        foreach ($programation as $item) {
            $this->programationList[] = [
                'start_date' => CarbonImmutable::parse($item['start_date'])->startOfDay(),
                'finish_date' => CarbonImmutable::parse($item['finish_date'])->endOfDay(),
                'art' => $item['art'],
                'custom_schedule' => $item['custom_schedule'],
            ];
        }
    }

    /**
     * Obtiene todas las artes programadas para un día específico.
     *
     * @return array<ProgrammedArt>
     */
    public function getArtsForDay(Day $day): array
    {
        $arts = [];

        foreach ($this->programationList as $prog) {
            if (!$day->date->between($prog['start_date'], $prog['finish_date'])) {
                continue;
            }

            $programmedArt = ProgrammedArt::factory($prog);

            if (!$programmedArt->isScheduledFor($day->date)) {
                continue;
            }

            $programmedArt->calculateDistribution($day);

            $arts[] = $programmedArt;
        }

        return $arts;
    }

    public static function factory(array $data): static
    {
        return new self($data);
    }
}
