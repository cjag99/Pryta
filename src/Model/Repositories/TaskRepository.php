<?php
require_once "./src/Config/Database.php";
require_once "./src/Model/Entities/Task.php";
require_once "./src/Model/Repositories/Repository.php";

class TaskRepository extends Repository
{
    public function __construct(PDO $connection)
    {
        return parent::__construct('task', $connection);
    }

    public function create(object $task): bool
    {
        if (!$task instanceof Task) {
            throw new \InvalidArgumentException('El objeto a crear debe ser de la clase Tarea.');
        }
        $query = "INSERT INTO $this->table_name (name, description, state, project_id, started_on, due_date) VALUES (:name, :description, :state, :project_id, :started_on, :due_date)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ':name' => $task->getName(),
            ':description' => $task->getDescription(),
            ':state' => $task->getState(),
            ':project_id' => $task->getProjectId(),
            ':started_on' => $task->getStartedOn(),
            ':due_date' => $task->getDueDate()
        ]);
        return $stmt->rowCount() > 0;
    }

    public function readAll(): ?array
    {
        $query = "SELECT * FROM $this->table_name";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function readOne(int $id): ?object
    {
        $query = "SELECT * FROM $this->table_name WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new Task(
                $result['id'],
                $result['name'],
                $result['project_id'],
                $result['description'],
                $result['state'],
                new DateTimeImmutable($result['started_on']),
                new DateTimeImmutable($result['due_date']),
                $result['member_assigned']
            );
        }
        return null;
    }


    public function update(object $task): bool
    {
        if (!$task instanceof Task) {
            throw new \InvalidArgumentException('El objeto a actualizar debe ser de la clase Tarea.');
        }
        $query = "UPDATE $this->table_name 
        SET name = :name, description = :description, state = :state, project_id = :project_id,
        started_on = :started_on, due_date = :due_date, member_assigned = :member_assigned 
        WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ':name' => $task->getName(),
            ':description' => $task->getDescription(),
            ':state' => $task->getState(),
            ':project_id' => $task->getProjectId(),
            ':started_on' => $task->getStartedOn(),
            ':due_date' => $task->getDueDate(),
            ':member_assigned' => $task->getMemberAssigned(),
            ':id' => $task->getId()
        ]);
        return true;
    }

    public function delete(int $id): void
    {

        $query = "DELETE FROM $this->table_name WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);
        header("Location: index.php?controller=dashboard&action=list");
    }
}
