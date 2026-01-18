<?php
require_once __DIR__ . '/TaskState.php';
/**
 * Clase que representa una tarea.
 *
 * Contiene los datos de una tarea y proporciona métodos para acceder a ellos.
 */
class Task
{
    /**
     * Constructor del task.
     *
     * @param int $id Identificador único del task.
     * @param string $name Nombre del task.
     * @param int $project_id Identificador del proyecto al que pertenece.
     * @param ?string $description Descripción del task (opcional).
     * @param string $state Estado del task (opcional, por defecto "Not assigned").
     * @param ?DateTimeImmutable $started_on Fecha de comienzo del task (opcional).
     * @param ?DateTimeImmutable $due_date Fecha de entrega del task (opcional).
     * @param ?int $member_assigned Identificador del miembro al que se le ha asignado el task (opcional).
     */
    public function __construct(
        private int $id,
        private string $name,
        private int $project_id,
        private ?string $description = null,
        private string $state = TaskState::NOT_ASSIGNED->value,
        private ?DateTimeImmutable $started_on = null,
        private ?DateTimeImmutable $due_date = null,
        private ?int $member_assigned = null
    ) {}


    /**
     * Devuelve el identificador único de la tarea.
     * @return int Identificador único de la tarea.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Cambia el nombre de la tarea.
     * @param string $name Nuevo nombre de la tarea.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    /**
     * Devuelve el nombre de la tarea.
     * @return string El nombre de la tarea.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Devuelve la descripción de la tarea (opcional).
     * @return ?string Descripción de la tarea (opcional).
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Establece la descripción de la tarea (opcional).
     * @param ?string $description Descripción de la tarea (opcional).
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * Establece el estado actual de la tarea.
     *
     * @param string $state Estado actual de la tarea. Debe ser uno de los valores definidos en la enumeración TaskState.
     *
     * @throws \InvalidArgumentException Si el estado proporcionado no es válido.
     */
    public function setState(string $state): void
    {
        if (!in_array($state, array_map(fn($s) => $s->value, TaskState::cases()))) {
            throw new \InvalidArgumentException('Invalid task state');
        }
        $this->state = $state;
    }

    /**
     * Devuelve el estado actual de la tarea.
     * @return string Estado actual de la tarea.
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Asigna una fecha de comienzo a la tarea (si existe).
     * @param ?DateTimeImmutable $started_on Fecha de comienzo de la tarea (si existe).
     */
    public function setStartedOn(?DateTimeImmutable $started_on): void
    {
        $this->started_on = $started_on;
    }

    /**
     * Devuelve la fecha de comienzo de la tarea (si existe).
     * @return ?string Fecha de comienzo de la tarea (si existe).
     */
    public function getStartedOn(): ?string
    {
        return $this->started_on?->format('Y-m-d');
    }

    /**
     * Asigna una fecha de entrega a la tarea (si existe).
     * @param ?DateTimeImmutable $due_date Fecha de entrega de la tarea (si existe).
     */
    public function setDueDate(?DateTimeImmutable $due_date): void
    {
        $this->due_date = $due_date;
    }

    /**
     * Devuelve la fecha de entrega de la tarea (si existe).
     * @return ?string Fecha de entrega de la tarea (si existe).
     */
    public function getDueDate(): ?string
    {
        return $this->due_date?->format('Y-m-d');
    }

    /**
     * Asigna un identificador de proyecto a la tarea.
     * @param int $project_id Identificador del proyecto al que se le asigna la tarea.
     */
    public function setProjectId(int $project_id): void
    {
        $this->project_id = $project_id;
    }

    /**
     * Devuelve el identificador del proyecto al que pertenece la tarea.
     * @return int Identificador del proyecto al que pertenece la tarea.
     */
    public function getProjectId(): int
    {
        return $this->project_id;
    }

    /**
     * Asigna un miembro a la tarea (si existe).
     * @param ?int $member_assigned Identificador del miembro asignado a la tarea (si existe).
     */
    public function setMemberAssigned(?int $member_assigned): void
    {
        $this->member_assigned = $member_assigned;
    }

    /**
     * Devuelve el identificador del miembro asignado a la tarea (si existe).
     * @return ?int Identificador del miembro asignado a la tarea (si existe).
     */
    public function getMemberAssigned(): ?int
    {
        return $this->member_assigned;
    }
}
