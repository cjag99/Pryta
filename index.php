<?php
session_start();
include_once "./src/Config/Database.php";
include_once "./src/Model/Repositories/UserRepository.php";
include_once "./src/Controller/AuthController.php";
$database = Database::getInstance('root', '');
$userRepository = new UserRepository($database);
$authController = new AuthController($userRepository);
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'login':
            $authController->login();
            break;
        case 'authenticate':
            $authController->authenticate();
            break;
        case 'dashboard':
            $authController->home();
            break;
        case 'logout':
            $authController->logout();
            break;
        default:
            echo "Acción no válida.";
            break;
    }
} else {
    $authController->login();
}
