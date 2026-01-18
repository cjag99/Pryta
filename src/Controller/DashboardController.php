<?php

declare(strict_types=1);
require_once "./src/Model/Entities/UserException.php";
require_once "./src/Services/ValidationService.php";
require_once "./src/Services/CSRFService.php";
/**
 * Controlador de la vista de dashboard.
 *
 * Este controlador se encarga de mostrar la vista de dashboard, que incluye
 * la lista de usuarios, equipos, proyectos y tareas.
 *
 * También se encarga de procesar las solicitudes de creación, actualización y eliminación
 * de registros en las tablas de usuarios, equipos, proyectos y tareas.
 */
class DashboardController
{
    /**
     * Constructor: recibe la conexión PDO (inyección de dependencias).
     * @param PDO $connection Conexión a la base de datos
     */
    public function __construct(
        private PDO $connection
    ) {}
    /**
     * Inicializa repositorios auxiliares para una tabla determinada.
     * 
     * Compara el nombre de la tabla con los repositorios auxiliares correspondientes y devuelve los mismos.
     * 
     * @param string $table_name El nombre de la tabla para comparar con los repositorios auxiliares
     * 
     * @return array Un array que contiene los repositorios auxiliares y sus datos correspondientes
     */
    private function launchAuxiliars($table_name)
    {
        $auxiliary_repositories = match ($table_name) {
            'user' => [new TeamRepository($this->connection)],
            'team' => [new UserRepository($this->connection)],
            'project' => [new TeamRepository($this->connection)],
            'task' => [new ProjectRepository($this->connection), new UserRepository($this->connection)],
        };
        $auxiliar_data = match ($table_name) {
            'user' => $auxiliary_repositories[0]->readIdNames(),
            'team' => $auxiliary_repositories[0]->readIdNames(),
            'project' => $auxiliary_repositories[0]->readIdNames(),
            'task' => [$auxiliary_repositories[0]->readIdNames(), $auxiliary_repositories[1]->readIdNames()],
        };
        return [$auxiliary_repositories, $auxiliar_data];
    }
    /**
     * Muestra todos los registros de la tabla seleccionada
     * 
     * @return void
     */
    public function list()
    {
        $allowed_tables = [
            'user' => 'user',
            'team' => 'team',
            'project' => 'project',
            'task' => 'task',
        ];

        $table_name = $_POST['table_name'] ?? $_SESSION['current_table'];

        if (!$table_name || !array_key_exists($table_name, $allowed_tables)) {
            $_SESSION['error'] = "<strong>ERROR:</strong> La tabla seleccionada no existe.";
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $_SESSION['current_table'] = $allowed_tables[$table_name];
        $repository = match ($_SESSION['current_table']) {
            'user' => new UserRepository($this->connection),
            'team' => new TeamRepository($this->connection),
            'project' => new ProjectRepository($this->connection),
            'task' => new TaskRepository($this->connection),
        };

        $data = $repository->readAll();
        $auxiliar_data = $this->launchAuxiliars($_SESSION['current_table'])[1];
        if (empty($data) && $_SESSION['current_table'] === 'user') {
            $_SESSION['error'] = "<strong>ERROR:</strong> No hay usuarios registrados en el sistema.";
            header("Location: index.php?controller=auth&action=login");
            exit();
        } else {
            require "./src/Views/Dashboard/list.php";
        }
    }


    /**
     * Crea un registro en la base de datos según la tabla actual.
     * Se valida el token CSRF y se comprueba que el usuario esté logueado.
     * Se utiliza la clase Repository correspondiente a la tabla actual
     * para crear el registro.
     *
     * @throws PDOException Si no hay permisos para crear el registro.
     * @throws Exception Si no se pudo realizar la operación.
     */
    public function create()
    {
        // Solo aceptamos POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar token CSRF
            if (!CSRFService::validateCSRFToken()) {
                $_SESSION['ERROR'] = "<strong>ERROR:</strong> Token CSRF inválido.";
                header("Location: index.php?controller=auth&action=login");
                exit;
            }
            $repository = match ($_SESSION['current_table']) {
                'user' => new UserRepository($this->connection),
                'team' => new TeamRepository($this->connection),
                'project' => new ProjectRepository($this->connection),
                'task' => new TaskRepository($this->connection),
            };
            switch ($_SESSION['current_table']) {
                case 'user':
                    $username = ValidationService::sanitizeInput($_POST['username']);
                    if (!ValidationService::validateUserName($username)) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Nombre de usuario inválido.";
                        $this->list();
                        return;
                    }
                    $passwd = trim(htmlspecialchars($_POST['password']));
                    if (!ValidationService::validatePassword($passwd)) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Contraseña inválida.";
                        $this->list();
                        return;
                    }
                    $team_id = isset($_POST['team_id']) && ValidationService::sanitizeInput($_POST['team_id']) !== ''
                        ? (int)ValidationService::sanitizeInput($_POST['team_id'])
                        : null;
                    $user = new User(
                        0,
                        $username,
                        ValidationService::sanitizeInput($_POST['name']),
                        ValidationService::sanitizeInput($_POST['surname']),
                        $passwd,
                        ValidationService::sanitizeInput($_POST['role']),
                        ValidationService::sanitizeInput($_POST['email']),
                        ValidationService::sanitizeInput($_POST['active']) === '1' ? true : false,
                        ValidationService::sanitizeInput($_POST['verified']) === '1' ? true : false,
                        $team_id
                    );
                    try {
                        $repository->create($user);
                    } catch (PDOException $e) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Permisos insuficientes.";
                        header("Location: index.php?controller=auth&action=login");
                        exit;
                    }
                    $_SESSION['current_table'] = 'user';
                    break;
                case 'team':
                    $team = new Team(
                        0,
                        ValidationService::sanitizeInput($_POST['reg_name']),
                        ValidationService::sanitizeInput($_POST['reg_description']),
                        new DateTimeImmutable(ValidationService::sanitizeInput($_POST['reg_creation_date'])),
                        (isset($_POST['reg_team_leader']) && ValidationService::sanitizeInput($_POST['reg_team_leader']) !== '')
                            ? (int)ValidationService::sanitizeInput($_POST['reg_team_leader'])
                            : null,
                    );
                    try {
                        $repository->create($team);
                    } catch (PDOException $e) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Permisos insuficientes.";
                        header("Location: index.php?controller=auth&action=login");
                        exit;
                    }
                    $_SESSION['current_table'] = 'team';
                    break;
                case 'project':
                    $project = new Project(
                        0,
                        ValidationService::sanitizeInput($_POST['reg_name']),
                        isset($_POST['reg_description']) && ValidationService::sanitizeInput($_POST['reg_description']) !== ''
                            ? ValidationService::sanitizeInput($_POST['reg_description'])
                            : null,

                        isset($_POST['reg_started_at']) && ValidationService::sanitizeInput($_POST['reg_started_at']) !== ''
                            ? new DateTimeImmutable(ValidationService::sanitizeInput($_POST['reg_started_at']))
                            : null,

                        isset($_POST['reg_due_date']) && ValidationService::sanitizeInput($_POST['reg_due_date']) !== ''
                            ? new DateTimeImmutable(ValidationService::sanitizeInput($_POST['reg_due_date']))
                            : null,

                        isset($_POST['reg_assigned_team']) && ValidationService::sanitizeInput($_POST['reg_assigned_team']) !== ''
                            ? (int) ValidationService::sanitizeInput($_POST['reg_assigned_team'])
                            : null
                    );
                    try {
                        $repository->create($project);
                    } catch (PDOException $e) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Permisos insuficientes.";
                        header("Location: index.php?controller=auth&action=login");
                        exit;
                    }
                    $_SESSION['current_table'] = 'project';
                    break;
                case 'task':
                    $task = new Task(
                        0,
                        ValidationService::sanitizeInput($_POST['reg_name']),
                        (int) $_POST['reg_project_id'],
                        isset($_POST['reg_description']) && ValidationService::sanitizeInput($_POST['reg_description']) !== ''
                            ? ValidationService::sanitizeInput($_POST['reg_description'])
                            : null,
                        "Not assigned",
                        isset($_POST['reg_started_at']) && ValidationService::sanitizeInput($_POST['reg_started_at']) !== ''
                            ? new DateTimeImmutable($_POST['reg_started_at'])
                            : null,
                        isset($_POST['reg_due_date']) && ValidationService::sanitizeInput($_POST['reg_due_date']) !== ''
                            ? new DateTimeImmutable($_POST['reg_due_date'])
                            : null,
                        isset($_POST['reg_member_assigned']) && ValidationService::sanitizeInput($_POST['reg_member_assigned']) !== ''
                            ? (int) ValidationService::sanitizeInput($_POST['reg_member_assigned'])
                            : null,

                    );
                    try {
                        $repository->create($task);
                    } catch (PDOException $e) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Permisos insuficientes.";
                        header("Location: index.php?controller=auth&action=login");
                        exit;
                    }
                    $_SESSION['current_table'] = 'task';
                    break;
                default:
                    $_SESSION['ERROR'] = "<strong>ERROR:</strong> No se ha podido crear el registro. Acceso denegado.";
                    header("Location: index.php?controller=auth&action=login");
                    exit;
            }
            $_SESSION['SUCCESS'] = "<strong>EXITO:</strong> Registro creado correctamente.";
            header("Location: index.php?controller=dashboard&action=list");
            exit;
        } else {
            $_SESSION['ERROR'] = "<strong>ERROR:</strong> No se ha podido crear el registro. Acceso denegado.";
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
    }

    /**
     * Actualiza un registro en la base de datos según la tabla actual.
     * Se valida el token CSRF y se comprueba que el usuario esté logueado.
     * Se utiliza la clase Repository correspondiente a la tabla actual
     * para actualizar el registro.
     *
     * @throws PDOException Si no hay permisos para actualizar el registro.
     * @throws Exception Si no se pudo realizar la operación.
     */
    public function update()
    {
        // Solo aceptamos POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar token CSRF
            if (!CSRFService::validateCSRFToken()) {
                $_SESSION['ERROR'] = "<strong>ERROR:</strong> Token CSRF inválido.";
                header("Location: index.php?controller=auth&action=login"); // Reenviar a index
                exit;
            }
            $repository = match ($_SESSION['current_table']) {
                'user' => new UserRepository($this->connection),
                'team' => new TeamRepository($this->connection),
                'project' => new ProjectRepository($this->connection),
                'task' => new TaskRepository($this->connection),
            };
            $currentRecord = $repository->readOne((int)ValidationService::sanitizeInput($_POST['send_id']));
            switch (true) {
                case $currentRecord instanceof User:
                    $username = ValidationService::sanitizeInput($_POST['new_username']);
                    if (!ValidationService::validateUserName($username)) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Nombre de usuario inválido.";
                        $this->list();
                        return;
                    }
                    if (!empty($_POST['new_password']) && $_POST['new_password'] === $_POST['new_confirm_password']) {
                        $passwd = trim(htmlspecialchars($_POST['new_password']));
                    } else {
                        $passwd = $currentRecord->getPasswd();
                    }
                    if (!ValidationService::validatePassword($passwd)) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Contraseña inválida.";
                        $this->list();
                        return;
                    }

                    try {
                        $currentRecord->setUsername($username);
                        $currentRecord->setName(ValidationService::sanitizeInput($_POST['new_name']));
                        $currentRecord->setSurname(ValidationService::sanitizeInput($_POST['new_surname']));
                        $currentRecord->setPasswd($passwd);
                        $currentRecord->setRole(ValidationService::sanitizeInput($_POST['new_role']));
                        $currentRecord->setEmail(ValidationService::sanitizeInput($_POST['new_email']));
                        $currentRecord->setActive(ValidationService::sanitizeInput($_POST['new_active']) === '1' ? true : false);
                        $currentRecord->setVerified(ValidationService::sanitizeInput($_POST['new_verified']) === '1' ? true : false);
                        $currentRecord->setTeamId(
                            isset($_POST['new_team_id']) && ValidationService::sanitizeInput($_POST['new_team_id']) !== ''
                                ? (int)ValidationService::sanitizeInput($_POST['new_team_id'])
                                : null
                        );


                        $repository->update($currentRecord);
                    } catch (UserException $e) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> " . $e->getMessage();
                        $this->list();
                        return;
                    } catch (PDOException $e2) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Permisos insuficientes.";
                        header("Location: index.php?controller=auth&action=login");
                        exit;
                    }
                    break;
                case $currentRecord instanceof Team:
                    try {
                        $currentRecord->setName(ValidationService::sanitizeInput($_POST['update_name']));
                        $currentRecord->setDescription(
                            isset($_POST['update_description']) && $_POST['update_description'] !== ''
                                ? ValidationService::sanitizeInput($_POST['update_description'])
                                : null
                        );
                        $currentRecord->setCreationDate(
                            isset($_POST['update_creation_date']) && $_POST['update_creation_date'] !== ''
                                ? new DateTimeImmutable(ValidationService::sanitizeInput($_POST['update_creation_date']))
                                : null
                        );
                        $currentRecord->setTeamLeader(
                            isset($_POST['update_team_leader']) && $_POST['update_team_leader'] !== ''
                                ? (int)ValidationService::sanitizeInput($_POST['update_team_leader'])
                                : null
                        );
                        $currentRecord->setIsAvailable(ValidationService::sanitizeInput($_POST['update_is_available']) === '1' ? true : false);
                        $repository->update($currentRecord);
                    } catch (Exception $e) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> No se pudo realizar la operación.";
                        $this->list();
                        return;
                    } catch (PDOException $e2) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Permisos insuficientes.";
                        header("Location: index.php?controller=auth&action=login");
                        exit;
                    }
                    break;
                case $currentRecord instanceof Project:
                    try {
                        $currentRecord->setName(ValidationService::sanitizeInput($_POST['update_name']));
                        $currentRecord->setDescription(
                            isset($_POST['update_description']) && $_POST['update_description'] !== ''
                                ? ValidationService::sanitizeInput($_POST['update_description'])
                                : null
                        );
                        $currentRecord->setStartedAt(
                            isset($_POST['update_started_at']) && $_POST['update_started_at'] !== ''
                                ? new DateTimeImmutable(ValidationService::sanitizeInput($_POST['update_started_at']))
                                : null
                        );
                        $currentRecord->setDueDate(
                            isset($_POST['update_due_date']) && $_POST['update_due_date'] !== ''
                                ? new DateTimeImmutable(ValidationService::sanitizeInput($_POST['update_due_date']))
                                : null
                        );
                        $currentRecord->setAssignedTeam(
                            isset($_POST['update_assigned_team']) && $_POST['update_assigned_team'] !== ''
                                ? (int) ValidationService::sanitizeInput($_POST['update_assigned_team'])
                                : null
                        );
                        $repository->update($currentRecord);
                    } catch (Exception $e) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> No se pudo realizar la operación.";
                        $this->list();
                        return;
                    } catch (PDOException $e2) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Permisos insuficientes.";
                        header("Location: index.php?controller=auth&action=login");
                        exit;
                    }
                    break;
                case $currentRecord instanceof Task:
                    try {
                        $currentRecord->setName(ValidationService::sanitizeInput($_POST['update_name']));
                        $currentRecord->setDescription(
                            isset($_POST['update_description']) && $_POST['update_description'] !== ''
                                ? ValidationService::sanitizeInput($_POST['update_description'])
                                : null
                        );
                        $currentRecord->setState(ValidationService::sanitizeInput($_POST['update_state']));
                        $currentRecord->setProjectId((int) ValidationService::sanitizeInput($_POST['update_project_id']));
                        $currentRecord->setStartedOn(
                            isset($_POST['update_started_on']) && $_POST['update_started_on'] !== ''
                                ? new DateTimeImmutable(ValidationService::sanitizeInput($_POST['update_started_on']))
                                : null
                        );
                        $currentRecord->setDueDate(
                            isset($_POST['update_due_date']) && $_POST['update_due_date'] !== ''
                                ? new DateTimeImmutable(ValidationService::sanitizeInput($_POST['update_due_date']))
                                : null
                        );
                        $currentRecord->setMemberAssigned(
                            isset($_POST['update_member_assigned']) && $_POST['update_member_assigned'] !== ''
                                ? (int) ValidationService::sanitizeInput($_POST['update_member_assigned'])
                                : null
                        );
                        $repository->update($currentRecord);
                    } catch (Exception $e) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> No se pudo realizar la operación.";
                        $this->list();
                        return;
                    } catch (PDOException $e2) {
                        $_SESSION['ERROR'] = "<strong>ERROR:</strong> Permisos insuficientes.";
                        header("Location: index.php?controller=auth&action=login");
                        exit;
                    }
                    break;
                // Solo se accede cuando pasa algo ánomalo. Se destruye la sesión y se redirige al login
                default:
                    $_SESSION['ERROR'] = "<strong>ERROR:</strong> No se pudo realizar la operación.";
                    header("Location: index.php?controller=auth&action=logout"); // Reenviar a index y destruir sesion
                    exit;
            }
            $_SESSION['INFO'] = "<strong>INFO:</strong> Operación realizada correctamente.";
            header("Location: index.php?controller=dashboard&action=list");
            exit;
        }
    }
    /**
     * Elimina un registro de la base de datos según la tabla actual.
     * Se valida el token CSRF y se comprueba que el usuario esté logueado.
     * Se utiliza la clase Repository correspondiente a la tabla actual
     * para eliminar el registro.
     *
     * @throws PDOException Si no hay permisos para eliminar el registro.
     * @throws Exception Si no se pudo realizar la operación.
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar token CSRF
            if (!CSRFService::validateCSRFToken()) {
                $_SESSION['ERROR'] = "<strong>ERROR:</strong> Token CSRF inválido.";
                header("Location: index.php?controller=auth&action=login"); // Reenviar a index y destruir sesion
                exit;
            }
            $repository = match ($_SESSION['current_table']) {
                'user' => new UserRepository($this->connection),
                'team' => new TeamRepository($this->connection),
                'project' => new ProjectRepository($this->connection),
                'task' => new TaskRepository($this->connection),
            };
            try {
                $repository->delete((int)ValidationService::sanitizeInput($_POST['id']));
            } catch (PDOException $e) {
                $_SESSION['ERROR'] = "<strong>ERROR:</strong> Permisos insuficientes.";
                header("Location: index.php?controller=auth&action=login");
                exit;
            }
            $_SESSION['SUCCESS'] = "<strong>EXITO:</strong> Operación realizada correctamente.";
            header("Location: index.php?controller=dashboard&action=list");
            exit;
        }
    }
}
