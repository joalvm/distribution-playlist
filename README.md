# Playlist DOOH

## Descripción del Proyecto

Este proyecto está diseñado para generar archivos `CSV` que indican los momentos exactos en los que se deben reproducir las artes publicitarias en unidades digitales DOOH (Digital Out-Of-Home). El objetivo principal es dispersar de manera optimizada y equitativa las reproducciones de cada arte a lo largo del horario configurado para cada día.

## Características Clave

- **Generación de archivos CSV:** El sistema produce archivos compatibles con las aplicaciones instaladas en las pantallas DOOH.
- **Optimización de distribución:** Las artes se distribuyen de manera equitativa en base a su cantidad de reproducciones y el tiempo disponible.
- **Compatibilidad configurada:** El proyecto permite definir horarios, rangos de reproducción y tipos de artes para cada unidad.
- **Soporte para diferentes tipos de artes:** Manejo de carouseles, videos, imágenes y secuencias.

## Estructura del Proyecto

### Carpetas Principales

- **`app/`**: Contiene la lógica principal del proyecto.
  - **`Generator.php`**: Clase principal encargada de la generación de los archivos de reproducción.
  - **`Playlist.php`**: Representa una lista de reproducción.
  - **`Components/`**: Módulos específicos del sistema.
    - **`art/`**: Maneja la lógica relacionada con los artes.
    - **`Programation/`**: Define y gestiona programaciones específicas.
    - **`Schedule/`**: Manejo de horarios y rangos de tiempo.
  - **`Enums/`**: Enumeraciones utilizadas en el proyecto.

- **`resources/`**: Data fake utilizada para pruebas.
  - **`default_art/`**: Ejemplos de configuraciones por defecto para diferentes tipos de artes.
  - **`programation/`**: Ejemplos de programaciones preconfiguradas.
  - **`schedule/`**: Ejemplos de horarios configurables.

Esta carpeta contiene múltiples archivos diseñados para simular diferentes escenarios. En producción, la información real se obtiene de la base de datos y debe estructurarse siguiendo las definiciones establecidas en las interfaces descritas a continuación.

- **`index.php`**: Punto de entrada para ejecutar el sistema.
- **`composer.json`**: Configuración del proyecto PHP y sus dependencias.

## Entidades Principales

### Clases e Interfaces

- **`Generator`**:
  Responsable de coordinar la generación de los archivos CSV, utilizando las configuraciones de programación y horarios.

- **`Playlist`**:
  Representa una lista de reproducción que contiene un conjunto de artes programados con sus respectivos horarios.

- **`Art`**:
  Define los detalles de un arte, incluyendo su duración, tipo y cantidad de reproducciones requeridas.

- **`Schedule`**:
  Maneja la configuración de horarios disponibles para cada unidad.

- **`Programation`**:
  Permite definir reglas específicas para la programación de artes.

### Interfaces para Recursos

A continuación, se describen las estructuras esperadas para cada tipo de recurso que debe ser proporcionado desde la base de datos:

#### Esquema de los recursos
```typescript
type Day = 'monday' | 'tuesday' | 'wednesday' | 'thursday' | 'friday' | 'saturday' | 'sunday';

type ArtType = 'carousel' | 'sequence' | 'video' | 'image' | 'html';

type DistributionType = "immutable" | "editable" | 'deleteable';

interface ArtItem {
    id: number;
    type: Omit<ArtType, 'carousel' | 'sequence'>
    path: string;
    duration: number; // Duración en segundos
    position: number; // Orden en el carrusel
}

interface Art {
    id: number;
    type: ArtType;
    items: Array<ArtItem>;
}

// Arte por defecto.
type DefaultArt = Art;

// Horario
interface Schedule {
  id: number;
  enabled: boolean; // Si el horario está activo
  days: {
    [T in Day]?: Array<{
      start: string; // Ejemplo: "06:00:00"
      end: string; // Ejemplo: "14:00:00"
    }>;
  };
}

// Programación
interface Programation {
  start_date: string; // Fecha de inicio (YYYY-MM-DD)
  finish_date: string; // Fecha de fin (YYYY-MM-DD)
  art: ProgramationArt;
  custom_schedule: Array<CustomSchedule> | null;
}

type ProgramationArt = Art & {
  total_reproductions: number; // Reproducciones requeridas
  distribution_type: DistributionType;
};

interface CustomSchedule {
  start_time: string; // Ejemplo: "16:00:00"
  finish_time: string; // Ejemplo: "20:00:00"
  days: Day[]; // Ejemplo: ["monday", "tuesday"]
}
```

### Enumeraciones

- **`ArtType`**: Define los tipos de artes disponibles (video, imagen, secuencia, carousel).
- **`DistributionType`**: Especifica los modos de distribución de los artes.

## Resultado Esperado

El sistema genera un archivo `CSV` con el siguiente formato:

```csv
art_id,type,duration,start_time,distribution
1,video,30,00:00:00,immutable
2,image,10,00:00:30,immutable
...
```

## Requisitos del Sistema

- **PHP 8.2** o superior.
- **Composer** para la gestión de dependencias.
- Extensiones necesarias: `json`, `mbstring`.
