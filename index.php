<?php
/**
 * Punto de entrada de la aplicación.
 * - Inicia la sesión, carga dependencias y delega la acción al controlador de autenticación.
 */

// Iniciar sesión (necesario para usar $_SESSION)
session_start();

// Cargar configuración y clases necesarias
include_once "./src/Config/Database.php";
include_once "./src/Model/Repositories/UserRepository.php";
include_once "./src/Controller/AuthController.php";

// Crear instancia de la base de datos y repositorios (inyección de dependencias)
$database = Database::getInstance('root', '');
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
