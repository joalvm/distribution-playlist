<?php

namespace App\Programation;

use App\Art\Art;
use App\Art\ArtType;
use App\Schedule\Day;
use App\Schedule\Schedule;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Programation implements Arrayable
{
    /**
     * Contiene, dia por dia todas las artes programadas.
     *
     * @var array<string,array{day:Day,arts:array<Date>}>
     */
    private array $datesMap = [];

    public function __construct(
        private readonly Schedule $schedule,
        array $artDates,
    ) {
        foreach ($artDates as $artDate) {
            $startDate = Carbon::parse($artDate['start_date'])->startOfDay();
            $endDate = Carbon::parse($artDate['finish_date'])->startOfDay();

            $this->registerArts($artDate, $startDate, $endDate);
        }
    }

    public function toArray()
    {
        return array_map(
            fn ($date) => [
                'day' => $date['day']->toArray(),
                'arts' => array_map(
                    fn (Date $art) => $art->toArray(),
                    $date['arts']
                ),
            ],
            $this->datesMap
        );
    }

    /**
     * Obtiene todas las fechas programadas.
     *
     * @return array<string,array{day:Day,arts:array<Date>}>
     */
    public function getDates(): array
    {
        return $this->datesMap;
    }

    private function registerArts(array $artDate, Carbon $startDate, Carbon $endDate)
    {
        for ($dateAt = $startDate->clone(); $dateAt->lte($endDate); $dateAt->addDay()) {
            $dayName = strtolower($dateAt->englishDayOfWeek);
            $day = $this->schedule->getDay($dayName, $dateAt->toImmutable());

            if ($day->isEmpty()) {
                continue;
            }

            $this->registerArt($this->makeDate($artDate), $day);
        }
    }

    private function registerArt(Date $artDate, Day $day)
    {
        $dateStr = $day->date->format('Y-m-d');

        if (!array_key_exists($dateStr, $this->datesMap)) {
            $this->datesMap[$dateStr] = ['day' => $day, 'arts' => []];
        }

        $this->datesMap[$dateStr]['arts'][] = $artDate;
    }

    private function makeDate(array $artDate)
    {
        $art = $artDate['art'];

        return new Date(
            campaignId: $artDate['campaign_id'],
            campaignFaceArtId: $artDate['id'],
            reproductions: $artDate['reproductions'],
            distributionType: DistributionType::tryFrom($artDate['distribution']),
            autoAdjust: $artDate['auto_adjust'],
            art: new Art(
                id: $art['id'],
                mediaId: $art['media_id'],
                type: ArtType::tryFrom($art['type']),
                items: $art['medias'],
            )
        );
    }
}
