<?php

use App\Art\Art;
use App\Art\ArtType;
use App\Generator;
use App\Programation\Programation;
use App\Schedule\Schedule;

require __DIR__ . '/vendor/autoload.php';

// use Carbon\Carbon;

$timezone = 'America/Lima';

date_default_timezone_set($timezone);

function getJson(string $path): array
{
    return json_decode(file_get_contents(__DIR__ . '/' . $path), true);
}

$scheduleData = getJson('resources/schedule/simple.json');
$defaultArtData = getJson('resources/default_art/carousel.json');
$programantionData = getJson('resources/programation_arts.json');

$schedule = new Schedule(
    $scheduleData['id'],
    $scheduleData['name'],
    $scheduleData['enabled'],
    $scheduleData['days'],
);

$defaultArt = new Art(
    id: $defaultArtData['id'],
    mediaId: $defaultArtData['media_id'],
    type: ArtType::tryFrom($defaultArtData['type']),
    items: $defaultArtData['medias'],
);

$programation = new Programation(
    artDates: $programantionData,
    schedule: $schedule,
);

foreach ($programation->getDates() as $date => $data) {
    if ('2024-10-30' !== $date) {
        continue;
    }

    $generator = new Generator($data['day'], $defaultArt);

    $generator->generate($data['arts']);
}
