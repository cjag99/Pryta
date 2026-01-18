<?php
require_once "./src/Config/Database.php";
require_once "./src/Model/Entities/Project.php";
require_once "./src/Model/Repositories/Repository.php";
/**
 * Clase que representa un repositorio de proyectos.
 *
 * Esta clase hereda de {@link Repository} y se encarga de realizar operaciones CRUD sobre la tabla de proyectos.
 *
 * @package Pryta\Model\Repositories
 */
class ProjectRepository extends Repository
{

    /**
     * Constructor del repositorio de proyectos.
     *
     * @param PDO $connection Conexión a la base de datos
     */
    public function __construct(PDO $connection)
    {
        return parent::__construct("project", $connection);
    }

    /**
     * Crea un proyecto en la base de datos.
     *
     * @param Project $project El proyecto a crear.
     *
     * @return bool True si se creó correctamente, false en caso contrario.
     *
     * @throws \InvalidArgumentException Si el parámetro no es una instancia de proyecto.
     */
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
            ':started_at' => $project->getStartedAt(),
            ':due_date' => $project->getDueDate(),
            ':assigned_team' => $project->getAssignedTeam()
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Lee todos los proyectos en la base de datos.
     *
     * @return array|null Un array asociativo con los proyectos o null si no hay resultados.
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
     * Lee un proyecto por su id.
     * 
     * @param int $id Identificador del proyecto.
     * @return ?object Un proyecto si se encuentra, null en caso contrario.
     */
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

    /**
     * Devuelve un array asociativo con los identificadores y nombres de los proyectos.
     * 
     * @return array|null Un array con los identificadores y nombres de los proyectos o null si no hay resultados.
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
     * Actualiza un proyecto en la base de datos.
     * 
     * @param Project $project El proyecto a actualizar.
     * @return bool True si se actualizó correctamente, false en caso contrario.
     */
    public function update(object $project): bool
    {
        $query = "UPDATE $this->table_name SET name = :name, description = :description, started_at = :started_at, due_date = :due_date, assigned_team = :assigned_team WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ':name' => $project->getName(),
            ':description' => $project->getDescription(),
            ':started_at' => $project->getStartedAt(),
            ':due_date' => $project->getDueDate(),
            ':assigned_team' => $project->getAssignedTeam(),
            ':id' => $project->getId()
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Elimina un proyecto por su id.
     *
     * @param int $id Identificador del proyecto a eliminar.
     */
    public function delete(int $id): void
    {

        $query = "DELETE FROM $this->table_name WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);
        header("Location: index.php?controller=dashboard&action=list");
    }
}
