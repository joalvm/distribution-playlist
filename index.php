<?php

use App\Components\Art\Art;
use App\components\Schedule\Schedule;
use Carbon\CarbonImmutable;

require __DIR__ . '/vendor/autoload.php';

function getJson(string $path): array
{
    return json_decode(file_get_contents(__DIR__ . '/' . $path), true) ?? [];
}

// Simula la zona horario de la unidad a la cual se le va a generar las playlists.
$timezone = 'America/Lima';

date_default_timezone_set($timezone);

// Fecha de inicio y fin para la generación de las playlists.
// 1. Se debe tomar en cuenta que solo se pueden generar playlists de hoy en adelante.
// 2. Que si es el dia de hoy se debe recuperar la actual playlists que se esta reproduciendo y
//    reemplazar solo las reproducciones a partir de la hora actual en adelante,
//    no se debe reemplazar las reproducciones que ya se reprodujeron.
$startDate = CarbonImmutable::now()->startOfDay();
$endDate = $startDate->copy()->addDays(10)->endOfDay();

// Horarios de la unidad.
$schedule = Schedule::factory(getJson('resources/schedule/single_range.json'));

// Arte por defecto configurado en la unidad.
$defaultArt = Art::factory(getJson('resources/default_art/image.json'));

// Programación de todas las artes que se van a reproducir en la unidad.
// 1. La lista de artes que se obtuvieron tras consultar la base de datos, se debe filtrar
//    solo aquellas artes que se deben reproducir en el rango de fechas de inicio y fin.
// 2. Se debe tener en cuenta que algunas artes tienen un horario de reproducción especifico,
//    por lo que se debe tomar en cuentas las horas especificas de reproducción y los dias.
$arts = getJson('resources/programation/simple.json');

dd($arts);

foreach ($startDate->daysUntil($endDate) as $date) {
    // Si el dia actual no esta en el horario, entonces no se debe generar playlist.
    if (!$schedule->hasDay($date)) {
        continue;
    }

    $day = $schedule->getDay($date);

    // Obtener programación.
}
