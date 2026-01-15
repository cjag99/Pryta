<?php

/**
 * Punto de entrada de la aplicación.
 * - Inicia la sesión, carga dependencias y delega la acción al controlador de autenticación.
 */
require_once "./src/Config/SessionConfig.php";
// Cargar configuración y clases necesarias
require_once "./src/Config/Database.php";
require_once "./src/Model/Repositories/UserRepository.php";
require_once "./src/Controller/AuthController.php";
require_once "./src/Controller/DashboardController.php";
require_once "./src/Config/load_env.php";
loadEnv();
// Crear instancia de la base de datos y repositorios (inyección de dependencias)
$connection = Database::getInstance($_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'] ?? '', $_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_NAME']);
$userRepository = new UserRepository($connection);

//Instanciar controladores
$controllers = [
    'auth' => new AuthController($userRepository),
    'dashboard' => new DashboardController($connection),
];

//Lista blanca de accions por controlador
$routes = [
    'auth' => ['login', 'authenticate', 'register', 'register_user', 'home', 'logout'],
    'dashboard' => ['list'],
];

//Valores de controlador y acción por defecto
$controllerName = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

//Validar controladores
if (!isset($controllers[$controllerName])) {
    $_SESSION['ERROR'] = "<strong>ERROR: </strong>El controlador $controllerName no existe.";
    $controllerName = 'auth';
    $action = 'login';
}

//Validar acciones
if (!in_array($action, $routes[$controllerName])) {
    $_SESSION['ERROR'] = "<strong>ERROR: </strong>La accion $action no existe en el controlador $controllerName.";
}

// Protección: solo usuarios logueados pueden dashboard
if ($controllerName === 'dashboard' && !isset($_SESSION['user'])) {
    $_SESSION['ERROR'] = "<strong>ERROR: </strong>Acceso denegado. Debes iniciar sesión para entrar en el sitio.";
    $controllerName = 'auth';
    $action = 'login';
}

// Ejecutar la acción
$controllers[$controllerName]->$action();
