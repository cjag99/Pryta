<?php

/**
 * Servicio de autenticación: control de intentos de login y bloqueo temporal.
 *
 * - `MAX_LOGIN_ATTEMPTS`: número máximo de intentos permitidos antes del bloqueo.
 * - `LOCKOUT_TIME`: tiempo de bloqueo en segundos (por defecto 900s = 15min).
 *
 * El servicio usa variables de sesión para llevar el control:
 * - `$_SESSION['login_attempts']`: contador de intentos fallidos
 * - `$_SESSION['first_attempt']`: timestamp del primer intento del periodo de bloqueo
 *
 * Nota: este enfoque es simple y funciona en una sola instancia de servidor. Para
 * entornos distribuidos o con múltiples servidores considera persistir este estado
 * en una base de datos o en un store compartido (redis) para evitar inconsistencias.
 */
class AuthService
{
    const MAX_LOGIN_ATTEMPTS = 5;
    const LOCKOUT_TIME = 900; // segundos (15 minutos)

    /**
     * Comprueba si el usuario está bloqueado por demasiados intentos de login.
     *
     * Lógica:
     * - Inicializa el contador y la marca temporal si no existen en la sesión.
     * - Si los intentos alcanzan el máximo, calcula el tiempo transcurrido desde
     *   el primer intento; si aún estamos dentro del periodo de bloqueo, devuelve
     *   un array con 'blocked' => true y el tiempo estimado en minutos que queda.
     * - Si el periodo de bloqueo ya pasó, resetea los intentos y permite continuar.
     *
     * @return array { 'blocked' => bool, 'message' => string, 'blocked_time' => int }
     */
    public static function checkLoginAttempts()
    {

        // Inicializamos contador y marca temporal si no existen
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['first_attempt'] = time();
        }

        // Si alcanzamos el número máximo de intentos, comprobamos el tiempo de bloqueo
        if ($_SESSION['login_attempts'] >= self::MAX_LOGIN_ATTEMPTS) {
            $time_passed = time() - $_SESSION['first_attempt'];
            if ($time_passed < self::LOCKOUT_TIME) {
                // Calculamos minutos restantes y devolvemos el estado bloqueado
                $blocked_time = ceil((self::LOCKOUT_TIME - $time_passed) / 60);
                return [
                    'blocked' => true,
                    'message' => "Demasiados intentos. Espere {$blocked_time} minutos e inténtelo de nuevo",
                    'blocked_time' => $blocked_time
                ];
            } else {
                // Se ha cumplido el periodo de bloqueo: reseteamos para permitir nuevos intentos
                self::resetLoginAttempts();
            }
        }

        // No bloqueado: devolvemos valores por defecto
        return [
            'blocked' => false,
            'message' => "",
            'blocked_time' => 0
        ];
    }

    /**
     * Reinicia el contador de intentos de inicio de sesión.
     * Se usa tras superar el periodo de bloqueo o después de un login exitoso.
     */
    public static function resetLoginAttempts()
    {
        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['first_attempt']);
    }

    /**
     * Incrementa el contador de intentos fallidos. Si no existe el contador,
     * lo inicializa y marca el primer intento con la hora actual.
     */
    public static function incrementLoginAttempts()
    {
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['first_attempt'] = time();
        }

        $_SESSION['login_attempts']++;
    }

    /**
     * Verifica si el usuario autenticado tiene el rol especificado.
     *
     * @param string $role rol a verificar
     * @return bool true si el usuario autenticado tiene el rol, false en caso contrario
     */
    public static function hasRole($role): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['role']) && $_SESSION['role'] === $role;
    }

    /**
     * Requiere que el usuario autenticado tenga el rol especificado.
     * Si no se cumple con esta condición, se redirige al inicio de sesión y se
     * muestra un mensaje de error.
     *
     * @param string $role rol a verificar
     */
    public static function requireRole($role): void
    {
        if (!self::hasRole($role)) {
            $_SESSION['ERROR'] = "<strong>ERROR: </strong>No tienes permiso para acceder a esta sección.";
            header("Location: index.php?controller=auth&action=logout");
            exit();
        }
    }
}
