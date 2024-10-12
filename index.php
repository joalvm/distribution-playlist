<?php

use App\Art\Art;
use App\Art\ArtType;
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

$schedule = new Schedule(
    $scheduleData['id'],
    $scheduleData['name'],
    $scheduleData['enabled'],
    $scheduleData['days'],
);

$defaultArt = new Art(
    $defaultArtData['id'],
    $defaultArtData['media_id'],
    ArtType::tryFrom($defaultArtData['type']),
    $defaultArtData['medias'],
);

dd($defaultArt->getMedias(), $defaultArt->getMedias());
