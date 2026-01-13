<?php


/**
 * Carga las variables de entorno desde un archivo .env
 *
 * El archivo .env debe contener líneas en el formato "clave=valor".
 * Las líneas en blanco y las que comienzan con '#' son ignoradas.
 * Las cadenas entre comillas dobles o simples son interpretadas como cadenas literales.
 * Las cadenas que no estén entre comillas dobles o simples son interpretadas como variables.
 *
 * @param string $path Ruta del archivo .env
 * @throws Exception Si el archivo .env no existe
 */
function loadEnv(string $path = __DIR__ . '/../../.env'): void
{
    if (!file_exists($path)) {
        throw new Exception(".env no encontrado");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line == '' || str_starts_with($line, '#')) {
            continue;
        }

        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');

        $key = trim($key);
        $value = trim($value);
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }

        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}
