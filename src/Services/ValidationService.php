<?php

/**
 * Servicio de validación y saneamiento de entradas de usuario.
 *
 * - `sanitizeInput`: limpieza básica para prevenir XSS al mostrar datos en HTML.
 *   No sustituye el uso de sentencias preparadas para prevenir SQL Injection.
 * - `validatePassword`: valida la complejidad mínima de contraseñas y devuelve errores legibles.
 * - `validateUserName`: validación simple de longitud del nombre de usuario.
 */
class ValidationService{
    /**
     * Saneamiento básico de una entrada para su salida en HTML.
     *
     * Pasos realizados:
     * - `trim()` para eliminar espacios alrededor
     * - `stripslashes()` para eliminar backslashes añadidos (compatibilidad)
     * - `htmlspecialchars()` para escapar caracteres HTML (prevención XSS)
     *
     * IMPORTANTE: Este método está pensado para escapar datos antes de mostrarlos en
     * la interfaz. Para prevenir inyección SQL usa siempre consultas preparadas (prepared statements).
     *
     * @param string $data Entrada del usuario
     * @return string Entrada saneada
     */
    public static function sanitizeInput($data){
        // Quita espacios al inicio y al final
        $data = trim($data);
        // Elimina backslashes (por si existen)
        $data = stripslashes($data);
        // Escapa caracteres especiales para salida HTML (UTF-8)
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    /**
     * Valida la complejidad de una contraseña según reglas definidas.
     *
     * Reglas verificadas (documentación de cada comprobación):
     * - Longitud: entre 8 y 15 caracteres.
     *   - `strlen($passwd)<8 || strlen($passwd)>15`
     * - Prohibición de caracteres problemáticos: `' " \\ / < > = ( )`.
     *   - Regex: `/[\'\"\\\/\<\>=\(\)]/` busca cualquiera de esos caracteres.
     *   - Motivo: evitar caracteres que puedan interferir en ciertos contextos o ser usados maliciosamente.
     * - Mayúsculas: al menos una letra A-Z (`/[A-Z]/`).
     * - Minúsculas: al menos una letra a-z (`/[a-z]/`).
     * - Dígitos: al menos un número 0-9 (`/[0-9]/`).
     * - Caracter especial: al menos uno del conjunto `!@#$%^&*_+-[]{};:,.?`.
     *   - Regex: `/[!@#$%^&*_+=\-\[\]{};:,.?]/`
     *
     * Devuelve un array con las claves:
     * - 'valid' => bool
     * - 'errors' => array Mensajes legibles por el usuario
     *
     * NOTA: Ajusta estas reglas según tu política de seguridad; por ejemplo, permitir
     * contraseñas más largas es recomendable (ej. 64 caracteres) si se usan passphrases.
     *
     * @param string $passwd Contraseña a validar (texto plano)
     * @return array Resultado con validez y mensajes de error
     */
    public static function validatePassword($passwd){
        $errors = [];

        // Regla: longitud mínima y máxima
        if(strlen($passwd)<8 || strlen($passwd)>15){
            $errors[] = "La contraseña debe tener entre 8 y 15 caracteres";
        }

        // Regla: prohibimos ciertos caracteres que podrían ser problemáticos
        // (comillas, barras, signos menores/mayores, paréntesis, etc.)
        if (preg_match('/[\'\"\\\\\/\<\>=\(\)]/', $passwd)) {
            $errors[] = "La contraseña no puede contener: ' \" \\ / < > = ( )";
        }

        // Regla: al menos una letra mayúscula
        if (!preg_match('/[A-Z]/', $passwd)) {
            $errors[] = "La contraseña debe contener al menos una mayúscula";
        }

        // Regla: al menos una letra minúscula
         if (!preg_match('/[a-z]/', $passwd)) {
            $errors[] = "La contraseña debe contener al menos una minúscula";
        }

        // Regla: al menos un dígito
         if (!preg_match('/[0-9]/', $passwd)) {
            $errors[] = "La contraseña debe contener al menos un número";
        }

        // Regla: al menos un carácter especial del conjunto permitido
        if (!preg_match('/[!@#$%^&*_+=\-\[\]{};:,.?]/', $passwd)) {
            $errors[] = "La contraseña debe contener al menos un carácter especial: !@#\$%^&*_+-[]{}:,.?";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Valida el nombre de usuario según las reglas simples definidas.
     *
     * Actualmente solo comprueba la longitud (8-15 caracteres). Considerar validar
     * caracteres permitidos (letras, números, guiones) según política.
     *
     * @param string $username
     * @return array Resultado con 'valid' y 'errors'
     */
    public static function validateUserName($username){
        $errors = [];
        if(strlen($username)< 8 || strlen($username)>15){
            $errors[] = "El nombre de usuario debe contener entre 8 y 15 caracteres";
        }
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}