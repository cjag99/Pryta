<?php

/**
 * Representa un equipo en la aplicación.
 *
 * Propiedades principales:
 * - id: identificador único del equipo.
 * - name: nombre del equipo.
 * - description: descripción del equipo (opcional).
 * - creation_date: fecha de creación del equipo.
 * - team_leader: identificador del líder del equipo (opcional).
 * - is_available: indica si el equipo está disponible o no.
 *
 * Métodos principales:
 * - getId(): devuelve el identificador único del equipo.
 * - getName(): devuelve el nombre del equipo.
 * - getDescription(): devuelve la descripción del equipo (opcional).
 * - getCreationDate(): devuelve la fecha de creación del equipo.
 * - getTeamLeader(): devuelve el identificador del líder del equipo (opcional).
 * - isAvailable(): indica si el equipo está disponible o no.
 *
 * Notas de seguridad:
 * - Algunos setters sensibles solo pueden ser ejecutados por un Superadmin
 *   (se lanzará UserException en caso contrario).
 */
class Team
{
    /**
     * Constructor del equipo.
     * @param int $id Identificador único del equipo.
     * @param string $name Nombre del equipo.
     * @param ?string $description Descripción del equipo (opcional).
     * @param ?DateTimeImmutable $creation_date Fecha de creación del equipo.
     * @param ?int $team_leader Identificador del líder del equipo (opcional).
     * @param bool $is_available Indica si el equipo está disponible o no.
     */
    public function __construct(
        private int $id,
        private string $name,
        private ?string $description = null,
        private ?DateTimeImmutable $creation_date = null,
        private ?int $team_leader = null,
        private bool $is_available = true
    ) {
        $this->creation_date = $creation_date ?? new DateTimeImmutable();
    }

    /**
     * Devuelve el identificador único del equipo.
     * @return int Identificador único del equipo.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Devuelve el nombre del equipo.
     * @return string Nombre del equipo.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Devuelve la descripción del equipo (opcional).
     * @return ?string Descripción del equipo (opcional).
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Devuelve la fecha de creación del equipo.
     * @return ?string Fecha de creación del equipo.
     */
    public function getCreationDate(): ?string
    {
        return $this->creation_date?->format('Y-m-d');
    }

    /**
     * Devuelve el identificador del líder del equipo (opcional).
     * @return ?int Identificador del líder del equipo (opcional).
     */
    public function getTeamLeader(): ?int
    {
        return $this->team_leader;
    }

    /**
     * Indica si el equipo está disponible o no.
     * @return bool Indica si el equipo está disponible o no.
     */
    public function isAvailable(): bool
    {
        return $this->is_available;
    }

    /**
     * Establece el nombre del equipo.
     * @param string $name Nombre del equipo.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Establece la descripción del equipo (opcional).
     * @param ?string $description Descripción del equipo (opcional).
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * Establece la fecha de creación del equipo.
     * @param ?DateTimeImmutable $creation_date Fecha de creación del equipo.
     */
    public function setCreationDate(?DateTimeImmutable $creation_date): void
    {
        $this->creation_date = $creation_date ?? new DateTimeImmutable();
    }

    /**
     * Establece el identificador del líder del equipo (opcional).
     * @param ?int $team_leader Identificador del líder del equipo (opcional).
     */
    public function setTeamLeader(?int $team_leader): void
    {
        $this->team_leader = $team_leader;
    }

    /**
     * Establece si el equipo está disponible o no.
     * @param bool $is_available Indica si el equipo está disponible o no.
     */
    public function setIsAvailable(bool $is_available): void
    {
        $this->is_available = $is_available;
    }
}
