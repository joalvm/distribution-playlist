<?php

define('ROOT_PATH', __DIR__);

require __DIR__ . '/vendor/autoload.php';

// Esto es el objeto que se recibe desde el front-end.
$payload = [
    // zona horario de la unidad a la cual se le va a generar las playlists.
    'timezone' => 'America/Lima',
    // Fecha de inicio de la generación de las playlists.
    'start_date' => '2024-12-11',
    // Fecha de fin de la generación de las playlists.
    'end_date' => '2024-12-20',
    // Horario de funcionamiento de la unidad.
    'schedule' => file_contents('resources/schedule/single_range.json'),
    // Arte por defecto configurado en la unidad.
    // El arte por defecto es usada para rellenar los espacios vacios en la programación.
    'default_art' => file_contents('resources/default_art/image.json'),
    // Lista de artes que se van a reproducir en la unidad.
    // Esta lista es la que se consulta a la base de datos y se obtiene todas las artes cuya programación
    // esta en el rango de fechas de inicio y fin.
    'arts' => file_contents('resources/programation/simple.json'),
];

// Proceso del backend.

// Se debe configurar la zona horaria de la unidad para que las fechas y horas sean correctas.
date_default_timezone_set($payload['timezone']);

// 1. Se debe tomar en cuenta que solo se pueden generar playlists de hoy en adelante.
// 2. Que si es el dia de hoy se debe recuperar la actual playlists que se esta reproduciendo y
//    reemplazar solo las reproducciones a partir de la hora actual en adelante,
//    no se debe reemplazar las reproducciones que ya se reprodujeron.
$startDate = Carbon\CarbonImmutable::parse($payload['start_date'])->startOfDay();
$endDate = Carbon\CarbonImmutable::parse($payload['end_date'])->endOfDay();

// Si la fecha de inicio es menor a la fecha actual, entonces se debe tomar la fecha actual como inicio.
// Si es hoy se debe tomar en cuenta la hora actual, para editar la actual playlist solo a partir de la hora actual.
// lo que ya se reprodujo no se debe reemplazar.
if ($startDate->isPast()) {
    $startDate = Carbon\CarbonImmutable::now();
}

// Si la fecha de fin es menor a la fecha de inicio o es una fecha pasada, entonces se debe tomar la fecha actual como fin.
if (!$endDate or $endDate->isPast()) {
    $endDate = Carbon\CarbonImmutable::now();
}

// Horarios de la unidad.
// Los horarios de unidad son los dias en los que la unidad funciona y pueden tener rangos de horarios
// especificos para cada dia y mas de un rango en el mismo dia. ejemplo: Lunes de 06:00:00 a 12:00:00 y de 14:00:00 a 22:00:00 ó Martes de 00:00:00 a 23:59:59.
// También se debe tener en cuenta que el horario puede tener configurado como minimo 1 dia de la semana(no es requerido que tenga los 7 dias de la semana) y
// que debe tener 1 rango como minimo por dia y que el rango debe tener una duración minima de 1 hora.
$schedule = App\components\Schedule\Schedule::factory($payload['schedule']);

// Arte por defecto configurado en la unidad.
// El arte por defecto configurado en la unidad es usado para rellenar los espacios vacios en la programación.
$defaultArt = App\Components\Art\Art::factory($payload['default_art']);

// Programación de todas las artes que se van a reproducir en la unidad.
// 1. La lista de artes que se obtuvieron tras consultar la base de datos, se debe filtrar
//    solo aquellas artes que se deben reproducir en el rango de fechas de inicio y fin.
// 2. Se debe tener en cuenta que algunas artes tienen un horario de reproducción especifico,
//    por lo que se debe tomar en cuentas las horas especificas de reproducción y los dias.
$programation = App\Components\Programation\Programation::factory($payload['arts']);

foreach ($startDate->daysUntil($endDate) as $date) {
    // Si el dia actual no esta en el horario, entonces no se debe generar playlist.
    if (!$schedule->hasDay($date)) {
        continue;
    }

    // Artes cuyo fecha actual, esta en el rango de su programación.
    $day = $schedule->getDay($date);
    $programmedArts = $programation->getArtsForDay($day);

    // Generar playlist.
    $generator = new App\Generator($schedule->getDay($date), $defaultArt);

    $generator->run($programmedArts);
}

// Nota: el resultado final debe ser un archivo csv con la programación de las artes en base a su total de reproducciones,
//       el archivo contiene los tiempos exactos en los que cada arte se debe reproducir. El formato es el siguiente:
//         - Id del arte.
//         - Tipo de arte. (solo videos, imagenes o html)(La artes de tipo secuencia y carousel se deben desglosar en base a sus items).
//         - Duración de la reproducción.
//         - Tiempo de inicio de reproducción. (formato: H:i:s)
//         - Tipo de distribución.
//      el nombre de archivo debe tener la siguiente estructura: "year/month/day/playlist_{year}{month}{day}.csv"

// Ejemplo:
// art_id,type,duration,start_time,distribution
// 1,video,30,00:00:00,immutable
// 2,image,10,00:00:30,immutable
