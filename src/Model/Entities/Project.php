<?php

class Project
{
    public function __construct(
        private int $id,
        private string $name,
        private ?string $description = null,
        private ?DateTimeImmutable $started_at = null,
        private ?DateTimeImmutable $due_date = null,
        private ?int $assigned_team = null
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getStartedAt(): ?string
    {
        return $this->started_at?->format('Y-m-d');
    }

    public function setStartedAt(?DateTimeImmutable $started_at): void
    {
        $this->started_at = $started_at;
    }

    public function getDueDate(): ?string
    {
        return $this->due_date?->format('Y-m-d');
    }

    public function setDueDate(?DateTimeImmutable $due_date): void
    {
        $this->due_date = $due_date;
    }

    public function getAssignedTeam(): ?int
    {
        return $this->assigned_team;
    }

    public function setAssignedTeam(?int $assigned_team): void
    {
        $this->assigned_team = $assigned_team;
    }
}
