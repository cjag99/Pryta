<?php
require_once "./src/Config/Database.php";
require_once "./src/Model/Entities/Team.php";
require_once "./src/Model/Repositories/Repository.php";
class TeamRepository extends Repository
{

    public function __construct(PDO $connection)
    {
        return parent::__construct('team', $connection);
    }


    public function create(object $team): bool
    {
        if (!$team instanceof Team) {
            throw new InvalidArgumentException('El parámetro debe ser una instancia de equipo.');
        }
        $query = "INSERT INTO $this->table_name (name, description, creation_date, team_leader, is_available) VALUES (:name, :description, :creation_date, :team_leader, :is_available)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ':name' => $team->getName(),
            ':description' => $team->getDescription(),
            ':creation_date' => $team->getCreationDate(),
            ':team_leader' => $team->getTeamLeader(),
            ':is_available' => $team->isAvailable()
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
            return new Team(
                $result['id'],
                $result['name'],
                $result['description'],
                new DateTimeImmutable($result['creation_date']),
                $result['team_leader'],
                $result['is_available']
            );
        }
        return null;
    }

    public function update(object $team): bool
    {
        $query = "UPDATE $this->table_name SET name = :name, description = :description, creation_date = :creation_date, team_leader = :team_leader, is_available = :is_available WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ':id' => $team->getId(),
            ':name' => $team->getName(),
            ':description' => $team->getDescription(),
            ':creation_date' => $team->getCreationDate(),
            ':is_available' => $team->isAvailable() ? 1 : 0,
            ':team_leader' => $team->getTeamLeader() ?? null,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): void
    {
        $query = "DELETE FROM $this->table_name WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);
        header("Location: index.php?controller=dashboard&action=list");
    }
}
