<?php

use App\Schedule\Schedule;
use Carbon\CarbonImmutable;

require __DIR__ . '/vendor/autoload.php';

// use Carbon\Carbon;

$timezone = 'America/Lima';

date_default_timezone_set($timezone);

function getJson(string $path): array
{
    return json_decode(file_get_contents(__DIR__ . '/' . $path), true);
}

$scheduleData = getJson('resources/schedule/simple.json');

$schedule = new Schedule(
    $scheduleData['id'],
    $scheduleData['name'],
    $scheduleData['enabled'],
    $scheduleData['days'],
);

dd($schedule->getDay('monday', CarbonImmutable::parse('2021-10-04')));
