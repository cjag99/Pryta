<?php
require_once "./src/Model/Repositories/UserRepository.php";
require_once "./src/Services/ValidationService.php";
require_once "./src/Services/AuthService.php";
require_once "./src/Services/CSRFService.php";

/**
 * Controlador de autenticación y registro.
 *
 * Métodos principales:
 * - login(): muestra el formulario de inicio de sesión.
 * - authenticate(): procesa el intento de inicio de sesión.
 * - register(): muestra el formulario de registro.
 * - processRegistration(): procesa el registro de nuevos usuarios.
 * - home(), logout(): acceso al dashboard y cierre de sesión.
 * - profile(): muestra el perfil del usuario logueado.
 * - updateProfile(): actualiza el perfil del usuario logueado.
 */
class AuthController
{
    private UserRepository $userRepository;
    /**
     * Constructor: inyecta el repositorio de usuarios.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Mostrar formulario de login.
     */
    public function login()
    {
        include "./src/Views/Auth/login.php";
    }

    /**
     * Procesa el envío del formulario de login.
     * - Valida datos, comprueba bloqueo por intentos y autentica usuario.
     * - En caso de éxito redirige al dashboard; en fallo vuelve al formulario con error.
     */
    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!CSRFService::validateCSRFToken()) {
                $_SESSION['ERROR'] = "<strong>ERROR:</strong> Token CSRF inválido.";
                $this->login();
                exit;
            }
            $username = ValidationService::sanitizeInput($_POST['username'] ?? '');
            // No aplicar escape HTML a las contraseñas antes de verificar; usar el valor enviado (solo trim).
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            // Comprobar si la cuenta está bloqueada antes de intentar iniciar sesión
            $attemptStatus = AuthService::checkLoginAttempts();
            if ($attemptStatus['blocked']) {
                $_SESSION['ERROR'] = $attemptStatus['message'];
                $this->login();
                return;
            }

            // Intentar autenticar usando el repositorio de usuarios
            $user = $this->userRepository->login($username, $password);

            if ($user) {
                // Validación adicional del lado servidor para asegurar formato correcto
                if (!ValidationService::validateUserName($username)) {
                    $_SESSION['ERROR'] = "Nombre de usuario inválido.";
                    $this->login();
                    return;
                } else if (!ValidationService::validatePassword($password)) {
                    $_SESSION['ERROR'] = "Contraseña inválida.";
                    $this->login();
                    return;
                }
                $_SESSION['user_id'] = $user->getId();
                // Para compatibilidad con las vistas existentes
                $_SESSION['LOGGED'] = true;
                $_SESSION['USER'] = $user->getUsername();
                $_SESSION['role'] = $user->getRole();
                AuthService::resetLoginAttempts();
                // Redirigir a la acción del dashboard mapeada (index.php gestiona 'dashboard')
                header('Location: index.php?controller=auth&action=home');
                exit();
            } else {
                // Incrementar contador de intentos y devolver error
                AuthService::incrementLoginAttempts();
                $_SESSION['ERROR'] = "Credenciales inválidas. Inténtelo de nuevo.";
                $this->login();
            }
        } else {
            $this->login();
            exit();
        }
    }

    /**
     * Mostrar la vista del dashboard (requiere sesión iniciada).
     * Si no hay sesión, se redirige al login.
     */
    public function home()
    {
        if (!isset($_SESSION['user_id'])) {
            // Verificar bloqueos por intentos antes de redirigir
            AuthService::checkLoginAttempts();
            $_SESSION['ERROR'] = "<strong>ERROR: </strong>No tienes permiso para acceder a esta sección.";
            $this->login();
            exit();
        }
        include __DIR__ . "/../Views/Dashboard/home.php";
    }

    /**
     * Cerrar sesión del usuario y redirigir al login.
     */
    public function logout()
    {
        // Iniciar sesión si no ha sido iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset(); // Destruir todas las variables de sesión
        $params = session_get_cookie_params(); // Obtener parámetros de la cookie de sesión
        // Destruir la cookie de sesión
        setcookie(
            session_name(),
            '',
            [
                'expires'  => time() - 42000,
                'path'     => $params['path'],
                'domain'   => $params['domain'],
                'secure'   => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => 'Strict',
            ]
        );
        session_destroy(); // Destruir la sesión
        header('Location: index.php?controller=auth&action=login'); // Redirigir al login
        exit();
    }

    /**
     * Mostrar formulario de registro.
     */
    public function register()
    {
        include __DIR__ . "/../Views/Auth/register.php";
    }

    /**
     * Procesa el envío del formulario de registro.
     * - Valida campos mínimos y crea el usuario si todo es correcto.
     */
    public function processRegistration()
    {
        // Solo aceptamos POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar token CSRF
            if (!CSRFService::validateCSRFToken()) {
                $_SESSION['ERROR'] = "<strong>ERROR:</strong> Token CSRF inválido.";
                $this->login(); // Reenviar a index 
                exit;
            }
            // Comprobar y sanear campos enviados
            if (isset($_POST['username'])) {
                $username = ValidationService::sanitizeInput($_POST['username']);
                // Validaciones básicas
                if (!ValidationService::validateUserName($username)) {
                    $_SESSION['ERROR'] = "Nombre de usuario inválido.";
                    $this->register();
                    return;
                }
                if (isset($_POST['password'])) {
                    $password = trim($_POST['password']);
                    if (!ValidationService::validatePassword($password)) {
                        $_SESSION['ERROR'] = "Contraseña inválida.";
                        $this->register();
                        return;
                    }
                }
                if (isset($_POST['confirm_password'])) {
                    $confirmPassword = trim($_POST['confirm_password']);
                    if ($password !== $confirmPassword) {
                        $_SESSION['ERROR'] = "Las contraseñas no coinciden.";
                        $this->register();
                        return;
                    }
                }
                if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email'])) {
                    $name = ValidationService::sanitizeInput($_POST['name']);
                    $surname = ValidationService::sanitizeInput($_POST['surname']);
                    $email = ValidationService::sanitizeInput($_POST['email']);
                    // Crear entidad de usuario y guardarla en el repositorio
                    $user = new User(0, $username, $name, $surname, $password, "Software Engineer", $email, false, true, null);
                    $this->userRepository->create($user);
                    header('Location: index.php?controller=auth&action=login');
                    exit();
                }
            }
        } else {
            $_SESSION['ERROR'] = "<strong>ERROR:</strong> Metodo no permitido.";
            header('Location: index.php?controller=auth&action=register');
            exit();
        }
    }

    /**
     * Muestra la página de perfil del usuario logueado.
     *
     * Comprueba si el usuario está logueado y en caso de no estarlo, redirige
     * a la página de inicio de sesión y destruye la sesión.
     *
     * En caso de que el usuario esté logueado, carga la vista de perfil con los
     * datos del usuario.
     *
     * @return void
     */
    public function profile()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['ERROR'] = "<strong>ERROR: </strong>No tienes permiso para acceder a esta sección.";
            // Reenviar a index y destruir sesion
            $this->login();
            exit();
        }
        $userInfo = $this->userRepository->readOne($_SESSION['user_id']);
        include __DIR__ . "/../Views/Auth/profile.php";
    }

    /**
     * Actualiza los datos del perfil del usuario logueado.
     *
     * Comprueba si el usuario está logueado y en caso de no estarlo, redirige
     * a la página de inicio de sesión y destruye la sesión.
     *
     * En caso de que el usuario esté logueado, carga la vista de perfil con los
     * datos del usuario y actualiza los datos del perfil si se recibieron parámetros
     * válidos en el formulario.
     *
     * @return void
     */
    public function updateProfile()
    {
        if (!isset($_SESSION['user_id'])) {
            // Reenviar a index y destruir sesion
            $this->login();
            exit();
        }
        $userInfo = $this->userRepository->readOne($_SESSION['user_id']); // Cargar los datos del usuario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar token CSRF
            if (!CSRFService::validateCSRFToken()) {
                $_SESSION['ERROR'] = "<strong>ERROR:</strong> Token CSRF inválido.";
                $this->login(); // Reenviar a index 
                exit;
            }
            // Comprobar y sanear campos enviados
            $username = ValidationService::sanitizeInput($_POST['username']);
            // Validar y actualizar la contraseña
            if (!empty($_POST['password']) && $_POST['password'] === $_POST['confirm_password']) {
                $passwd = trim(htmlspecialchars($_POST['password']));
            } else {
                $passwd = $userInfo->getPasswd();
            }
            (ValidationService::validateUserName($username)) ? $userInfo->setUsername($username) : $userInfo->setUsername($userInfo->getUsername());
            $userInfo->setName($_POST['name']);
            $userInfo->setSurname($_POST['surname']);
            $userInfo->setEmail($_POST['email']);
            $userInfo->setPasswd($passwd);
            try {
                $this->userRepository->updateProfile($userInfo); // Actualizar el perfil
            } catch (PDOException $e) {
                $_SESSION['ERROR'] = "<strong>ERROR:</strong> Permisos insuficientes.";
                $this->login();
                exit;
            }
        }
        $_SESSION['SUCCESS'] = "Perfil actualizado correctamente.";
        header('Location: index.php?controller=auth&action=profile'); // Redirigir a la vista de perfil
        exit;
    }
}
