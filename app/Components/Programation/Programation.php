<?php

namespace App\Components\Programation;

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
    public function getForDay(CarbonImmutable $date): array
    {
        $arts = [];

        foreach ($this->programationList as $prog) {
            if (!$date->between($prog['start_date'], $prog['finish_date'])) {
                continue;
            }

            $arts[] = ProgrammedArt::factory($prog);
        }

        return $arts;
    }

    public static function factory(array $data): static
    {
        return new self($data);
    }
}
