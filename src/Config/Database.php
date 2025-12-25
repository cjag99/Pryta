<?php

/**
 * Clase para gestionar la conexión a la base de datos mediante PDO.
 *
 * - Contiene el DSN (Data Source Name) y un método para obtener una conexión.
 * - Evita guardar credenciales en el repositorio; usa variables de entorno en producción.
 */
class Database {
    // Data Source Name: indica host, base de datos y charset.
    // Ajusta estos valores según el entorno y evita dejar credenciales en el código fuente.
    private static $dsn = "mysql:host=localhost;dbname=pryta;charset=utf8mb4";
     private static ?PDO $instance = null;

    
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
    public static function getInstance(
        string $user,
        string $password,
        string $host = '127.0.0.1',
        int $port = 3307,
        string $dbname = 'pryta'
    ): PDO {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("ERROR DE CONEXIÓN: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}