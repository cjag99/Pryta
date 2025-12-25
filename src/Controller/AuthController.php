<?php
include_once "./src/Model/Repositories/UserRepository.php";
include_once "./src/Services/ValidationService.php";
include_once "./src/Services/AuthService.php";
class AuthController{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function login(){
        include __DIR__ . "/../Views/Auth/login.php";
    }

    public function authenticate(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $username = ValidationService::sanitizeInput($_POST['username'] ?? '');
            // Do NOT apply HTML escaping to passwords before verification — use the raw posted value (trimmed).
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            // Check for lockout status before attempting login
            $attemptStatus = AuthService::checkLoginAttempts();
            if ($attemptStatus['blocked']) {
                $_SESSION['ERROR'] = $attemptStatus['message'];
                include __DIR__ . "/../Views/Auth/login.php";
                return;
            }

            $user = $this->userRepository->login($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user->getId();
                // For compatibility with existing views
                $_SESSION['LOGGED'] = true;
                $_SESSION['USER'] = $user->getUsername();
                AuthService::resetLoginAttempts();
                // Redirect to the mapped dashboard action (index.php handles 'dashboard')
                header('Location: index.php?action=dashboard');
                exit();
            } else {
                AuthService::incrementLoginAttempts();
                $_SESSION['ERROR'] = "Credenciales inválidas. Inténtelo de nuevo.";
                include __DIR__ . "/../Views/Auth/login.php";
            }
        } else {
            header('Location: index.php?action=login');
            exit();
        }
    }

    public function home(){
        if(!isset($_SESSION['user_id'])){
            AuthService::checkLoginAttempts();
            header('Location: index.php?action=login');
            exit();
        }
        include __DIR__ . "/../Views/Dashboard/home.php";
    }

    public function logout(){
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit();
    }

}