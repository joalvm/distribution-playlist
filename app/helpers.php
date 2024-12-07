<?php

if (!function_exists('file_contents')) {
    /**
     * Obtiene el contenido de un archivo json.
     */
    function file_contents(string $path): array
    {
        return json_decode(
            file_get_contents(ROOT_PATH . '/' . $path),
            true
        ) ?? [];
    }
}
