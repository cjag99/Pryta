<?php
require_once "./src/Config/Database.php";
require_once "./src/Model/Entities/Project.php";
require_once "./src/Model/Repositories/Repository.php";
class ProjectRepository extends Repository
{
    public function __construct(PDO $connection)
    {
        return parent::__construct("project", $connection);
    }

    public function create(object $project): bool
    {
        if (!$project instanceof Project) {
            throw new \InvalidArgumentException('El parámetro debe ser una instancia de proyecto.');
        }
        $query = "INSERT INTO $this->table_name (name, description, started_at, due_date, assigned_team) VALUES (:name, :description, :started_at, :due_date, :assigned_team)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ':name' => $project->getName(),
            ':description' => $project->getDescription(),
            ':started_at' => $project->getStartedAt()->format('Y-m-d H:i:s'),
            ':due_date' => $project->getDueDate()->format('Y-m-d H:i:s'),
            ':assigned_team' => $project->getAssignedTeam()
        ]);
        return true;
    }

    public function readAll(): array|null
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
            return new Project(
                $result['id'],
                $result['name'],
                $result['description'],
                new DateTimeImmutable($result['started_at']),
                new DateTimeImmutable($result['due_date']),
                $result['assigned_team']
            );
        }
        return null;
    }
    public function update(object $project): bool
    {
        $query = "UPDATE $this->table_name SET name = :name, description = :description, started_at = :started_at, due_date = :due_date, assigned_team = :assigned_team WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ':name' => $project->getName(),
            ':description' => $project->getDescription(),
            ':started_at' => $project->getStartedAt()->format('Y-m-d H:i:s'),
            ':due_date' => $project->getDueDate()->format('Y-m-d H:i:s'),
            ':assigned_team' => $project->getAssignedTeam(),
            ':id' => $project->getId()
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
