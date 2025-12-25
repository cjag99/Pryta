<?php

/**
 * Excepción específica para errores relacionados con usuarios.
 *
 * - Extiende de Exception para permitir lanzar errores del dominio 'User'.
 * - El método `errorMessage()` devuelve el mensaje adecuado para mostrar en la UI.
 *
 * NOTA: `getMessage()` puede contener información técnica; en entornos de producción
 * conviene devolver un mensaje genérico al usuario y registrar la información
 * técnica en logs para no filtrar datos sensibles.
 */
class UserException extends Exception{

    /**
     * Devuelve un mensaje apto para mostrar al usuario.
     *
     * @return string Mensaje de error (seguro para la interfaz)
     */
    public function errorMessage(): string{
        return $this->getMessage();
    }

    // Opcional: implementar __toString() o métodos adicionales para facilitar el registro (logs).
}