<?php

namespace App\Components\Programation;

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
