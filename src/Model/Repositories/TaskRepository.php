<?php
require_once "./src/Config/Database.php";
require_once "./src/Model/Entities/Task.php";
require_once "./src/Model/Repositories/Repository.php";

/**
 * Clase que representa un repositorio de tareas.
 *
 * Esta clase hereda de {@link Repository} y se encarga de realizar operaciones CRUD sobre la tabla de tareas.
 *
 * @package Pryta\Model\Repositories
 */
class TaskRepository extends Repository
{
    /**
     * Constructor del repositorio de tareas.
     *
     * @param PDO $connection Conexión a la base de datos
     */
    public function __construct(PDO $connection)
    {
        return parent::__construct('task', $connection);
    }

    /**
     * Crea una nueva tarea en la base de datos.
     *
     * @param Task $task Tarea a crear.
     *
     * @return bool Verdadero que indica si la tarea se cre o correctamente.
     *
     * @throws \InvalidArgumentException Si el objeto a crear no es de la clase Tarea.
     */
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

    /**
     * Lee todas las tareas en la base de datos.
     *
     * @return array|null Un array asociativo con las tareas o null si no hay resultados.
     */
    public function readAll(): ?array
    {
        $query = "SELECT * FROM $this->table_name";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Lee una tarea en la base de datos por su id.
     *
     * @param int $id Identificador de la tarea a leer.
     *
     * @return ?object Un objeto de la clase Tarea si se encuentra, null en caso contrario.
     */
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


    /**
     * Actualiza una tarea en la base de datos.
     *
     * @param object $task El objeto de la clase Tarea a actualizar.
     *
     * @return bool True si la tarea se actualizó correctamente, false en caso contrario.
     *
     * @throws \InvalidArgumentException Si el objeto a actualizar no es de la clase Tarea.
     */
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

    /**
     * Elimina una tarea de la base de datos.
     *
     * @param int $id Identificador de la tarea a eliminar.
     */

    public function delete(int $id): void
    {

        $query = "DELETE FROM $this->table_name WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([':id' => $id]);
        header("Location: index.php?controller=dashboard&action=list");
    }
}
