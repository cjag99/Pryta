<?php


class DashboardController
{
    public function __construct(
        private PDO $connection
    ) {}
    public function home()
    {
        include __DIR__ . "/../Views/Dashboard/home.php";
    }

    public function list($table_name)
    {
        $allowed_tables = [
            'user' => 'user',
            'team' => 'team',
            'project' => 'project',
            'task' => 'task',
        ];

        if (!$table_name || !array_key_exists($table_name, $allowed_tables)) {
            $_SESSION['ERROR'] = "<strong>ERROR: </strong>La tabla seleccionada no es correcta. Por seguridad, vuelva a iniciar sesión.";
            header("Location: index.php?action=login");
            exit();
        }

        $_SESSION['current_table'] = $allowed_tables[$table_name];
        $repository = match ($_SESSION['current_table']) {
            'user' => new UserRepository($_SESSION['connection']),
            //'team' => new TeamRepository($database),
            //'project' => new ProjectRepository($database),
            //'task' => new TaskRepository($database),
        };
        $data = $repository->readAll();
        require "./src/Views/Dashboard/list.php";
    }
}
