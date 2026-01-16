<?php

declare(strict_types=1);

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


    public function create()
    {
        $repository = match ($_SESSION['current_table']) {
            'user' => new UserRepository($this->connection),
            'team' => new TeamRepository($this->connection),
            'project' => new ProjectRepository($this->connection),
            'task' => new TaskRepository($this->connection),
        };
        switch ($_SESSION['current_table']) {
            case 'user':
                $team_id = $_POST['team_id'] ?? null;
                if ($team_id == '' || !is_numeric($team_id)) {
                    $team_id = null;
                } else {
                    $team_id = (int)$team_id;
                }
                $user = new User(
                    0,
                    $_POST['username'],
                    $_POST['name'],
                    $_POST['surname'],
                    $_POST['password'],
                    $_POST['role'],
                    $_POST['email'],
                    $_POST['active'] === '1' ? true : false,
                    $_POST['verified'] === '1' ? true : false,
                    $team_id
                );
                $repository->create($user);
                break;
        }
        header("Location: index.php?controller=dashboard&action=list");
    }
    public function delete()
    {
        $repository = match ($_SESSION['current_table']) {
            'user' => new UserRepository($this->connection),
            'team' => new TeamRepository($this->connection),
            'project' => new ProjectRepository($this->connection),
            'task' => new TaskRepository($this->connection),
        };
        $repository->delete((int)$_POST['id']);
        header("Location: index.php?controller=dashboard&action=list");
    }
}
