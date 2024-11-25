<?php

use App\Components\Art\Art;
use App\Components\Programation\Programation;
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
$programation = Programation::factory(getJson('resources/programation/simple.json'));

foreach ($startDate->daysUntil($endDate) as $date) {
    // Si el dia actual no esta en el horario, entonces no se debe generar playlist.
    if (!$schedule->hasDay($date)) {
        continue;
    }

    // Información del dia.
    $day = $schedule->getDay($date);

    // Artes del dia en el iterador.
    $programmedArts = $programation->getForDay($date);
    $totalSeconds = 0;

    foreach ($programmedArts as $programmedArt) {
        $programmedArt->calculateDistribuction($day);

        $totalSeconds += $programmedArt->usedSeconds();
        dd($programmedArt, $totalSeconds);
    }
}

// Nota: el resultado final debe ser un archivo csv con la programación de las artes en base a su total de reproducciones,
//       el archivo contiene los tiempos exactos en los que cada arte se debe reproducir. El formato es el siguiente:
//         - Id del arte.
//         - Tipo de arte. (solo videos, imagenes o html)(La artes de tipo secuencia y carousel se deben desglosar en sus partes).
//         - Duración de la reproducción.
//         - Tiempo de inicio de reproducción. (formato: H:i:s)
//         - Tipo de distribución.
//      el nombre de archivo debe tener la siguiente estructura: "year/month/day/playlist_{year}{month}{day}.csv"
// Ejemplo:
// 1,video,30,00:00:00,immutable
// 2,image,10,00:00:30,immutable
