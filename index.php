<?php

/**
 * Punto de entrada de la aplicación.
 * - Inicia la sesión, carga dependencias y delega la acción al controlador de autenticación.
 */

// Iniciar sesión (necesario para usar $_SESSION)
session_start();

// Cargar configuración y clases necesarias
require_once "./src/Config/Database.php";
require_once "./src/Model/Repositories/UserRepository.php";
require_once "./src/Controller/AuthController.php";
require_once "./src/Config/load_env.php";
loadEnv();
// Crear instancia de la base de datos y repositorios (inyección de dependencias)
$database = Database::getInstance($_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'] ?? '', $_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_NAME']);
$userRepository = new UserRepository($database);
$authController = new AuthController($userRepository);
// Ruteo básico según el parámetro 'action' de la query string
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'login':
            // Mostrar formulario de login
            $authController->login();
            break;
        case 'authenticate':
            // Procesar inicio de sesión
            $authController->authenticate();
            break;
        case 'dashboard':
            // Mostrar panel del usuario
            $authController->home();
            break;
        case 'logout':
            // Cerrar sesión
            $authController->logout();
            break;
        case 'register':
            // Mostrar formulario de registro
            $authController->register();
            break;
        case 'register_user':
            // Procesar envío de registro
            $authController->processRegistration();
            break;
        default:
            // Acción desconocida
            echo "Acción no válida.";
            break;
    }
} else {
    // Por defecto mostramos el formulario de login
    $authController->login();
}
