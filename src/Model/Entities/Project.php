<?php

/**
 * Representa un proyecto en la aplicación.
 *
 * Propiedades principales:
 * - id: identificador único del proyecto.
 * - name: nombre del proyecto.
 * - description: descripción del proyecto (opcional).
 * - started_at: fecha de comienzo del proyecto (opcional).
 * - due_date: fecha de entrega del proyecto (opcional).
 * - assigned_team: identificador del equipo al que se le ha asignado el proyecto (opcional).
 *
 * Métodos principales:
 * - getId(): devuelve el identificador único del proyecto.
 * - getName(): devuelve el nombre del proyecto.
 * - getDescription(): devuelve la descripción del proyecto (opcional).
 * - getStartedAt(): devuelve la fecha de comienzo del proyecto (opcional).
 * - getDueDate(): devuelve la fecha de entrega del proyecto (opcional).
 * - getAssignedTeam(): devuelve el identificador del equipo al que se le ha asignado el proyecto (opcional).
 * - setName(): cambia el nombre del proyecto.
 * - setDescription(): cambia la descripción del proyecto (opcional).
 * - setStartedAt(): cambia la fecha de comienzo del proyecto (opcional).
 * - setDueDate(): cambia la fecha de entrega del proyecto (opcional).
 * - setAssignedTeam(): cambia el identificador del equipo al que se le ha asignado el proyecto (opcional).
 *
 */
class Project
{
    /**
     * Constructor del proyecto.
     *
     * @param int $id Identificador único del proyecto.
     * @param string $name Nombre del proyecto.
     * @param ?string $description Descripción del proyecto (opcional).
     * @param ?DateTimeImmutable $started_at Fecha de comienzo del proyecto (opcional).
     * @param ?DateTimeImmutable $due_date Fecha de entrega del proyecto (opcional).
     * @param ?int $assigned_team Identificador del equipo al que se le ha asignado el proyecto (opcional).
     */
    public function __construct(
        private int $id,
        private string $name,
        private ?string $description = null,
        private ?DateTimeImmutable $started_at = null,
        private ?DateTimeImmutable $due_date = null,
        private ?int $assigned_team = null
    ) {}

    /**
     * Devuelve el identificador único del proyecto.
     * @return int Identificador único del proyecto.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Cambia el id del proyecto.
     *
     * @param int $id Nuevo id del proyecto.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    /**
     * Devuelve el nombre del proyecto.
     * @return string Nombre del proyecto.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Cambia el nombre del proyecto.
     * @param string $name Nuevo nombre del proyecto.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Devuelve la descripci n del proyecto (si existe).
     * @return ?string Descripci n del proyecto (si existe).
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Cambia la descripci n del proyecto (si existe).
     * @param ?string $description Nueva descripci n del proyecto (si existe).
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * Devuelve la fecha de comienzo del proyecto (si existe).
     * @return ?string Fecha de comienzo del proyecto (si existe).
     */
    public function getStartedAt(): ?string
    {
        return $this->started_at?->format('Y-m-d');
    }

    /**
     * Cambia la fecha de comienzo del proyecto (si existe).
     * @param ?DateTimeImmutable $started_at Nueva fecha de comienzo del proyecto (si existe).
     */
    public function setStartedAt(?DateTimeImmutable $started_at): void
    {
        $this->started_at = $started_at;
    }

    /**
     * Devuelve la fecha de entrega del proyecto (si existe).
     * @return ?string Fecha de entrega del proyecto (si existe).
     */
    public function getDueDate(): ?string
    {
        return $this->due_date?->format('Y-m-d');
    }

    /**
     * Cambia la fecha de entrega del proyecto (si existe).
     * @param ?DateTimeImmutable $due_date Nueva fecha de entrega del proyecto (si existe).
     */
    public function setDueDate(?DateTimeImmutable $due_date): void
    {
        $this->due_date = $due_date;
    }

    /**
     * Devuelve el identificador del equipo al que se le ha asignado el proyecto (si existe).
     * @return ?int Identificador del equipo al que se le ha asignado el proyecto (si existe).
     */
    public function getAssignedTeam(): ?int
    {
        return $this->assigned_team;
    }

    /**
     * Cambia el identificador del equipo al que se le ha asignado el proyecto (si existe).
     * @param ?int $assigned_team Nuevo identificador del equipo al que se le ha asignado el proyecto (si existe).
     */
    public function setAssignedTeam(?int $assigned_team): void
    {
        $this->assigned_team = $assigned_team;
    }
}
