<?php

/**
 * Servicio para validar tokens CSRF (Falsificación de petición cruzada, CSRF).
 *
 * - Valida el token enviado por formularios contra el token almacenado en la sesión.
 * - Utiliza `hash_equals` para comparación en tiempo constante y mitigar ataques por temporización.
 * - Requiere que la sesión esté iniciada y que el token de sesión se haya generado
 *   con suficiente entropía (p. ej. `random_bytes()` o `openssl_random_pseudo_bytes()`).
 * - Recomendación: aplicar esta validación en todos los endpoints que procesan formularios (POST)
 *   y regenerar el token cuando sea necesario para mayor seguridad.
 */
class CSRFService{

    /**
     * Valida el token CSRF enviado vía POST frente al almacenado en la sesión.
     *
     * @return bool True si el token existe y coincide, false en caso contrario.
     * - No lanza excepciones; el llamador debe manejar el resultado y rechazar la petición cuando devuelva false.
     */
    public static function validateCSRFToken(){
        // Comprobamos que el token esté presente tanto en POST como en la sesión
        if(!isset($_POST['csrf_token'])|| !isset($_SESSION['csrf_token'])){
            return false;
        }
        // hash_equals realiza una comparación en tiempo constante (segura frente a ataques por temporización)
        return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }
}