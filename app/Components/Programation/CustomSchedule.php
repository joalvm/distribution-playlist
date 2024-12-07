<?php

namespace App\Components\Programation;

use App\Components\Schedule\Range;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Arrayable;

class CustomSchedule implements Arrayable
{
    private array $schedulesList = [];

    public function __construct(array $schedule)
    {
        $this->schedulesList = $schedule;
    }

    public function toArray()
    {
        return $this->schedulesList;
    }

    /**
     * Retorna los rangos de tiempo disponibles para un día específico.
     * Tambien verifica que si hay asolapamiento de rangos, se debe convertir en uno solo
     * obteniendo la fecha menor y la fecha mayor.
     *
     * @return array<Range>
     */
    public function getRangesFor(CarbonImmutable $date): array
    {
        $dayName = strtolower($date->englishDayOfWeek);
        $ranges = [];

        foreach ($this->schedulesList as $schedule) {
            if (!in_array($dayName, $schedule['days'])) {
                continue;
            }

            $range = new Range(
                startTime: $date->copy()->setTimeFromTimeString($schedule['start_time']),
                endTime: $date->copy()->setTimeFromTimeString($schedule['finish_time'])
            );

            // Verificar si hay asolapamiento de rangos.
            foreach ($ranges as $index => $currentRange) {
                if ($range->overlapsWith($currentRange)) {
                    $ranges[$index] = new Range(
                        startTime: $currentRange->start()->min($range->start()),
                        endTime: $currentRange->end()->max($range->end())
                    );

                    continue 2;
                }
            }

            $ranges[] = $range;
        }

        return $ranges;
    }

    public function isAvailableFor(CarbonImmutable $date): bool
    {
        $dayName = strtolower($date->englishDayOfWeek);

        foreach ($this->schedulesList as $schedule) {
            if (in_array($dayName, $schedule['days'])) {
                return true;
            }
        }

        return false;
    }

    public static function factory(?array $data): ?self
    {
        if (null === $data) {
            return null;
        }

        return new self($data);
    }
}
