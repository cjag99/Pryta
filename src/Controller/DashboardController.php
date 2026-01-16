<?php

declare(strict_types=1);
require_once "./src/Model/Entities/UserException.php";
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

        $table_name = $_POST['table_name'] ?? $_SESSION['current_table'] ?? 'user';

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
                $_SESSION['current_table'] = 'user';
                break;
            case 'team':
                $team = new Team(
                    0,
                    $_POST['reg_name'],
                    $_POST['reg_description'],
                    new DateTimeImmutable($_POST['reg_creation_date']),
                    (isset($_POST['reg_team_leader']) && $_POST['reg_team_leader'] !== '') ? (int)$_POST['reg_team_leader'] : null,
                );
                $repository->create($team);
                $_SESSION['current_table'] = 'team';
                break;
        }
        header("Location: index.php?controller=dashboard&action=list");
        exit();
    }

    public function update()
    {
        $repository = match ($_SESSION['current_table']) {
            'user' => new UserRepository($this->connection),
            'team' => new TeamRepository($this->connection),
            'project' => new ProjectRepository($this->connection),
            'task' => new TaskRepository($this->connection),
        };
        $currentRecord = $repository->readOne((int)$_POST['send_id']);
        switch (true) {
            case $currentRecord instanceof User:
                try {
                    $currentRecord->setUsername($_POST['new_username']);
                    $currentRecord->setName($_POST['new_name']);
                    $currentRecord->setSurname($_POST['new_surname']);
                    if (!empty($_POST['new_password']) && $_POST['new_password'] === $_POST['new_confirm_password']) {
                        $currentRecord->setPasswd($_POST['new_password']);
                    }
                    $currentRecord->setRole($_POST['new_role']);
                    $currentRecord->setEmail($_POST['new_email']);
                    $currentRecord->setActive($_POST['new_active'] === '1' ? true : false);
                    $currentRecord->setVerified($_POST['new_verified'] === '1' ? true : false);
                    $currentRecord->setTeamId(
                        isset($_POST['new_teamId']) && $_POST['new_teamId'] !== ''
                            ? (int)$_POST['new_teamId']
                            : null
                    );

                    $repository->update($currentRecord);
                } catch (UserException $e) {
                    $_SESSION['error'] = $e->getMessage();
                }
                break;
            case $currentRecord instanceof Team:
                try {
                    $currentRecord->setName($_POST['update_name']);
                    $currentRecord->setDescription($_POST['update_description']);
                    $currentRecord->setCreationDate(new DateTimeImmutable($_POST['update_creation_date']));
                    $currentRecord->setTeamLeader(
                        isset($_POST['update_team_leader']) && $_POST['update_team_leader'] !== ''
                            ? (int)$_POST['update_team_leader']
                            : null
                    );
                    $currentRecord->setIsAvailable($_POST['update_is_available'] === '1' ? true : false);
                    $repository->update($currentRecord);
                } catch (Exception $e) {
                    $_SESSION['error'] = $e->getMessage();
                }
        }
        header("Location: index.php?controller=dashboard&action=list");
        exit;
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

    public function settings()
    {
        require "./src/Views/Dashboard/settings.php";
    }
}
