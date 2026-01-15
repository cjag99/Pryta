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

    public function list()
    {
        $allowed_tables = [
            'user' => 'user',
            'team' => 'team',
            'project' => 'project',
            'task' => 'task',
        ];

        $table_name = $_POST['table_name'] ?? null;

        if (!array_key_exists($table_name, $allowed_tables)) {
            $table_name = 'user';
        }

        $_SESSION['current_table'] = $allowed_tables[$table_name];
        $repository = match ($_SESSION['current_table']) {
            'user' => new UserRepository($this->connection),
            'team' => new TeamRepository($this->connection),
            'project' => new ProjectRepository($this->connection),
            'task' => new TaskRepository($this->connection),
        };
        $data = $repository->readAll();
        if (empty($data) && $_SESSION['current_table'] === 'user') {
            $_SESSION['error'] = "<strong>ERROR:</strong> No hay usuarios registrados en el sistema.";
            header("Location: index.php?controller=auth&action=login");
            exit();
        } else {
            require "./src/Views/Dashboard/list.php";
        }
    }

    public function delete()
    {
        $repository = match ($_SESSION['current_table']) {
            'user' => new UserRepository($this->connection),
            'team' => new TeamRepository($this->connection),
            'project' => new ProjectRepository($this->connection),
            'task' => new TaskRepository($this->connection),
        };
        $repository->delete($_POST['id']);
        header("Location: index.php?controller=dashboard&action=list");
    }
}
