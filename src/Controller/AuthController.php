<?php
include_once "./src/Model/Repositories/UserRepository.php";
include_once "./src/Services/ValidationService.php";
include_once "./src/Services/AuthService.php";

/**
 * Controlador de autenticación y registro.
 *
 * Métodos principales:
 * - login(): muestra el formulario de inicio de sesión.
 * - authenticate(): procesa el intento de inicio de sesión.
 * - register(): muestra el formulario de registro.
 * - processRegistration(): procesa el registro de nuevos usuarios.
 * - home(), logout(): acceso al dashboard y cierre de sesión.
 */
class AuthController{
    private UserRepository $userRepository;
    /**
     * Constructor: inyecta el repositorio de usuarios.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    /**
     * Mostrar formulario de login.
     */
    public function login(){
        include __DIR__ . "/../Views/Auth/login.php";
    }

    /**
     * Procesa el envío del formulario de login.
     * - Valida datos, comprueba bloqueo por intentos y autentica usuario.
     * - En caso de éxito redirige al dashboard; en fallo vuelve al formulario con error.
     */
    public function authenticate(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $username = ValidationService::sanitizeInput($_POST['username'] ?? '');
            // No aplicar escape HTML a las contraseñas antes de verificar; usar el valor enviado (solo trim).
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            // Comprobar si la cuenta está bloqueada antes de intentar iniciar sesión
            $attemptStatus = AuthService::checkLoginAttempts();
            if ($attemptStatus['blocked']) {
                $_SESSION['ERROR'] = $attemptStatus['message'];
                include __DIR__ . "/../Views/Auth/login.php";
                return;
            }

            // Intentar autenticar usando el repositorio de usuarios
            $user = $this->userRepository->login($username, $password);

            if ($user) {
                // Validación adicional del lado servidor para asegurar formato correcto
                if(!ValidationService::validateUserName($username)){
                    $_SESSION['ERROR'] = "Nombre de usuario inválido.";
                    include __DIR__ . "/../Views/Auth/login.php";
                    return;
                } else if (!ValidationService::validatePassword($password)){
                    $_SESSION['ERROR'] = "Contraseña inválida.";
                    include __DIR__ . "/../Views/Auth/login.php";
                    return;
                }
                $_SESSION['user_id'] = $user->getId();
                // Para compatibilidad con las vistas existentes
                $_SESSION['LOGGED'] = true;
                $_SESSION['USER'] = $user->getUsername();
                AuthService::resetLoginAttempts();
                // Redirigir a la acción del dashboard mapeada (index.php gestiona 'dashboard')
                header('Location: index.php?action=dashboard');
                exit();
            } else {
                // Incrementar contador de intentos y devolver error
                AuthService::incrementLoginAttempts();
                $_SESSION['ERROR'] = "Credenciales inválidas. Inténtelo de nuevo.";
                include __DIR__ . "/../Views/Auth/login.php";
            }
        } else {
            header('Location: index.php?action=login');
            exit();
        }
    }

    /**
     * Mostrar la vista del dashboard (requiere sesión iniciada).
     * Si no hay sesión, se redirige al login.
     */
    public function home(){
        if(!isset($_SESSION['user_id'])){
            // Verificar bloqueos por intentos antes de redirigir
            AuthService::checkLoginAttempts();
            header('Location: index.php?action=login');
            exit();
        }
        include __DIR__ . "/../Views/Dashboard/home.php";
    }

    /**
     * Cerrar sesión del usuario y redirigir al login.
     */
    public function logout(){
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit();
    }

    /**
     * Mostrar formulario de registro.
     */
    public function register(){
        include __DIR__ . "/../Views/Auth/register.php";
    }

    /**
     * Procesa el envío del formulario de registro.
     * - Valida campos mínimos y crea el usuario si todo es correcto.
     */
    public function processRegistration(){
        // Solo aceptamos POST
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Comprobar y sanear campos enviados
            if(isset($_POST['username'])){
                $username = ValidationService::sanitizeInput($_POST['username']);
                // Validaciones básicas
                if(!ValidationService::validateUserName($username)){
                    $_SESSION['ERROR'] = "Nombre de usuario inválido.";
                    include __DIR__ . "/../Views/Auth/register.php";
                    return;
                }
                if(isset($_POST['password'])){
                    $password = trim($_POST['password']);
                    if(!ValidationService::validatePassword($password)){
                        $_SESSION['ERROR'] = "Contraseña inválida.";
                        include __DIR__ . "/../Views/Auth/register.php";
                        return;
                    }
                }
                if(isset($_POST['confirm_password'])){
                    $confirmPassword = trim($_POST['confirm_password']);
                    if($password !== $confirmPassword){
                        $_SESSION['ERROR'] = "Las contraseñas no coinciden.";
                        include __DIR__ . "/../Views/Auth/register.php";
                        return;
                    }
                }
                if(isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email'])){
                    $name = ValidationService::sanitizeInput($_POST['name']);
                    $surname = ValidationService::sanitizeInput($_POST['surname']);
                    $email = ValidationService::sanitizeInput($_POST['email']);
                    // Crear entidad de usuario y guardarla en el repositorio
                    $user = new User(0, $username, $name, $surname, $password, $email);
                    $this->userRepository->insert($user);
                    header('Location: index.php?action=login');
                    exit();
                }
               
            }
        } else {
            header('Location: index.php?action=register');
            exit();
        }
    }
}