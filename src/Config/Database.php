<?php

/**
 * Clase para gestionar la conexión a la base de datos mediante PDO.
 *
 * - Contiene el DSN (Data Source Name) y un método para obtener una conexión.
 * - Evita guardar credenciales en el repositorio; usa variables de entorno en producción.
 *
 * TODO: Considerar un patrón singleton público o un método que gestione conexiones reusables.
 */
class Database {
    // Data Source Name: indica host, base de datos y charset.
    // Ajusta estos valores según el entorno y evita dejar credenciales en el código fuente.
    private static $dsn = "mysql:host=localhost;dbname=pryta;charset=utf8mb4";

    
    /**
     * Crea y devuelve una instancia de PDO.
     *
     * @param string $username Usuario de la base de datos
     * @param string $password Contraseña de la base de datos
     * @return PDO|null Devuelve la conexión o null si ocurre un error
     *
     * NOTAS:
     * - Las opciones de PDO deben pasarse como array asociativo: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
     * - Actualmente las excepciones se registran y no se propagan; esto evita que información se pierda
     *   pero puede ocultar fallos durante el desarrollo. Considerar relanzar la excepción o lanzar una propia.
     */
    private static function getInstance($username, $password){
        $connection = null;
        try{
            // Opciones recomendadas para PDO (usar array asociativo)
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            $connection = new PDO(Database::$dsn, $username, $password, $options);
        } catch (PDOException $e) {
            // TODO: Registrar la excepción en un sistema de logging más robusto si está disponible
            error_log('Database connection error: ' . $e->getMessage());
            // Opcional: relanzar la excepción para que el caller la gestione
            // throw $e;
        }
        return $connection;
    }
}