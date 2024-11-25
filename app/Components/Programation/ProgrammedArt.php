<?php

namespace App\Components\Programation;

use App\Components\art\Art;
use App\Components\Schedule\Day;
use App\Components\Schedule\Range;
use App\Enums\DistributionType;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;

class ProgrammedArt implements Arrayable
{
    /**
     * @var array<array{
     *     range: Range,
     *     interval: int,
     *     reproductions: int,
     *     effective_start: CarbonImmutable,
     *     effective_end: CarbonImmutable
     * }>|null
     */
    private ?array $distribution = null;

    /**
     * constructor.
     */
    public function __construct(
        public readonly Art $art,
        public readonly int $totalReproductions,
        public readonly DistributionType $distributionType,
        public readonly ?CustomSchedule $customSchedules = null,
    ) {
    }

    public function calculateDistribuction(Day $day): void
    {
        $this->distribution = [];
    }

    public function usedSeconds(): int
    {
        return $this->totalReproductions * $this->art->duration;
    }

    public function toArray()
    {
        return [
            'art' => $this->art->toArray(),
            'total_reproductions' => $this->totalReproductions,
            'distribution_type' => $this->distributionType->value,
            'custom_schedules' => $this->customSchedules?->toArray(),
        ];
    }

    public function isScheduledFor(CarbonImmutable $date): bool
    {
        if (null === $this->customSchedules) {
            return true;
        }

        foreach ($this->customSchedules as $schedule) {
            if ($schedule->isAvailableFor($date)) {
                return true;
            }
        }

        return false;
    }

    public static function factory(array $data): self
    {
        return new self(
            art: Art::factory($data['art']),
            totalReproductions: $data['art']['total_reproductions'],
            distributionType: DistributionType::tryFrom($data['art']['distribution_type']),
            customSchedules: CustomSchedule::factory($data['custom_schedule']),
        );
    }
}
