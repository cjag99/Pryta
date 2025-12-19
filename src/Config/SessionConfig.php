<?php

/**
 * Configuración y gestión de sesiones.
 *
 * - Configura los parámetros de la cookie de sesión (lifetime, path, domain, secure, httponly, samesite)
 * - Inicia la sesión y aplica comprobaciones de caducidad y regeneración
 *
 * Nota: Ajusta 'domain' y 'lifetime' según el entorno (desarrollo/producción).
 */

session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => 'localhost',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict',
]);

// Iniciamos la sesión. IMPORTANTE: debe llamarse antes de enviar cualquier salida al navegador
// (headers ya enviados provocaría un error). En producción asegúrate de forzar 'secure' a true
// si tu sitio usa HTTPS y ajustar 'domain' según el dominio real.
session_start();

// Tiempo máximo de vida de la sesión en segundos. Tras este periodo, la sesión se destruye
// y el usuario es redirigido al login con una señal de expiración.
$session_max_lifetime = 7200;

if(!isset($_SESSION['session_created'])){
    // Guardamos la marca de tiempo de creación de la sesión
    $_SESSION['session_created'] = time();
}

// Comprobamos si la sesión ha superado el tiempo máximo y, si es así, la destruimos
// y redirigimos al usuario al formulario de login indicando que la sesión expiró.
// Si la sesión supera su tiempo máximo, la limpiamos y destruimos para seguridad.
// Observación: después de enviar la cabecera de redirección se ejecuta `exit()` para asegurar
// que no se continúe ejecutando código accidentalmente.
if(time()- $_SESSION['session_created']>= $session_max_lifetime){
    session_unset();
    session_destroy();
    header("Location: index.php?action=login&expired=1");
    exit();
}  

// Intervalo (segundos) tras el cual se regenera el id de sesión para reducir el riesgo de fixation
$regenerate_interval = 1200;

if(!isset($_SESSION['last_regeneration'])){
    // Guardamos la última regeneración de id
    $_SESSION['last_regeneration'] = time();
}


if(time() - $_SESSION['last_regeneration']>= $regenerate_interval){
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} 


// Aseguramos que exista un token CSRF en la sesión para proteger los formularios.
if(empty($_SESSION['csrf_token'])){
    $random_bytes = openssl_random_pseudo_bytes(64);
    $csrf_token = bin2hex($random_bytes);
    // Guardamos el token en la sesión
    $_SESSION['csrf_token'] = $csrf_token;
}