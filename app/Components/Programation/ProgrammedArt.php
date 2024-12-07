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
     *     interval: float,
     *     reproductions: int,
     *     accumulated_fraction: float
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

    public function calculateDistribution(Day $day): static
    {
        $this->distribution = [];
        $effectiveRanges = $this->getAvailableRanges($day);

        if (empty($effectiveRanges)) {
            return $this;
        }

        $totalRangeSeconds = array_sum(array_map(fn ($range) => $range->seconds(), $effectiveRanges));
        $artDuration = $this->art->duration;

        foreach ($effectiveRanges as $range) {
            // Primero verificamos cuántas reproducciones caben en este rango
            $maxPossibleReproductions = (int) floor($range->seconds() / $artDuration);

            // Calculamos la proporción deseada de reproducciones
            $reproductionRatio = ($range->seconds() / $totalRangeSeconds) * $this->totalReproductions;

            // Nos aseguramos de no exceder el máximo posible
            $reproductionRatio = min($reproductionRatio, $maxPossibleReproductions);

            // Separamos parte entera y decimal
            $rangeReproductions = (int) floor($reproductionRatio);
            $fraction = $reproductionRatio - $rangeReproductions;

            // El intervalo debe ser al menos la duración del arte
            $interval = max(
                $range->seconds() / $reproductionRatio,
                $artDuration
            );

            $this->distribution[] = [
                'range' => $range,
                'interval' => $interval,
                'reproductions' => $rangeReproductions,
                'accumulated_fraction' => $fraction,
            ];
        }

        // Ajustamos las reproducciones basadas en las fracciones acumuladas
        $this->adjustReproductionsWithFractions();

        return $this;
    }

    private function adjustReproductionsWithFractions(): void
    {
        if (null === $this->distribution) {
            return;
        }

        $accumulatedFraction = 0.0;

        // Primera pasada: acumular fracciones
        foreach ($this->distribution as &$dist) {
            $accumulatedFraction += $dist['accumulated_fraction'];

            if ($accumulatedFraction >= 1) {
                $extraReproduction = (int) floor($accumulatedFraction);
                $dist['reproductions'] += $extraReproduction;
                $accumulatedFraction -= $extraReproduction;

                // Recalculamos el intervalo con la nueva cantidad de reproducciones
                $dist['interval'] = $dist['range']->seconds() / $dist['reproductions'];
            }
        }
    }

    /**
     * Retorna la distribución calculada.
     *
     * @return array<array{
     *     range: Range,
     *     interval: float,
     *     reproductions: int,
     *     accumulated_fraction: float
     * }>|null
     */
    public function getDistribution(): ?array
    {
        return $this->distribution;
    }

    public function withSchedule(): bool
    {
        return null !== $this->customSchedules;
    }

    /**
     * Retorna los rangos disponibles según el custom schedule.
     *
     * @return array<Range>
     */
    private function getAvailableRanges(Day $day): array
    {
        if (null === $this->customSchedules) {
            return $day->ranges();
        }

        $ranges = [];

        foreach ($this->customSchedules->getRangesFor($day->date) as $range) {
            // Verificar que los rangos customizados no se salgan de los rangos del día.
            foreach ($day->ranges() as $dayRange) {
                // Si el rango customizado está dentro del rango del día, se toma tal cual.
                if ($range->isInsideOf($dayRange)) {
                    $ranges[] = $range;

                    continue;
                }

                if ($range->overlapsWith($dayRange)) {
                    // Si el rango customizado se solapa con el rango del día, se debe ajustar al rango del día.
                    // Esto quiere decir que si customStart es menor a dayStart, se debe tomar dayStart debido a que
                    // el rango customizado no puede empezar fuera del rango del día.
                    $start = $range->start()->greaterThanOrEqualTo($dayRange->start())
                        ? $range->start()->copy()
                        : $dayRange->start()->copy();

                    // Si customEnd es mayor a dayEnd, se debe tomar dayEnd debido a que el rango customizado no puede
                    // terminar fuera del rango del día.
                    $end = $range->end()->lessThanOrEqualTo($dayRange->end())
                        ? $range->end()->copy()
                        : $dayRange->end()->copy();

                    $ranges[] = new Range($start, $end);
                }
            }
        }

        return $ranges;
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

        return $this->customSchedules->isAvailableFor($date);
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
