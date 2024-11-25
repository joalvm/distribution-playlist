<?php

require __DIR__ . '/vendor/autoload.php';

// Lista de artes con sus respectivas reproducciones
$arts = [
    ['name' => 'A', 'reproductions' => 1080],
    ['name' => 'B', 'reproductions' => 340],
    ['name' => 'C', 'reproductions' => 737],
    ['name' => 'D', 'reproductions' => 868],
    ['name' => 'E', 'reproductions' => 335],
];

// Paso 1: Obtener el mayor número de reproducciones entre todas las artes
// Esto nos da el número máximo de loops necesarios, ya que al menos un arte
// debe aparecer en todos los ciclos.
$maxLoops = max(array_column($arts, 'reproductions'));

// Paso 2: Inicializamos los loops como un array vacío con tantos slots como el mayor número de reproducciones.
// Cada "loop" es un array vacío que contendrá los artes correspondientes a esa iteración.
$loops = array_fill(0, $maxLoops, []);


// Paso 3: Recorremos cada arte para calcular su dispersión en los loops.
foreach ($arts as $art) {
    // Obtener la cantidad de reproducciones del arte actual
    $reproductions = $art['reproductions'];
    $name = $art['name'];

    // Paso 4: Calculamos el intervalo de dispersión
    // Dividimos el número total de loops entre la cantidad de reproducciones
    // para determinar la cantidad de saltos (o espacios) entre cada reproducción de este arte.
    $dispersion = $maxLoops / $reproductions;

    // Variable que mantiene la posición actual en los loops para este arte.
    $currentIndex = 0;

    // Variable que acumula la parte decimal sobrante de cada paso, para hacer los ajustes.
    $fraction = $dispersion - floor($dispersion);

    // Paso 5: Distribuimos las reproducciones en los loops
    for ($i = 0; $i < $reproductions; $i++) {
        // Redondeamos la posición actual sumando la parte entera y ajustando con la fracción acumulada
        $index = floor($currentIndex + $fraction);

        // Paso 6: Añadimos el nombre del arte al loop correspondiente
        $loops[$index][] = $name;

        // Acumulamos la parte decimal sobrante del cálculo
        $fraction += $dispersion - floor($dispersion);

        // Aumentamos la posición actual en función de la parte entera del paso
        $currentIndex += floor($dispersion);

        // Si la fracción acumulada es mayor o igual a 1, la restamos y avanzamos un índice
        // Esto asegura que distribuimos el arte de forma equitativa, ajustando la fracción.
        if ($fraction >= 1) {
            $fraction -= 1;
            $currentIndex += 1;
        }
    }
}

dd(json_encode($loops));
