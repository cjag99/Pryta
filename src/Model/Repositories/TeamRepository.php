<?php
require_once "./src/Config/Database.php";
require_once "./src/Model/Entities/Team.php";
require_once "./src/Model/Repositories/Repository.php";
/**
 * Clase que representa un repositorio de equipos.
 *
 * Esta clase hereda de {@link Repository} y se encarga de realizar operaciones CRUD sobre la tabla de equipos.
 *
 * @package Pryta\Model\Repositories
 */
class TeamRepository extends Repository
{

    /**
     * Constructor del repositorio de equipos.
     *
     * @param PDO $connection Conexión a la base de datos
     */
    public function __construct(PDO $connection)
    {
        return parent::__construct('team', $connection);
    }


    /**
     * Crea un equipo en la base de datos.
     *
     * @param Team $team Equipo a crear
     * @return bool True si el equipo se creó correctamente, false en caso contrario
     * @throws InvalidArgumentException Si el parámetro no es una instancia de equipo
     */
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

    /**
     * Lee todos los equipos en la base de datos.
     *
     * @return array|null Un array asociativo con los equipos o null si no hay resultados.
     */
    public function readAll(): array|null
    {
        $query = "SELECT * FROM $this->table_name";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Lee los identificadores y nombres de todos los equipos en la base de datos.
     *
     * @return array|null Un array asociativo con los identificadores y nombres de los equipos o null si no hay resultados.
     */
    public function readIdNames(): array|null
    {
        $query = "SELECT id, name FROM $this->table_name";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    /**
     * Lee un equipo en la base de datos por su id.
     *
     * @param int $id Identificador del equipo a leer.
     * @return ?object Un objeto de la clase Team si se encuentra, null en caso contrario.
     */
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

    /**
     * Actualiza un equipo en la base de datos.
     *
     * @param object $team El objeto de la clase Team a actualizar.
     *
     * @return bool True si el equipo se actualizó correctamente, false en caso contrario.
     *
     * @throws \InvalidArgumentException Si el objeto a actualizar no es de la clase Team.
     */
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


    /**
     * Elimina un equipo por su id.
     *
     * @param int $id Identificador del equipo a eliminar.
     */
    public function delete(int $id): void
    {
        $query = "DELETE FROM $this->table_name WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);
        header("Location: index.php?controller=dashboard&action=list");
    }
}
